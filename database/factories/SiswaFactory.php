<?php

namespace Database\Factories;

use App\Models\OrangTua;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->siswa(),
            'orang_tua_id' => null,
            'rombel_aktif_id' => null,
            'nis' => fake()->unique()->numerify('24257###'),
            'nisn' => fake()->unique()->numerify('008#######'),
            'nama_lengkap' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-15 years', '-12 years')->format('Y-m-d'),
            'jk' => fake()->randomElement(['L', 'P']),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'status' => 'aktif',
        ];
    }
}
