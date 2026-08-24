<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari data guru berdasarkan user_id dan load relasi mapel
        $guru = Guru::with('mapel')->where('user_id', $user->id)->first();

        // Jika tidak ditemukan, coba cari berdasarkan ID yang sama dengan user
        if (!$guru) {
            $guru = Guru::with('mapel')->find($user->id);
        }

        // Cek apakah guru ini menjadi wali kelas
        $rombelWali = null;
        if ($guru) {
            $rombelWali = Rombel::where('wali_kelas_id', $guru->id)->first();
        }

        $totalKelas = 0;
        $totalSiswaDiampu = 0;
        $kelasSelesai = 0;
        $kelasList = [];

        return view('guru.dashboard', compact(
            'guru',
            'rombelWali',
            'totalKelas',
            'totalSiswaDiampu',
            'kelasSelesai',
            'kelasList'
        ));
    }
}
