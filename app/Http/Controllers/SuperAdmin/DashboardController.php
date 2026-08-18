<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Admin Sekolah
        $totalAdmin = User::where('role', RoleEnum::ADMIN_SEKOLAH->value)->count();

        // Total Pengguna Terdaftar (Murni Guru, Kepala Sekolah, Kurikulum, Siswa, & Ortu)
        $totalUser = User::whereNotIn('role', [
            RoleEnum::SUPER_ADMIN->value,
            RoleEnum::ADMIN_SEKOLAH->value,
        ])->count();

        // 5 Pengguna terbaru
        $latestUsers = User::latest()->take(5)->get();

        return view('superadmin.super_admin', compact('totalAdmin', 'totalUser', 'latestUsers'));
    }
}
