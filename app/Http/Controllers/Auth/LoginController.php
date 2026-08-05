<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true, // Memastikan akun aktif
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Kredensial tidak cocok atau akun Anda sedang tidak aktif.'),
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Redirect sesuai RoleEnum
        return redirect()->to($this->getRedirectUrlByRole($user->role));
    }

    /**
     * Mengeluarkan pengguna dari sistem.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
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
            RoleEnum::KEPALA_SEKOLAH => route('kepsek.dashboard'),
            RoleEnum::KURIKULUM => route('kurikulum.dashboard'),
            RoleEnum::GURU => route('guru.dashboard'),
            RoleEnum::SISWA => route('siswa.dashboard'),
            RoleEnum::ORANG_TUA => route('ortu.dashboard'),
            default => route('login'), // Fallback jika role tidak valid
        };
    }
}
