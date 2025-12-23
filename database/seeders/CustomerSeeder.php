<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Str; // Penting untuk generate UUID

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Dummy Pelanggan (Individu & Corporate)
        $customers = [
            [
                'name' => 'Ibu Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Mawar Merah No. 12, Lowokwaru, Malang',
            ],
            [
                'name' => 'Pak Slamet Riyadi',
                'phone' => '085678901234',
                'address' => 'Perumahan Griya Shanta Blok A-5, Malang',
            ],
            [
                'name' => 'Siti Aminah',
                'phone' => '081908080808',
                'address' => 'Jl. Soekarno Hatta No. 45 (Sebelah Indomaret), Malang',
            ],
            [
                'name' => 'Hotel Melati Indah',
                'phone' => '0341-404040',
                'address' => 'Jl. Jaksa Agung Suprapto No. 20, Klojen, Malang',
            ],
            [
                'name' => 'Restoran Padang Murah',
                'phone' => '081333444555',
                'address' => 'Jl. Gajayana No. 88, Dinoyo, Malang',
            ],
        ];

        foreach ($customers as $data) {
            // Generate UUID manual agar tidak error di DatabaseSeeder
            $data['uuid'] = (string) Str::uuid();

            Customer::create($data);
        }
    }
}
