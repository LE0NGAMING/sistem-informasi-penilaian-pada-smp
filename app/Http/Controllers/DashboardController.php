<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request and route to specific dashboard view.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $data = match ($user->role) {
            'super_admin', 'admin_sekolah' => $this->getAdminStats(),
            'kepala_sekolah' => $this->getKepsekStats(),
            'guru_mapel', 'wali_kelas' => $this->getGuruStats($user),
            'siswa' => $this->getSiswaStats($user),
            'orang_tua' => $this->getOrangTuaStats($user),
            default => [],
        };

        return view("dashboard.{$user->role}", compact('data', 'user'));
    }

    private function getAdminStats(): array
    {
        return [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::count(),
            'total_rombel' => Rombel::count(),
            'total_users' => User::count(),
        ];
    }

    private function getKepsekStats(): array
    {
        // Kepsek melihat agregat makro untuk laporan
        return [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::count(),
            'siswa_berprestasi' => Siswa::has('prestasi')->count(),
            // Logika chart / grafik bisa diload via AJAX/API secara terpisah untuk performa
        ];
    }

    private function getGuruStats(User $user): array
    {
        $guru = $user->guru;
        return [
            'kelas_diampu' => $guru ? $guru->rombelWali()->count() : 0,
            'total_penilaian_dibuat' => $guru ? $guru->penilaianInputed()->count() : 0,
        ];
    }

    private function getSiswaStats(User $user): array
    {
        $siswa = $user->siswa;
        return [
            'total_kehadiran' => $siswa ? $siswa->absensi()->where('status', 'Hadir')->count() : 0,
            'nilai_rata_rata_terakhir' => $siswa ? $siswa->rapor()->latest()->value('rata_rata') : 0,
        ];
    }

    private function getOrangTuaStats(User $user): array
    {
        $anak = $user->orangTua?->siswa; // Relasi HasMany anak
        return [
            'anak' => $anak, // Bisa menampilkan multiple anak jika ortu memiliki > 1 anak di sekolah yg sama
        ];
    }
}
