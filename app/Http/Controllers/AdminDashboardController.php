<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard statistik untuk Admin Sekolah.
     */
    public function index()
    {
        // 1. Statistik Utama
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalRombel = Rombel::count();
        $totalUser = User::count();

        // 2. Ringkasan Pengguna Berdasarkan Role
        $roleCounts = [
            'admin_sekolah' => User::where('role', RoleEnum::ADMIN_SEKOLAH->value)->count(),
            'guru'          => User::where('role', RoleEnum::GURU->value)->count(),
            'siswa'         => User::where('role', RoleEnum::SISWA->value)->count(),
            'orang_tua'     => User::where('role', RoleEnum::ORANG_TUA->value)->count(),
            'kepala_sekolah' => User::where('role', RoleEnum::KEPALA_SEKOLAH->value)->count(),
        ];

        // 3. 5 Akun Pengguna Terbaru
        $latestUsers = User::latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'totalSiswa',
            'totalGuru',
            'totalRombel',
            'totalUser',
            'roleCounts',
            'latestUsers'
        ));
    }
}
