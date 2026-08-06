<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel; // Sesuaikan dengan nama model Mapel Anda

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            [
                'kode_mapel' => 'MP-001',
                'nama_mapel' => 'Matematika',
                'kelompok'   => 'Wajib',
            ],
            [
                'kode_mapel' => 'MP-002',
                'nama_mapel' => 'Bahasa Indonesia',
                'kelompok'   => 'Wajib',
            ],
            [
                'kode_mapel' => 'MP-003',
                'nama_mapel' => 'Bahasa Inggris',
                'kelompok'   => 'Wajib',
            ],
            [
                'kode_mapel' => 'MP-004',
                'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti',
                'kelompok'   => 'Wajib',
            ],
            [
                'kode_mapel' => 'MP-005',
                'nama_mapel' => 'Pancasila dan Kewarganegaraan',
                'kelompok'   => 'Wajib',
            ],
            [
                'kode_mapel' => 'MP-006',
                'nama_mapel' => 'Informatika',
                'kelompok'   => 'Peminatan',
            ],
            [
                'kode_mapel' => 'MP-007',
                'nama_mapel' => 'Fisika',
                'kelompok'   => 'Peminatan',
            ],
            [
                'kode_mapel' => 'MP-008',
                'nama_mapel' => 'Kimia',
                'kelompok'   => 'Peminatan',
            ],
            [
                'kode_mapel' => 'MP-009',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'kelompok'   => 'Umum',
            ],
            [
                'kode_mapel' => 'MP-010',
                'nama_mapel' => 'Seni Budaya',
                'kelompok'   => 'Umum',
            ],
        ];

        foreach ($mapels as $mapel) {
            Mapel::updateOrCreate(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }
    }
}
