<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guru>
 */
class GuruFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->guru(),
            'nip' => fake()->unique()->numerify('198########2026##'),
            'nama_lengkap' => fake()->name(),
            'gelar_depan' => fake()->optional(0.3)->randomElement(['Drs.', 'Dr.', 'Ir.']),
            'gelar_belakang' => fake()->randomElement(['S.Pd.', 'M.Pd.', 'S.Si.', 'M.Si.']),
            'jk' => fake()->randomElement(['L', 'P']),
            'no_hp' => fake()->phoneNumber(),
            'jabatan' => fake()->randomElement(['Guru Mata Pelajaran', 'Wali Kelas', 'Pembina Ekstrakulikuler']),
        ];
    }
}
