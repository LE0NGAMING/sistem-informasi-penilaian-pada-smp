<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Pengampu;
use App\Models\Penilaian;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Model Guru (beserta relasi Mapel Utama) berdasarkan User login
        $guru = Guru::with('mapel')->where('user_id', $user->id)->first() ?? $user;

        // 2. Ambil Tahun Ajaran Aktif
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $tahunAjaranId = $tahunAjaranAktif?->id;

        // 3. Ambil data Pengampu guru
        $pengampuList = Pengampu::with(['rombel.siswa', 'mapel'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        // --- CARD 1: KELAS MENGAJAR ---
        $totalKelasMengajar = $pengampuList->pluck('rombel_id')->unique()->filter()->count();

        // --- CARD 2: TOTAL SISWA DIAMPU ---
        $rombelIds = $pengampuList->pluck('rombel_id')->unique()->filter();
        $totalSiswaDiampu = Siswa::whereIn('rombel_id', $rombelIds)->count();

        // --- CARD 4: TUGAS WALI KELAS ---
        $rombelWali = Rombel::where('wali_kelas_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();

        // --- CARD 3 & TABEL: STATUS INPUT NILAI PER KELAS ---
        $totalTargetInput = 0;
        $totalSelesaiInput = 0;
        $statusPerKelas = [];

        foreach ($pengampuList as $pengampu) {
            // Hitung total siswa aktif di rombel ini
            $jumlahSiswaInRombel = Siswa::where('rombel_id', $pengampu->rombel_id)->count();

            // Hitung siswa yang nilainya sudah terisi di tabel penilaians
            $siswaSudahDinilai = Penilaian::where('mapel_id', $pengampu->mapel_id)
                ->where('rombel_id', $pengampu->rombel_id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where(function ($query) {
                    $query->whereNotNull('nilai_harian')
                        ->orWhereNotNull('nilai_pengetahuan')
                        ->orWhereNotNull('nilai_keterampilan')
                        ->orWhereNotNull('nilai_akhir');
                })
                ->pluck('siswa_id')
                ->unique()
                ->count();

            // Hitung Persentase Progress
            $progress = $jumlahSiswaInRombel > 0
                ? round(($siswaSudahDinilai / $jumlahSiswaInRombel) * 100)
                : 0;

            $totalTargetInput += $jumlahSiswaInRombel;
            $totalSelesaiInput += $siswaSudahDinilai;

            $statusPerKelas[] = [
                'mapel'       => $pengampu->mapel?->nama_mapel ?? '-',
                'rombel'      => $pengampu->rombel?->nama_rombel ?? '-',
                'input_siswa' => "{$siswaSudahDinilai} / {$jumlahSiswaInRombel}",
                'progress'    => $progress,
            ];
        }

        return view('guru.dashboard', compact(
            'guru',
            'totalKelasMengajar',
            'totalSiswaDiampu',
            'totalSelesaiInput',
            'totalTargetInput',
            'rombelWali',
            'statusPerKelas'
        ));
    }
}
