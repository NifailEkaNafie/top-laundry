<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Pastikan import ini ada

class DatabaseSeeder extends Seeder
{
    // Menggunakan trait ini akan mematikan event model (seperti boot creating uuid otomatis)
    // Jadi kita harus input UUID secara manual di bawah
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            // password default biasanya 'password'
        ]);

        // 2. Buat Customer Default (Walk-in) secara manual
        Customer::create([
            'uuid' => (string) Str::uuid(), // Generate UUID manual
            'name' => 'Walk-in Customer',
            'phone' => 'N/A',
            'address' => 'N/A',
        ]);

        // 3. Panggil Seeder Customer Lainnya (pastikan CustomerSeeder juga sudah pakai UUID manual)
        $this->call([
            CustomerSeeder::class,
        ]);
    }
}
