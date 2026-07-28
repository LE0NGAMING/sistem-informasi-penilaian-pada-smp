<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Penilaian;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penilaian>
 */
class PenilaianFactory extends Factory
{
    public function definition(): array
    {
        $harian = fake()->randomFloat(2, 60, 95);
        $tugas = fake()->randomFloat(2, 65, 98);
        $quiz = fake()->randomFloat(2, 60, 95);
        $uts = fake()->randomFloat(2, 55, 95);
        $uas = fake()->randomFloat(2, 60, 98);
        $praktik = fake()->randomFloat(2, 70, 95);
        $proyek = fake()->randomFloat(2, 70, 98);
        $portofolio = fake()->randomFloat(2, 70, 95);

        // Rumus Bobot Standar SMP: (Harian*15% + Tugas*15% + Quiz*10% + UTS*25% + UAS*25% + Praktik*10%)
        $nilaiAkhir = round(
            ($harian * 0.15) +
                ($tugas * 0.15) +
                ($quiz * 0.10) +
                ($uts * 0.25) +
                ($uas * 0.25) +
                ($praktik * 0.10),
            2
        );

        $predikat = match (true) {
            $nilaiAkhir >= 90 => 'A',
            $nilaiAkhir >= 80 => 'B',
            $nilaiAkhir >= 70 => 'C',
            default => 'D',
        };

        return [
            'siswa_id' => Siswa::factory(),
            'mapel_id' => Mapel::factory(),
            'semester_id' => Semester::factory(),
            'rombel_id' => Rombel::factory(),
            'guru_id' => Guru::factory(),
            'nilai_harian' => $harian,
            'tugas' => $tugas,
            'quiz' => $quiz,
            'uts' => $uts,
            'uas' => $uas,
            'praktik' => $praktik,
            'proyek' => $proyek,
            'portofolio' => $portofolio,
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $predikat,
            'deskripsi' => 'Menunjukkan pemahaman yang sangat baik dalam seluruh kompetensi dasar.',
            'is_remedial' => $nilaiAkhir < 75,
            'nilai_remedial' => $nilaiAkhir < 75 ? 75.00 : null,
        ];
    }
}
