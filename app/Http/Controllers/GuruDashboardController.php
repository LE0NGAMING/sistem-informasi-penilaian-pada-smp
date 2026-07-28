<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    /**
     * Menampilkan dashboard statistik & ringkasan mengajar untuk Guru.
     */
    public function index()
    {
        $user = Auth::user();

        // Mengambil data guru terhubung dengan user login
        $guru = Guru::where('user_id', $user->id)->first();

        // Cek jika guru menjadi wali kelas di rombel tertentu
        $rombelWali = $guru ? Rombel::where('guru_id', $guru->id)->first() : null;

        // Data dummy / skema query untuk daftar pengampuan mengajar & status nilai
        // Di aplikasi nyata, ini bisa di-query dari tabel 'pembelajaran' / 'jadwal' / 'penilaian'
        $kelasList = collect([
            (object) [
                'id' => 1,
                'mapel' => 'Matematika',
                'rombel' => 'Kelas VII-A',
                'jumlah_siswa' => 32,
                'jumlah_terisi' => 32,
                'progress' => 100,
                'status' => 'Selesai',
            ],
            (object) [
                'id' => 2,
                'mapel' => 'Matematika',
                'rombel' => 'Kelas VII-B',
                'jumlah_siswa' => 30,
                'jumlah_terisi' => 20,
                'progress' => 66,
                'status' => 'Proses',
            ],
            (object) [
                'id' => 3,
                'mapel' => 'Matematika',
                'rombel' => 'Kelas VIII-A',
                'jumlah_siswa' => 31,
                'jumlah_terisi' => 0,
                'progress' => 0,
                'status' => 'Belum Diisi',
            ],
        ]);

        $totalKelas = $kelasList->count();
        $totalSiswaDiampu = $kelasList->sum('jumlah_siswa');
        $kelasSelesai = $kelasList->where('progress', 100)->count();

        return view('dashboard.guru', compact(
            'guru',
            'rombelWali',
            'kelasList',
            'totalKelas',
            'totalSiswaDiampu',
            'kelasSelesai'
        ));
    }
}
