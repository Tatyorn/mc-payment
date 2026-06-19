<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->finance()->create([
            'name' => 'Finance User',
            'email' => 'finance@mcpayment.com',
            'currency' => 'EUR',
        ]);

        $employees = [
            ['name' => 'Alice Johnson', 'email' => 'alice@mcpayment.com', 'currency' => 'USD'],
            ['name' => 'Carlos Silva', 'email' => 'carlos@mcpayment.com', 'currency' => 'BRL'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki@mcpayment.com', 'currency' => 'JPY'],
            ['name' => 'James Smith', 'email' => 'james@mcpayment.com', 'currency' => 'GBP'],
            ['name' => 'Marie Dubois', 'email' => 'marie@mcpayment.com', 'currency' => 'EUR'],
        ];

        foreach ($employees as $employee) {
            User::factory()->employee()->withCurrency($employee['currency'])->create([
                'name' => $employee['name'],
                'email' => $employee['email'],
            ]);
        }
    }
}
