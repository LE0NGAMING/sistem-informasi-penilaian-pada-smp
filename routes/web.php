<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman login
Route::get('/', fn() => redirect()->route('login'));

// Route Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
});

// Route Terproteksi Auth & Role-Based Access Control (RBAC)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Super Admin Routes
    Route::middleware('role:' . RoleEnum::SUPER_ADMIN->value)
        ->prefix('super-admin')
        ->name('superadmin.')
        ->group(function () {
            // Dashboard (Memanggil Controller agar data dinamis muncul)
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Point 2: Management User & Admin
            Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        });

    // Admin Sekolah Dashboard
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)->group(function () {
        Route::get('/admin/dashboard', fn() => view('dashboard.admin'))->name('admin.dashboard');
    });

    // Kepala Sekolah Dashboard
    Route::middleware('role:' . RoleEnum::KEPALA_SEKOLAH->value)->group(function () {
        Route::get('/kepala-sekolah/dashboard', fn() => view('dashboard.kepsek'))->name('kepsek.dashboard');
    });

    // Kurikulum Dashboard
    Route::middleware('role:' . RoleEnum::KURIKULUM->value)->group(function () {
        Route::get('/kurikulum/dashboard', fn() => view('dashboard.kurikulum'))->name('kurikulum.dashboard');
    });

    // Guru Dashboard
    Route::middleware('role:' . RoleEnum::GURU->value)->group(function () {
        Route::get('/guru/dashboard', fn() => view('dashboard.guru'))->name('guru.dashboard');
    });

    // Siswa Dashboard
    Route::middleware('role:' . RoleEnum::SISWA->value)->group(function () {
        Route::get('/siswa/dashboard', fn() => view('dashboard.siswa'))->name('siswa.dashboard');
    });

    // Orang Tua Dashboard
    Route::middleware('role:' . RoleEnum::ORANG_TUA->value)->group(function () {
        Route::get('/orang-tua/dashboard', fn() => view('dashboard.ortu'))->name('ortu.dashboard');
    });
});
