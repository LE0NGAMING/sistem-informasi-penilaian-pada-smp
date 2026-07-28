<?php

namespace Database\Seeders;

use App\Enums\JenisKelaminEnum;
use App\Enums\RoleEnum;
use App\Enums\SemesterEnum;
use App\Enums\StatusSiswaEnum;
use App\Models\Guru;
use App\Models\KKM;
use App\Models\Mapel;
use App\Models\OrangTua;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------------------------------------------
        // 1. Master Tahun Ajaran & Semester
        // ------------------------------------------------------------------
        $tahunAjaran = TahunAjaran::create([
            'tahun' => '2025/2026',
            'is_active' => true,
        ]);

        $semester = Semester::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'semester' => SemesterEnum::GANJIL,
            'is_active' => true,
            'tanggal_pembagian_rapor' => '2025-12-20',
        ]);

        // ------------------------------------------------------------------
        // 2. Master Mata Pelajaran & KKM
        // ------------------------------------------------------------------
        $mapelList = [
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama dan Budi Pekerti', 'kelompok' => 'A', 'urutan' => 1],
            ['kode' => 'PPKN', 'nama' => 'Pancasila dan Kewarganegaraan', 'kelompok' => 'A', 'urutan' => 2],
            ['kode' => 'BINDO', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'A', 'urutan' => 3],
            ['kode' => 'MTK', 'nama' => 'Matematika', 'kelompok' => 'A', 'urutan' => 4],
            ['kode' => 'IPA', 'nama' => 'Ilmu Pengetahuan Alam', 'kelompok' => 'A', 'urutan' => 5],
            ['kode' => 'IPS', 'nama' => 'Ilmu Pengetahuan Sosial', 'kelompok' => 'A', 'urutan' => 6],
            ['kode' => 'BING', 'nama' => 'Bahasa Inggris', 'kelompok' => 'A', 'urutan' => 7],
            ['kode' => 'SBK', 'nama' => 'Seni Budaya', 'kelompok' => 'B', 'urutan' => 8],
            ['kode' => 'PJOK', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelompok' => 'B', 'urutan' => 9],
            ['kode' => 'MULOK', 'nama' => 'Bahasa Daerah (Sunda)', 'kelompok' => 'Mulok', 'urutan' => 10],
        ];

        foreach ($mapelList as $m) {
            $mapel = Mapel::create([
                'kode_mapel' => $m['kode'],
                'nama_mapel' => $m['nama'],
                'kelompok' => $m['kelompok'],
                'urutan' => $m['urutan'],
            ]);

            // Set KKM otomatis = 75.00 untuk tingkat 7, 8, dan 9
            foreach ([7, 8, 9] as $tingkat) {
                KKM::create([
                    'mapel_id' => $mapel->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'tingkat' => $tingkat,
                    'nilai_kkm' => 75.00,
                ]);
            }
        }

        // ------------------------------------------------------------------
        // 3. Akun Super Admin & Admin Sekolah
        // ------------------------------------------------------------------
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::SUPER_ADMIN,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Administrator Sekolah',
            'email' => 'admin@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::ADMIN_SEKOLAH,
            'is_active' => true,
        ]);

        // ------------------------------------------------------------------
        // 4. Akun Kepala Sekolah
        // ------------------------------------------------------------------
        User::create([
            'name' => 'Dr. H. Ahmad Dahlan, M.Pd.',
            'email' => 'kepsek@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::KEPALA_SEKOLAH,
            'is_active' => true,
        ]);

        // ------------------------------------------------------------------
        // 5. Akun Guru & Profile (Wali Kelas)
        // ------------------------------------------------------------------
        $userGuru = User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'email' => 'guru@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::GURU,
            'is_active' => true,
        ]);

        $guru = Guru::create([
            'user_id' => $userGuru->id,
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Budi Santoso',
            'gelar' => 'S.Pd.',
            'jenis_kelamin' => JenisKelaminEnum::LAKI_LAKI,
            'no_hp' => '081234567890',
        ]);

        // ------------------------------------------------------------------
        // 6. Rombongan Belajar (Rombel 7A)
        // ------------------------------------------------------------------
        $rombel7A = Rombel::create([
            'nama_rombel' => '7A',
            'tingkat' => 7,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'wali_kelas_id' => $guru->id,
        ]);

        // ------------------------------------------------------------------
        // 7. Akun Orang Tua & Profile
        // ------------------------------------------------------------------
        $userOrtu = User::create([
            'name' => 'Ahmad Wijaya (Orang Tua)',
            'email' => 'ortu@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::ORANG_TUA,
            'is_active' => true,
        ]);

        $orangTua = OrangTua::create([
            'user_id' => $userOrtu->id,
            'nama_ayah' => 'Ahmad Wijaya',
            'pekerjaan_ayah' => 'Wiraswasta',
            'nama_ibu' => 'Siti Rahma',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'no_hp' => '081987654321',
            'alamat' => 'Jl. Merdeka No. 45, Bandung',
        ]);

        // ------------------------------------------------------------------
        // 8. Akun Siswa 1 & Profile
        // ------------------------------------------------------------------
        $userSiswa1 = User::create([
            'name' => 'Rizky Pratama',
            'email' => 'siswa@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::SISWA,
            'is_active' => true,
        ]);

        Siswa::create([
            'user_id' => $userSiswa1->id,
            'nis' => '252607001',
            'nisn' => '0089123456',
            'nama_lengkap' => 'Rizky Pratama',
            'jenis_kelamin' => JenisKelaminEnum::LAKI_LAKI,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2012-05-15',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 45, Bandung',
            'rombel_id' => $rombel7A->id,
            'orang_tua_id' => $orangTua->id,
            'status' => StatusSiswaEnum::AKTIF,
        ]);

        // ------------------------------------------------------------------
        // 9. Akun Siswa 2 (Untuk simulasi Peringkat/Ranking Kelas)
        // ------------------------------------------------------------------
        $userSiswa2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siswa2@smpn1.sch.id',
            'password' => Hash::make('password123'),
            'role' => RoleEnum::SISWA,
            'is_active' => true,
        ]);

        Siswa::create([
            'user_id' => $userSiswa2->id,
            'nis' => '252607002',
            'nisn' => '0089123457',
            'nama_lengkap' => 'Siti Nurhaliza',
            'jenis_kelamin' => JenisKelaminEnum::PEREMPUAN,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2012-08-20',
            'agama' => 'Islam',
            'alamat' => 'Jl. Asia Afrika No. 12, Bandung',
            'rombel_id' => $rombel7A->id,
            'orang_tua_id' => $orangTua->id,
            'status' => StatusSiswaEnum::AKTIF,
        ]);
    }
}
