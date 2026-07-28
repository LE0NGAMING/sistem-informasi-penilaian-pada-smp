<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\KKM;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tahun Ajaran & Semester
        $ta = TahunAjaran::create([
            'tahun' => '2025/2026',
            'is_active' => true,
        ]);

        Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester' => 'ganjil',
            'is_active' => true,
            'tanggal_mulai' => '2025-07-15',
            'tanggal_selesai' => '2025-12-20',
        ]);

        Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester' => 'genap',
            'is_active' => false,
            'tanggal_mulai' => '2026-01-05',
            'tanggal_selesai' => '2026-06-20',
        ]);

        // 2. Tingkat Kelas
        foreach ([7 => 'Kelas 7', 8 => 'Kelas 8', 9 => 'Kelas 9'] as $tingkat => $nama) {
            Kelas::firstOrCreate(['tingkat' => $tingkat], ['nama_kelas' => $nama]);
        }

        // 3. Mata Pelajaran SMP Kurikulum Merdeka
        $mapelList = [
            ['kode' => 'PAI', 'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'kelompok' => 'A', 'urutan' => 1],
            ['kode' => 'PPKN', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelompok' => 'A', 'urutan' => 2],
            ['kode' => 'INDO', 'nama_mapel' => 'Bahasa Indonesia', 'kelompok' => 'A', 'urutan' => 3],
            ['kode' => 'MTK', 'nama_mapel' => 'Matematika', 'kelompok' => 'A', 'urutan' => 4],
            ['kode' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kelompok' => 'A', 'urutan' => 5],
            ['kode' => 'IPS', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kelompok' => 'A', 'urutan' => 6],
            ['kode' => 'ING', 'nama_mapel' => 'Bahasa Inggris', 'kelompok' => 'A', 'urutan' => 7],
            ['kode' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelompok' => 'B', 'urutan' => 8],
            ['kode' => 'SBK', 'nama_mapel' => 'Seni Budaya', 'kelompok' => 'B', 'urutan' => 9],
            ['kode' => 'INFOR', 'nama_mapel' => 'Informatika', 'kelompok' => 'B', 'urutan' => 10],
        ];

        foreach ($mapelList as $dataMapel) {
            $mapel = Mapel::create($dataMapel);

            // Create KKM Default untuk tiap tingkat kelas (75)
            foreach ([7, 8, 9] as $tingkat) {
                KKM::create([
                    'mapel_id' => $mapel->id,
                    'tingkat_kelas' => $tingkat,
                    'nilai_kkm' => 75,
                ]);
            }
        }
    }
}
