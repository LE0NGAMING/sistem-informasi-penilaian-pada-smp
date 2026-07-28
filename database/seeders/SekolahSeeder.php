<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        Sekolah::updateOrCreate(
            ['npsn' => '20101234'],
            [
                'nama_sekolah' => 'SMP Negeri 1 Digital Excellence',
                'alamat' => 'Jl. Pendidikan No. 45, Kompleks Edukasi Modern',
                'kode_pos' => '10110',
                'no_telepon' => '(021) 555-0199',
                'email' => 'info@smpn1digital.sch.id',
                'website' => 'https://smpn1digital.sch.id',
                'nama_kepala_sekolah' => 'Dr. H. Ahmad Sanusi, M.Pd.',
                'nip_kepala_sekolah' => '197508121999031002',
            ]
        );
    }
}
