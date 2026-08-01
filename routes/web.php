<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PresensiGuruController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Import Controller Master Data Admin
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\RombelController;

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

    // Route Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Super Admin Routes
    Route::middleware('role:' . RoleEnum::SUPER_ADMIN->value)
        ->prefix('super-admin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        });

    // Admin Sekolah Routes (Dashboard & Master Data)
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Arahkan dashboard ke DashboardController
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // --- Master Data Routes ---
            Route::resource('kelas', KelasController::class);
            Route::resource('mapel', MapelController::class);
            Route::resource('siswa', SiswaController::class);
            Route::resource('rombel', RombelController::class);
        });

    // Kepala Sekolah Dashboard
    Route::middleware('role:' . RoleEnum::KEPALA_SEKOLAH->value)->group(function () {
        Route::get('/kepala-sekolah/dashboard', fn() => view('kepalasekolah.dashboard'))->name('kepalasekolah.dashboard');
    });

    // Kurikulum Dashboard
    Route::middleware('role:' . RoleEnum::KURIKULUM->value)->group(function () {
        Route::get('/kurikulum/dashboard', fn() => view('dashboard.kurikulum'))->name('kurikulum.dashboard');
    });

    // Guru Routes (Dashboard & Absensi)
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');

            // Route Absensi Guru
            Route::get('/absensi', [PresensiGuruController::class, 'index'])->name('absensi.index');
            Route::post('/absensi', [PresensiGuruController::class, 'store'])->name('absensi.store');
        });

    // Siswa Dashboard
    Route::middleware('role:' . RoleEnum::SISWA->value)->group(function () {
        Route::get('/siswa/dashboard', fn() => view('dashboard.siswa'))->name('siswa.dashboard');
    });

    // Orang Tua Dashboard
    Route::middleware('role:' . RoleEnum::ORANG_TUA->value)->group(function () {
        Route::get('/orang-tua/dashboard', fn() => view('dashboard.ortu'))->name('ortu.dashboard');
    });

    // Route Pengaturan Profil & Password
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
