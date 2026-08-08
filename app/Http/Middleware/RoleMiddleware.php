<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memeriksa apakah pengguna memiliki hak akses yang sesuai.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Daftar role yang diizinkan (misal: 'admin_sekolah', 'super_admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Memastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Ambil nilai string dari RoleEnum atau biarkan jika sudah berbentuk string
        $userRoleValue = $user->role instanceof RoleEnum ? $user->role->value : $user->role;

        // 3. Bersihkan setiap elemen array $roles dari potensi spasi kosong yang tidak disengaja
        // Contoh: "role:admin, guru" akan diubah dari ['admin', ' guru'] menjadi ['admin', 'guru']
        $cleanRoles = array_map('trim', $roles);

        // 4. Periksa kecocokan role menggunakan strict comparison (true)
        if (!in_array($userRoleValue, $cleanRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
