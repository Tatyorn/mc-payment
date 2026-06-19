<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('registers a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'currency' => 'USD',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email', 'role', 'currency'],
        ]);

    expect($response['user']['role'])->toBe('employee')
        ->and($response['user']['currency'])->toBe('USD');
});

it('fails to register with invalid data', function () {
    $response = $this->postJson('/api/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'currency' => 'INVALID',
    ]);

    $response->assertUnprocessable();
});

it('logs in a user', function () {
    Client::factory()->create([
        'name' => 'Test Personal Access Client',
        'redirect_uris' => [],
        'grant_types' => ['personal_access'],
    ]);

    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'token',
        ]);
});

it('fails to login with wrong credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('correct'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrong',
    ]);

    $response->assertUnauthorized()
        ->assertJson([
            'type' => 'authentication_failed',
            'title' => 'Authentication Failed',
            'detail' => 'Invalid credentials.',
        ]);
});

it('logs out a user', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = postJson('/api/logout');

    $response->assertSuccessful()
        ->assertJson(['message' => 'Logged out successfully.']);
});

it('returns user data when authenticated', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = getJson('/api/user');

    $response->assertSuccessful()
        ->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('fails when not authenticated', function () {
    $this->getJson('/api/user')->assertUnauthorized();
    $this->postJson('/api/logout')->assertUnauthorized();
});
