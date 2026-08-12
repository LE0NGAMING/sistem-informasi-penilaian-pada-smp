<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\PengampuController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\PenilaianController as GuruPenilaianController;
use App\Http\Controllers\Guru\PresensiController as GuruPresensiController;
use App\Http\Controllers\Guru\RekapPenilaianController;
use App\Http\Controllers\Guru\RaporController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Redirect Root ke Halaman Login
Route::get('/', fn() => to_route('login'));

// Route Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
});

// Route Terproteksi (Authenticated Users)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // =========================================================================
    // 1. SUPER ADMIN ROUTES
    // =========================================================================
    Route::middleware('role:' . RoleEnum::SUPER_ADMIN->value)
        ->prefix('super-admin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        });

    // =========================================================================
    // 2. ADMIN SEKOLAH ROUTES (FULL CUD & MANAGEMENT)
    // =========================================================================
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Master Data Management
            Route::resource('kelas', KelasController::class);
            Route::resource('siswa', SiswaController::class);
            Route::resource('guru', GuruController::class);
            Route::resource('mapel', MapelController::class);

            // Master Data Rombel + Fitur Plotting Siswa
            Route::resource('rombel', RombelController::class);
            Route::post('rombel/{rombel}/plot-siswa', [RombelController::class, 'plotSiswa'])->name('rombel.plot-siswa');
            Route::delete('rombel/{rombel}/unplot-siswa/{siswa}', [RombelController::class, 'unplotSiswa'])->name('rombel.unplot-siswa');

            // Presensi Admin
            Route::controller(AdminPresensiController::class)->prefix('presensi')->name('presensi.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/rekap', 'rekap')->name('rekap');
            });

            // Nilai Admin (Full Access)
            Route::controller(GuruPenilaianController::class)->prefix('nilai')->name('nilai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });

            // Pengampu Mengajar (Manajemen Plotting Guru -> Mapel -> Rombel)
            Route::resource('pengampu', PengampuController::class);

            // Log Aktivitas Sistem
            Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        });

    // =========================================================================
    // 3. GURU ROUTES (DENGAN STRICT DATA SCOPING)
    // =========================================================================
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');

            // // Presensi Guru
            // Route::controller(GuruPresensiController::class)->prefix('presensi')->name('presensi.')->group(function () {
            //     Route::get('/', 'index')->name('index');
            //     Route::post('/', 'store')->name('store');
            // });

            // Penilaian Guru (Terbatas Berdasarkan Tabel Pengampu)
            Route::controller(GuruPenilaianController::class)->prefix('nilai')->name('nilai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });

            // Rekap Penilaian
            Route::controller(RekapPenilaianController::class)->prefix('rekap')->name('rekap.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/cetak', 'cetak')->name('cetak');
            });

            // Rapor Guru
            Route::controller(RaporController::class)->prefix('rapor')->name('rapor.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/cetak-siswa/{siswa}', 'cetakSiswa')->name('siswa');
                Route::get('/cetak-rombel/{rombel}', 'cetakRombel')->name('rombel');
            });
        });

    // =========================================================================
    // 4. SIMPLE ROLE DASHBOARDS (STATIC VIEWS)
    // =========================================================================
    Route::middleware('role:' . RoleEnum::KEPALA_SEKOLAH->value)
        ->get('/kepala-sekolah/dashboard', fn() => view('kepalasekolah.dashboard'))
        ->name('kepalasekolah.dashboard');

    Route::middleware('role:' . RoleEnum::KURIKULUM->value)
        ->get('/kurikulum/dashboard', fn() => view('dashboard.kurikulum'))
        ->name('kurikulum.dashboard');

    Route::middleware('role:' . RoleEnum::SISWA->value)
        ->get('/siswa/dashboard', fn() => view('dashboard.siswa'))
        ->name('siswa.dashboard');

    Route::middleware('role:' . RoleEnum::ORANG_TUA->value)
        ->get('/orang-tua/dashboard', fn() => view('dashboard.ortu'))
        ->name('ortu.dashboard');

    // =========================================================================
    // 5. PROFILE ROUTES (SHARED FOR ALL AUTH USERS)
    // =========================================================================
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
    });
});
