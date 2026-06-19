<?php

namespace Tests\Feature;

use App\Enums\PaymentRequestStatus;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;

use function Pest\Laravel\travelTo;

beforeEach(function () {
    Http::fake([
        'v6.exchangerate-api.com/*' => Http::response([
            'result' => 'success',
            'conversion_rate' => 1.09,
            'time_last_update_unix' => now()->timestamp,
        ]),
    ]);
});

it('creates a payment request', function () {
    $user = User::factory()->create(['currency' => 'USD']);
    Passport::actingAs($user);
    $invoice = UploadedFile::fake()->create('invoice.pdf', 1024, 'application/pdf');

    $response = $this->post('/api/payment-requests', [
        'description' => 'New laptop',
        'amount' => 1500.00,
        'currency' => 'USD',
        'invoice' => $invoice,
    ], ['Accept' => 'application/json']);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'invoice',
                'amount',
                'currency',
                'exchange_rate',
                'exchange_rate_source',
                'exchanged_at',
                'eur_amount',
                'status',
            ],
        ]);

    expect($response['data']['status'])->toBe('pending')
        ->and($response['data']['currency'])->toBe('USD')
        ->and($response['data']['eur_amount'])->toEqual(round(1500 * 1.09, 2));
});

it('fails to create with invalid currency', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $invoice = UploadedFile::fake()->create('invoice.pdf', 1024, 'application/pdf');

    $response = $this->postJson('/api/payment-requests', [
        'description' => 'Test',
        'amount' => 100,
        'currency' => 'INVALID',
        'invoice' => $invoice,
    ]);

    $response->assertUnprocessable();
});

it('fails to create with negative amount', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $invoice = UploadedFile::fake()->create('invoice.pdf', 1024, 'application/pdf');

    $response = $this->postJson('/api/payment-requests', [
        'description' => 'Test',
        'amount' => -100,
        'currency' => 'USD',
        'invoice' => $invoice,
    ]);

    $response->assertUnprocessable();
});

it('lists payment requests with status filter', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    PaymentRequest::factory()->count(3)->pending()->create(['user_id' => $user->id]);
    PaymentRequest::factory()->count(2)->approved()->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/payment-requests?status=pending');

    $response->assertSuccessful()
        ->assertJsonStructure(['data' => [['id', 'description', 'amount', 'currency', 'status']]]);
});

it('shows a single payment request', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);
    $paymentRequest = PaymentRequest::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson("/api/payment-requests/{$paymentRequest->id}");

    $response->assertSuccessful()
        ->assertJson([
            'data' => [
                'id' => $paymentRequest->id,
                'description' => $paymentRequest->description,
            ],
        ]);
});

it('approves a pending request as finance', function () {
    $finance = User::factory()->finance()->create();
    Passport::actingAs($finance);
    $paymentRequest = PaymentRequest::factory()->pending()->create();

    $response = $this->patchJson("/api/payment-requests/{$paymentRequest->id}/approve");

    $response->assertSuccessful();
    expect($response['data']['status'])->toBe('approved');
});

it('fails to approve as employee', function () {
    $employee = User::factory()->employee()->create();
    Passport::actingAs($employee);
    $paymentRequest = PaymentRequest::factory()->pending()->create();

    $response = $this->patchJson("/api/payment-requests/{$paymentRequest->id}/approve");

    $response->assertForbidden();
});

it('rejects a pending request as finance', function () {
    $finance = User::factory()->finance()->create();
    Passport::actingAs($finance);
    $paymentRequest = PaymentRequest::factory()->pending()->create();

    $response = $this->patchJson("/api/payment-requests/{$paymentRequest->id}/reject");

    $response->assertSuccessful();
    expect($response['data']['status'])->toBe('rejected');
});

it('fails to approve an already approved request', function () {
    $finance = User::factory()->finance()->create();
    Passport::actingAs($finance);
    $paymentRequest = PaymentRequest::factory()->approved()->create();

    $response = $this->patchJson("/api/payment-requests/{$paymentRequest->id}/approve");

    $response->assertStatus(422)
        ->assertJson([
            'type' => 'business_rule_violation',
            'title' => 'Business Rule Violation',
            'detail' => 'Only pending requests can be approved.',
        ]);
});

it('auto-expires pending requests after 48 hours', function () {
    $user = User::factory()->create();

    $now = now();
    travelTo($now->copy()->subHours(49));
    PaymentRequest::factory()->pending()->create([
        'user_id' => $user->id,
    ]);

    travelTo($now->copy()->subHours(47));
    PaymentRequest::factory()->pending()->create([
        'user_id' => $user->id,
    ]);

    travelTo($now);
    $this->artisan('payment:expire');

    expect(PaymentRequest::where('status', PaymentRequestStatus::EXPIRED)->count())->toBe(1)
        ->and(PaymentRequest::where('status', PaymentRequestStatus::PENDING)->count())->toBe(1);
});
