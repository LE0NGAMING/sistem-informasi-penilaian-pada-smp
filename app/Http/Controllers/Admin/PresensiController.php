<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiHarian;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $rombels = Rombel::orderBy('nama_rombel', 'asc')->get();
        $selectedRombel = $request->get('rombel_id');
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        $siswas = collect();
        $presensis = collect();

        if ($selectedRombel) {
            // Ambil data siswa diurutkan abjad
            $siswas = Siswa::where('rombel_id', $selectedRombel)
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            // Optimasi: Ambil presensi sekaligus dalam satu query (mencegah N+1)
            $presensis = PresensiHarian::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('tanggal', $selectedTanggal)
                ->get()
                ->keyBy('siswa_id');
        }

        return view('admin.presensi.index', compact(
            'rombels',
            'siswas',
            'presensis',
            'selectedRombel',
            'selectedTanggal'
        ));
    }

    public function store(Request $request)
    {
        // Validasi ketat termasuk isi array di dalam presensi
        $request->validate([
            'rombel_id'             => 'required|exists:rombel,id',
            'tanggal'               => 'required|date',
            'presensi'              => 'required|array',
            'presensi.*.status'     => 'required|in:hadir,sakit,izin,alpa',
            'presensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Gunakan Transaction agar aman dari data korup/setengah-setengah
        DB::transaction(function () use ($request) {
            foreach ($request->presensi as $siswaId => $data) {
                $existing = PresensiHarian::where('siswa_id', $siswaId)
                    ->where('tanggal', $request->tanggal)
                    ->first();

                // Tentukan metode: jika status berubah manual dari web, ubah jadi 'manual'
                $metode = 'manual';
                if ($existing) {
                    $metode = ($existing->status !== $data['status']) ? 'manual' : $existing->metode;
                }

                PresensiHarian::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'tanggal'  => $request->tanggal,
                    ],
                    [
                        'status'     => $data['status'],
                        'jam_masuk'  => $existing->jam_masuk ?? null,
                        'jam_pulang' => $existing->jam_pulang ?? null,
                        'metode'     => $metode,
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui!');
    }

    public function rekap(Request $request)
    {
        $rombels = Rombel::orderBy('nama_rombel', 'asc')->get();
        $rekaps = [];
        $selectedRombel = $request->get('rombel_id');

        if ($selectedRombel) {
            $siswas = Siswa::where('rombel_id', $selectedRombel)
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $siswaIds = $siswas->pluck('id');

            // OPTIMASI UTAMA: Ambil seluruh data presensi siswa rombel ini dalam 1 query saja,
            // lalu kelompokkan berdasarkan siswa_id di memori PHP (Bebas dari N+1 Query).
            $semuaPresensi = PresensiHarian::whereIn('siswa_id', $siswaIds)
                ->get()
                ->groupBy('siswa_id');

            foreach ($siswas as $siswa) {
                $presensiSiswa = $semuaPresensi->get($siswa->id, collect());

                $totalHadir = $presensiSiswa->where('status', 'hadir')->count();
                $totalSakit = $presensiSiswa->where('status', 'sakit')->count();
                $totalIzin  = $presensiSiswa->where('status', 'izin')->count();
                $totalAlpa  = $presensiSiswa->where('status', 'alpa')->count();

                $totalHari  = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
                $persentase = $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 0;

                $rekaps[] = [
                    'nisn'         => $siswa->nisn ?? $siswa->nis ?? '-',
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'hadir'        => $totalHadir,
                    'sakit'        => $totalSakit,
                    'izin'         => $totalIzin,
                    'alpa'         => $totalAlpa,
                    'persentase'   => $persentase,
                ];
            }
        }

        return view('admin.presensi.rekap', compact('rombels', 'rekaps', 'selectedRombel'));
    }
}
