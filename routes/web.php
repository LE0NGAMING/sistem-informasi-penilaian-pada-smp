<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Guru\RekapPenilaianController;
use App\Http\Controllers\Guru\RaporController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

// Redirect Root
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
    // 2. READ-ONLY MASTER DATA (SHARED: ADMIN SEKOLAH & GURU)
    // =========================================================================
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value . ',' . RoleEnum::GURU->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
            Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
            Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');

            Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
            Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
            Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');

            Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index');
            Route::get('/mapel/create', [MapelController::class, 'create'])->name('mapel.create');
            Route::get('/mapel/{mapel}', [MapelController::class, 'show'])->name('mapel.show');
        });

    // =========================================================================
    // 3. ADMIN SEKOLAH ROUTES (FULL CUD & MANAGEMENT)
    // =========================================================================
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Master Data Management
            Route::resource('kelas', KelasController::class);

            // Master Data Rombel + Fitur Plotting Siswa
            Route::resource('rombel', RombelController::class);
            Route::post('rombel/{rombel}/plot-siswa', [RombelController::class, 'plotSiswa'])->name('rombel.plot-siswa');
            Route::delete('rombel/{rombel}/unplot-siswa/{siswa}', [RombelController::class, 'unplotSiswa'])->name('rombel.unplot-siswa');

            // Resource CUD yang dikomplementasi dari read-only
            Route::resource('siswa', SiswaController::class)->except(['index', 'show']);
            Route::resource('guru', GuruController::class)->except(['index', 'show']);
            Route::resource('mapel', MapelController::class)->except(['index', 'show']);

            // Presensi Admin
            Route::controller(PresensiController::class)->prefix('presensi')->name('presensi.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/rekap', 'rekap')->name('rekap');
            });

            // Nilai Admin
            Route::controller(PenilaianController::class)->prefix('nilai')->name('nilai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });
        });

    // =========================================================================
    // 4. GURU ROUTES
    // =========================================================================
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');

            // Presensi Guru
            Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
            Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

            // Penilaian Guru
            Route::get('/nilai', [PenilaianController::class, 'index'])->name('nilai.index');
            Route::post('/nilai', [PenilaianController::class, 'store'])->name('nilai.store');

            // Rekap Penilaian
            //Route::prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/rekap', [RekapPenilaianController::class, 'index'])->name('rekap.index');
            Route::get('/rekap/cetak', [RekapPenilaianController::class, 'cetak'])->name('rekap.cetak');
            // });

            //Route::controller(RaporController::class)->prefix('rapor')->name('rapor.')->group(function () {
            Route::get('/rapor/siswa/{siswa}', [RaporController::class, 'cetakSiswa'])->name('rapor.siswa');
            Route::get('/rapor/rombel/{rombel}', [RaporController::class, 'cetakRombel'])->name('rapor.rombel');
            // });
        });

    // =========================================================================
    // 5. SIMPLE ROLE DASHBOARDS (STATIC VIEWS)
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
    // 6. PROFILE ROUTES
    // =========================================================================
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
    });
});
