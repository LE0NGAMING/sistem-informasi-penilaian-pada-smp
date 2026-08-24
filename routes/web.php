<?php

use App\Enums\RoleEnum;

// Auth & Common Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

// Super Admin Controllers
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\AdminSekolahController;
use App\Http\Controllers\SuperAdmin\UserController;

// Admin Sekolah Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\PengampuController;
use App\Http\Controllers\Admin\PenilaianController as AdminPenilaianController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\RekapPenilaianController as AdminRekapPenilaianController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\SiswaController;

// Guru Controllers
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\PenilaianController as GuruPenilaianController;
use App\Http\Controllers\Guru\RekapPenilaianController as GuruRekapPenilaianController;

// Wali Kelas Controllers
use App\Http\Controllers\WaliKelas\EkskulController as WaliKelasEkskulController;
use App\Http\Controllers\WaliKelas\PresensiHarianController as WaliKelasPresensiController;
use App\Http\Controllers\WaliKelas\RaporController as WaliKelasRaporController;

use Illuminate\Support\Facades\Route;

// =========================================================================
// PUBLIC & GUEST ROUTES
// =========================================================================
Route::get('/', fn() => to_route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
});

// =========================================================================
// AUTHENTICATED ROUTES
// =========================================================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // -------------------------------------------------------------------------
    // 1. SUPER ADMIN ROUTES
    Route::middleware('role:' . RoleEnum::SUPER_ADMIN->value)
        ->prefix('super-admin')
        ->name('superadmin.')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

            // Manajemen khusus Admin Sekolah
            Route::resource('admin-sekolah', AdminSekolahController::class)->except(['create', 'edit', 'show']);
            Route::patch('admin-sekolah/{user}/toggle-status', [AdminSekolahController::class, 'toggleStatus'])->name('admin-sekolah.toggle-status');
            Route::post('admin-sekolah/{user}/reset-password', [AdminSekolahController::class, 'resetPassword'])->name('admin-sekolah.reset-password');

            // Fitur Infrastruktur & Sistem
            Route::get('/audit-logs', [SuperAdminLogController::class, 'index'])->name('logs.index');
            Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup/run', [BackupController::class, 'run'])->name('backup.run');
            Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        });

    // -------------------------------------------------------------------------
    // 2. ADMIN SEKOLAH ROUTES (FULL CUD & MANAGEMENT)
    // -------------------------------------------------------------------------
    Route::middleware('role:' . RoleEnum::ADMIN_SEKOLAH->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Master Data Management
            Route::resources([
                'kelas' => KelasController::class,
                'siswa' => SiswaController::class,
                'guru'  => GuruController::class,
                'mapel' => MapelController::class,
            ]);

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

            // Nilai Admin
            Route::controller(AdminPenilaianController::class)->prefix('nilai')->name('nilai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });

            // Rekap Penilaian Admin
            Route::controller(AdminRekapPenilaianController::class)->prefix('rekap')->name('rekap.')->group(function () {
                Route::get('/', 'index')->name('index');
            });

            // Pengampu Mengajar (Manajemen Plotting Guru -> Mapel -> Rombel)
            Route::resource('pengampu', PengampuController::class);

            // Log Aktivitas Sistem
            Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        });

    // -------------------------------------------------------------------------
    // 3. GURU MAPEL ROUTES
    // -------------------------------------------------------------------------
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

            // Penilaian Guru Mapel (Terbatas Berdasarkan Tabel Pengampu)
            Route::controller(GuruPenilaianController::class)->prefix('nilai')->name('nilai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });

            // Rekap Penilaian Guru
            Route::controller(GuruRekapPenilaianController::class)->prefix('rekap')->name('rekap.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/cetak', 'cetak')->name('cetak');
            });
        });

    // -------------------------------------------------------------------------
    // 4. WALI KELAS ROUTES
    // -------------------------------------------------------------------------
    Route::middleware('role:' . RoleEnum::GURU->value)
        ->prefix('walikelas')
        ->name('walikelas.')
        ->group(function () {

            // Presensi Harian Rombel
            Route::controller(WaliKelasPresensiController::class)->prefix('presensi')->name('presensi.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/export-excel', 'exportExcel')->name('exportExcel');
                Route::get('/export-pdf', 'exportPdf')->name('exportPdf');
            });

            // Input Nilai & Deskripsi Ekstrakurikuler (Khusus Wali Kelas)
            Route::controller(WaliKelasEkskulController::class)->prefix('ekskul')->name('ekskul.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
            });

            // Rapor & Cetak Rapor (Khusus Wali Kelas)
            Route::controller(WaliKelasRaporController::class)->prefix('rapor')->name('rapor.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/cetak-siswa/{siswa}', 'cetakSiswa')->name('siswa');
                Route::get('/cetak-rombel/{rombel}', 'cetakRombel')->name('rombel');
            });
        });

    // -------------------------------------------------------------------------
    // 5. STATIC DASHBOARDS (ROLE SPECIFIC)
    // -------------------------------------------------------------------------
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

    // -------------------------------------------------------------------------
    // 6. PROFILE ROUTES (SHARED)
    // -------------------------------------------------------------------------
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
    });
});
