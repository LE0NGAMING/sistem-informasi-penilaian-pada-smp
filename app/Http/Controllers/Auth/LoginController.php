<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/** @var \App\Models\User|null $user */

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses otentikasi login pengguna.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // ==========================================
            // CATAT LOG AKTIVITAS LOGIN DI SINI
            // ==========================================
            activity()
                ->causedBy($user)
                ->log('Melakukan login ke dalam sistem.');

            // Redirect ke dashboard spesifik sesuai role user
            // Menggunakan intended agar bisa kembali ke halaman sebelumnya (jika ada),
            // atau fallback ke dashboard sesuai role jika langsung login.
            return redirect()->intended($this->getRedirectUrlByRole($user->role));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Mengeluarkan pengguna dari sistem.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Ambil data user sebelum sesi dihancurkan
        $user = Auth::user();

        // ==========================================
        // CATAT LOG AKTIVITAS LOGOUT DI SINI
        // ==========================================
        if ($user) {
            activity()
                ->causedBy($user)
                ->log('Melakukan logout dari sistem.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Helper menentukan URL Dashboard berdasarkan RoleEnum pengguna.
     */
    private function getRedirectUrlByRole(RoleEnum|string $role): string
    {
        // Konversi tipe string (misal: 'super_admin') menjadi instance RoleEnum
        $roleEnum = is_string($role) ? RoleEnum::tryFrom($role) : $role;

        return match ($roleEnum) {
            RoleEnum::SUPER_ADMIN => route('superadmin.dashboard'),
            RoleEnum::ADMIN_SEKOLAH => route('admin.dashboard'),
            RoleEnum::KEPALA_SEKOLAH => route('kepalasekolah.dashboard'), // Diperbaiki: sesuaikan dengan web.php
            RoleEnum::KURIKULUM => route('kurikulum.dashboard'),
            RoleEnum::GURU => route('guru.dashboard'),
            RoleEnum::SISWA => route('siswa.dashboard'),
            RoleEnum::ORANG_TUA => route('ortu.dashboard'),
            default => route('login'), // Fallback jika role tidak valid
        };
    }
}
