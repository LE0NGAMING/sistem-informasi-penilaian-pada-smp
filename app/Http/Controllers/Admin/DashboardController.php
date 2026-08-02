<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\User;
use App\Enums\RoleEnum;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil hitungan data dari database
        $totalSiswa = Siswa::count();
        $totalGuru = User::where('role', \App\Enums\RoleEnum::GURU->value)->count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $totalRombel = Rombel::count();
        $totalUser = User::count();
        $latestUsers = User::latest()->take(5)->get();
        $roleCounts = [
            'admin_sekolah'  => User::where('role', RoleEnum::ADMIN_SEKOLAH->value ?? 'admin_sekolah')->count(),
            'guru'           => User::where('role', RoleEnum::GURU->value ?? 'guru')->count(),
            'siswa'          => User::where('role', RoleEnum::SISWA->value ?? 'siswa')->count(),
            'orang_tua'      => User::where('role', RoleEnum::ORANG_TUA->value ?? 'orang_tua')->count(),
            'kepala_sekolah' => User::where('role', RoleEnum::KEPALA_SEKOLAH->value ?? 'kepala_sekolah')->count(),
            'kurikulum'      => User::where('role', RoleEnum::KURIKULUM->value ?? 'kurikulum')->count(),
        ];

        return view('dashboard.admin_sekolah', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalMapel',
            'totalRombel',
            'totalUser',
            'latestUsers',
            'roleCounts'
        ));
    }
}
