<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterActorSeeder extends Seeder
{
    public function run(): void
    {
        $taAktif = TahunAjaran::where('is_active', true)->first();
        $kelas7 = Kelas::where('tingkat', 7)->first();

        // 1. Create Guru Wali Kelas
        $userWali = User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'email' => 'budi.santoso@smpn1digital.sch.id',
            'password' => Hash::make('Password123!'),
            'role' => 'wali_kelas',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $guruWali = Guru::create([
            'user_id' => $userWali->id,
            'nip' => '198503122010011005',
            'nama_lengkap' => 'Budi Santoso',
            'gelar_belakang' => 'S.Pd.',
            'jk' => 'L',
            'no_hp' => '081234567890',
            'jabatan' => 'Wali Kelas 7-A',
        ]);

        // 2. Create Rombel 7-A
        $rombel = Rombel::create([
            'kelas_id' => $kelas7->id,
            'tahun_ajaran_id' => $taAktif->id,
            'wali_kelas_id' => $guruWali->id,
            'nama_rombel' => '7-A',
            'kuota' => 32,
        ]);

        // 3. Create Orang Tua & Siswa Contoh
        for ($i = 1; $i <= 5; $i++) {
            $userOrtu = User::create([
                'name' => "Orang Tua Siswa {$i}",
                'email' => "ortu.siswa{$i}@gmail.com",
                'password' => Hash::make('Password123!'),
                'role' => 'orang_tua',
                'is_active' => true,
            ]);

            $ortu = OrangTua::create([
                'user_id' => $userOrtu->id,
                'nama_ayah' => "Ayah Siswa {$i}",
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => "Ibu Siswa {$i}",
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'no_hp' => '08198765432' . $i,
                'alamat' => "Jl. Mawar No. {$i}, Jakarta",
            ]);

            $userSiswa = User::create([
                'name' => "Siswa Teladan {$i}",
                'email' => "siswa{$i}@smpn1digital.sch.id",
                'password' => Hash::make('Password123!'),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            Siswa::create([
                'user_id' => $userSiswa->id,
                'orang_tua_id' => $ortu->id,
                'rombel_aktif_id' => $rombel->id,
                'nis' => '242500' . $i,
                'nisn' => '008123450' . $i,
                'nama_lengkap' => "Siswa Teladan {$i}",
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2012-05-10',
                'jk' => $i % 2 === 0 ? 'P' : 'L',
                'agama' => 'Islam',
                'alamat' => "Jl. Mawar No. {$i}, Jakarta",
                'status' => 'aktif',
            ]);
        }
    }
}
