<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Import Controller Master Data Admin
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\PresensiController;

// Import Controller Guru untuk Penilaian
use App\Http\Controllers\Guru\PenilaianController;
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

    // ==========================================
    // 1. SUPER ADMIN ROUTES
    // ==========================================
    Route::middleware('role:' . RoleEnum::SUPER_ADMIN->value)
        ->prefix('super-admin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        });

    // ==========================================
    // 2. READ-ONLY MASTER DATA (ADMIN & GURU)
    // ==========================================
    // Guru & Admin Sekolah dapat melihat daftar dan detail data
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value . ',' . RoleEnum::GURU->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // View Data Siswa
            Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
            Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');

            // View Data Guru
            Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
            Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');

            // View Data Mapel
            Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index');
            Route::get('/mapel/{mapel}', [MapelController::class, 'show'])->name('mapel.show');
        });

    // ==========================================
    // 3. ADMIN SEKOLAH ROUTES (FULL CUD ACCESS)
    // ==========================================
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Master Data CUD (Create, Update, Delete)
            Route::resource('kelas', KelasController::class);
            Route::resource('rombel', RombelController::class);

            // Exclude 'index' & 'show' karena sudah didefinisikan di grup gabungan di atas
            Route::resource('siswa', SiswaController::class)->except(['index', 'show']);
            Route::resource('guru', GuruController::class)->except(['index', 'show']);
            Route::resource('mapel', MapelController::class)->except(['index', 'show']);

            // Presensi Routes Admin
            Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
            Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
            Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');

            // Nilai Routes Admin
            Route::get('/nilai', [PenilaianController::class, 'index'])->name('nilai.index');
            Route::post('/nilai', [PenilaianController::class, 'store'])->name('nilai.store');
        });

    // ==========================================
    // 4. KEPALA SEKOLAH ROUTES
    // ==========================================
    Route::middleware('role:' . RoleEnum::KEPALA_SEKOLAH->value)->group(function () {
        Route::get('/kepala-sekolah/dashboard', fn() => view('kepalasekolah.dashboard'))->name('kepalasekolah.dashboard');
    });

    // ==========================================
    // 5. KURIKULUM ROUTES
    // ==========================================
    Route::middleware('role:' . RoleEnum::KURIKULUM->value)->group(function () {
        Route::get('/kurikulum/dashboard', fn() => view('dashboard.kurikulum'))->name('kurikulum.dashboard');
    });

    // ==========================================
    // 6. GURU ROUTES (DASHBOARD, PRESENSI & NILAI)
    // ==========================================
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');

            // Menu Presensi Siswa oleh Guru
            Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
            Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

            // Menu Input Nilai oleh Guru
            Route::get('/nilai', [PenilaianController::class, 'index'])->name('nilai.index');
            Route::post('/nilai', [PenilaianController::class, 'store'])->name('nilai.store');
        });

    // ==========================================
    // 7. SISWA ROUTES
    // ==========================================
    Route::middleware('role:' . RoleEnum::SISWA->value)->group(function () {
        Route::get('/siswa/dashboard', fn() => view('dashboard.siswa'))->name('siswa.dashboard');
    });

    // ==========================================
    // 8. ORANG TUA ROUTES
    // ==========================================
    Route::middleware('role:' . RoleEnum::ORANG_TUA->value)->group(function () {
        Route::get('/orang-tua/dashboard', fn() => view('dashboard.ortu'))->name('ortu.dashboard');
    });

    // ==========================================
    // 9. PROFILE & PASSWORD ROUTES
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
