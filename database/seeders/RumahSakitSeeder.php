<?php

namespace Database\Seeders;

use App\Models\RumahSakit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RumahSakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Tambah 5 RS random
        for ($i = 1; $i <= 5; $i++) {
            RumahSakit::create([
                'nama_rs' => 'RS ' . $faker->city,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'telepon' => $faker->phoneNumber,
            ]);
        }
    }
}
