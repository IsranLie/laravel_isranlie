<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Tambah 5 pasien random
        for ($i = 1; $i <= 5; $i++) {
            Pasien::create([
                'nama_pasien' => $faker->firstName,
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber,
                'rs_id' => rand(1, 5), // mengacu ke RS id yang ada
            ]);
        }
    }
}
