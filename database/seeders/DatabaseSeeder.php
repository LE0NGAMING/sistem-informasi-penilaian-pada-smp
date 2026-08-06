<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MapelSeeder::class,
        ]);

        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. MASTER DATA: KELAS & TAHUN AJARAN
        // ==========================================
        $kelas7 = Kelas::create(['nama_kelas' => 'Kelas 7', 'tingkat' => '7']);
        $kelas8 = Kelas::create(['nama_kelas' => 'Kelas 8', 'tingkat' => '8']);
        $kelas9 = Kelas::create(['nama_kelas' => 'Kelas 9', 'tingkat' => '9']);

        $tahunAjaran = TahunAjaran::create([
            'tahun'     => '2025/2026',
            'is_active' => true,
        ]);

        // ==========================================
        // 2. AKUN SYSTEM: SUPER ADMIN & ADMIN
        // ==========================================

        // 1 Super Admin
        User::create([
            'name'     => 'Super Admin Utama',
            'email'    => 'superadmin@smp.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
        ]);

        // 2 Admin Sekolah
        User::create([
            'name'     => 'Admin TU 1',
            'email'    => 'admin1@smp.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'admin_sekolah',
        ]);

        User::create([
            'name'     => 'Admin TU 2',
            'email'    => 'admin2@smp.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'admin_sekolah',
        ]);

        // ==========================================
        // 3. KEPALA SEKOLAH (User + Profile Guru)
        // ==========================================
        $userKepsek = User::create([
            'name'     => 'Drs. H. Ahmad Dahlan, M.Pd.',
            'email'    => 'kepsek@smp.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'kepala_sekolah',
        ]);

        $tglLahirKepsek = '1975-01-01';
        Guru::create([
            'user_id'                => $userKepsek->id,
            'nip'                    => '197501012000031001',
            'nama_lengkap'           => 'Ahmad Dahlan',
            'gelar'                  => 'Drs. H., M.Pd.',
            'jenis_kelamin'          => 'L',
            'tanggal_lahir'          => $tglLahirKepsek,
            'no_hp'                  => '081234567890',
            'tanggal_mulai_mengajar' => '2000-03-01',
            'tanggal_pensiun'        => Carbon::parse($tglLahirKepsek)->addYears(60)->format('Y-m-d'),
        ]);

        // ==========================================
        // 4. DATA GURU (10 ORANG)
        // ==========================================
        for ($i = 1; $i <= 10; $i++) {
            $gender   = $i % 2 == 0 ? 'P' : 'L';
            $nama     = $gender == 'L' ? $faker->name('male') : $faker->name('female');
            $tglLahir = $faker->date('Y-m-d', '1995-01-01');

            $userGuru = User::create([
                'name'     => $nama,
                'email'    => "guru{$i}@smp.sch.id",
                'password' => Hash::make('password'),
                'role'     => 'guru',
            ]);

            Guru::create([
                'user_id'                => $userGuru->id,
                'nip'                    => '198505' . str_pad($i, 2, '0', STR_PAD_LEFT) . '201001' . ($gender == 'L' ? '1' : '2') . '00' . $i,
                'nama_lengkap'           => $nama,
                'gelar'                  => $faker->randomElement(['S.Pd.', 'M.Pd.', 'S.Si.', 'S.Kom.']),
                'jenis_kelamin'          => $gender,
                'tanggal_lahir'          => $tglLahir,
                'no_hp'                  => $faker->phoneNumber(),
                'tanggal_mulai_mengajar' => $faker->date('Y-m-d', '2020-01-01'),
                'tanggal_pensiun'        => Carbon::parse($tglLahir)->addYears(60)->format('Y-m-d'),
            ]);
        }

        // ==========================================
        // 5. DATA SISWA (7 ORANG) - NIS & NISN TERPISAH
        // ==========================================
        $daftarAgama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        for ($j = 1; $j <= 7; $j++) {
            $gender = $j % 2 == 0 ? 'P' : 'L';
            $nama   = $gender == 'L' ? $faker->name('male') : $faker->name('female');

            $userSiswa = User::create([
                'name'     => $nama,
                'email'    => "siswa{$j}@smp.sch.id",
                'password' => Hash::make('password'),
                'role'     => 'siswa',
            ]);

            Siswa::create([
                'user_id'       => $userSiswa->id,
                'nis'           => '2526' . str_pad($j, 3, '0', STR_PAD_LEFT), // Format NIS Lokal (Contoh: 2526001)
                'nisn'          => '008' . str_pad($j, 7, '0', STR_PAD_LEFT),  // Format NISN Nasional 10 Digit (Contoh: 0080000001)
                'nama_lengkap'  => $nama,
                'jenis_kelamin' => $gender,
                'tempat_lahir'  => $faker->city(),
                'tanggal_lahir' => $faker->dateTimeBetween('2011-01-01', '2013-12-31')->format('Y-m-d'),
                'agama'         => $faker->randomElement($daftarAgama),
                'alamat'        => $faker->address(),
            ]);
        }
    }
}
