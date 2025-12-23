<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer; // Added
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Add a default "Walk-in Customer"
        Customer::create([
            'name' => 'Walk-in Customer',
            'phone' => 'N/A', // Or a generic number
            'address' => 'N/A',
        ]);
    }
}
