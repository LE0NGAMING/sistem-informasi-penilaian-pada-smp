<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kelas untuk dropdown
        $kelases = Kelas::all();

        $selectedKelas = $request->get('kelas_id');
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        $siswas = collect();
        $presensis = collect();

        if ($selectedKelas) {
            // 2. Ambil data siswa berdasarkan kelas yang dipilih
            $siswas = Siswa::where('kelas_id', $selectedKelas)->get();

            // 3. Ambil data presensi yang sudah ada pada tanggal tersebut
            $presensis = Presensi::where('kelas_id', $selectedKelas)
                ->where('tanggal', $selectedTanggal)
                ->get()
                ->keyBy('siswa_id');
        }

        // 4. Kirim variabel dengan nama yang sesuai dengan Blade
        return view('admin.presensi.index', compact(
            'kelases',
            'siswas',
            'presensis',
            'selectedKelas',
            'selectedTanggal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal'  => 'required|date',
            'presensi' => 'required|array',
        ]);

        foreach ($request->presensi as $siswaId => $data) {
            $existing = Presensi::where('siswa_id', $siswaId)
                ->where('tanggal', $request->tanggal)
                ->first();

            Presensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal'  => $request->tanggal,
                ],
                [
                    'kelas_id'   => $request->kelas_id,
                    'status'     => $data['status'],
                    'jam_masuk'  => $data['jam_masuk'] ?? ($existing->jam_masuk ?? null),
                    'jam_pulang' => $data['jam_pulang'] ?? ($existing->jam_pulang ?? null),
                    // Jika diubah dari web, tandai metodenya sebagai manual/override
                    'metode'     => $existing ? ($existing->status !== $data['status'] ? 'manual' : $existing->metode) : 'manual',
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui!');
    }

    public function rekap(Request $request)
    {
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        $rekaps = [];
        $selectedKelas = $request->get('kelas_id');

        if ($selectedKelas) {
            $siswas = Siswa::where('kelas_id', $selectedKelas)
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            foreach ($siswas as $siswa) {
                $totalHadir = Presensi::where('siswa_id', $siswa->id)->where('status', 'hadir')->count();
                $totalSakit = Presensi::where('siswa_id', $siswa->id)->where('status', 'sakit')->count();
                $totalIzin  = Presensi::where('siswa_id', $siswa->id)->where('status', 'izin')->count();
                $totalAlpa  = Presensi::where('siswa_id', $siswa->id)->where('status', 'alpa')->count();

                $totalHari = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
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

        return view('admin.presensi.rekap', compact('kelases', 'rekaps', 'selectedKelas'));
    }
}
