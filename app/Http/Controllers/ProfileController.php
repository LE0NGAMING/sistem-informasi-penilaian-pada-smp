<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Memperbarui Nama & Email
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success_profile', 'Profil berhasil diperbarui!');
    }

    /**
     * Memperbarui Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Password berhasil diperbarui!');
    }

    /**
     * Logout Pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
