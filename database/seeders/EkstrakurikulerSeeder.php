<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_ekskul' => 'Pramuka (Wajib)', 'pembina' => 'Budi Santoso, S.Pd'],
            ['nama_ekskul' => 'Paskibra', 'pembina' => 'Siti Aminah, S.Pd'],
            ['nama_ekskul' => 'PMR (Palang Merah Remaja)', 'pembina' => 'Dr. Rina'],
            ['nama_ekskul' => 'Futsal / Olahraga', 'pembina' => 'Andi Wijaya, S.Pd'],
            ['nama_ekskul' => 'Seni Musik / Tari', 'pembina' => 'Dewi Lestari, S.Sn'],
        ];

        foreach ($data as $item) {
            Ekstrakurikuler::updateOrCreate(
                ['nama_ekskul' => $item['nama_ekskul']], // Kunci pencarian agar tidak duplikat
                [
                    'pembina' => $item['pembina'],
                    // 'sekolah_id' => 1, // Buka komentar ini jika ekskul diikat ke sekolah tertentu
                ]
            );
        }
    }
}
