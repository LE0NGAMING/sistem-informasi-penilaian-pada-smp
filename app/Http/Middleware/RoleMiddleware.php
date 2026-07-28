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
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil nilai string dari RoleEnum
        $userRoleValue = $user->role instanceof RoleEnum ? $user->role->value : $user->role;

        // 2. Periksa apakah role pengguna ada di daftar role yang diizinkan
        if (! in_array($userRoleValue, $roles)) {
            // Tampilkan error 403 Forbidden jika tidak punya hak akses
            abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
