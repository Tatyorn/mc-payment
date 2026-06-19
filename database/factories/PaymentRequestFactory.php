<?php

namespace Database\Factories;

use App\Enums\PaymentRequestStatus;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentRequest>
 */
class PaymentRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'description' => fake()->sentence(),
            'invoice' => 'invoices/test.pdf',
            'amount' => fake()->randomFloat(2, 10, 10000),
            'currency' => 'USD',
            'exchange_rate' => 1.0,
            'exchange_rate_source' => 'exchangerate-api.com',
            'exchanged_at' => now(),
            'eur_amount' => fn (array $attrs) => $attrs['amount'] / $attrs['exchange_rate'],
            'status' => PaymentRequestStatus::PENDING,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentRequestStatus::PENDING,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentRequestStatus::APPROVED,
            'approved_by' => User::factory()->finance(),
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentRequestStatus::REJECTED,
            'approved_by' => User::factory()->finance(),
            'approved_at' => now(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentRequestStatus::EXPIRED,
        ]);
    }
}
