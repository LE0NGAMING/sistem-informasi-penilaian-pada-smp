<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Kelas;

class PresensiGuruController extends Controller
{
    public function index(Request $request)
    {
        // Parameter filter (Default: Tanggal hari ini)
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $kelasId = $request->get('kelas_id', 1); // Default ke kelas ID 1 jika belum ada parameter

        // Ambil daftar semua kelas untuk dropdown filter
        $daftarKelas = Kelas::all();

        // Ambil daftar siswa di kelas tersebut
        $siswas = Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap', 'asc')->get();

        // Ambil presensi yang sudah tersimpan sebelumnya (jika ada)
        $presensis = Presensi::where('kelas_id', $kelasId)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absensi.index', compact('siswas', 'presensis', 'daftarKelas', 'kelasId', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'tanggal'  => 'required|date',
            'presensi' => 'required|array',
        ]);

        // Simpan / Update data absensi siswa
        foreach ($request->presensi as $siswaId => $data) {
            Presensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'kelas_id' => $request->kelas_id,
                    'tanggal'  => $request->tanggal,
                ],
                [
                    'guru_id' => Auth::id(),
                    'status'  => $data['status'],
                    'catatan' => $data['catatan'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Presensi berhasil disimpan!');
    }
}
