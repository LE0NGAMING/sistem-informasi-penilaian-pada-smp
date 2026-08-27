<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Penilaian;

class PresensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $mapelList = Mapel::orderBy('nama_mapel', 'asc')->get();

        $kelasId  = $request->get('kelas_id');
        $mapelId  = $request->get('mapel_id');
        $semester = $request->get('semester', 'Ganjil');

        $siswaList = collect();

        if ($kelasId && $mapelId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->with(['nilai' => function ($q) use ($mapelId, $semester) {
                    $q->where('mapel_id', $mapelId)
                        ->where('semester', $semester);
                }])
                ->orderBy('nama_lengkap', 'asc')
                ->get();
        }

        return view('guru.nilai.index', compact(
            'kelasList',
            'mapelList',
            'siswaList',
            'kelasId',
            'mapelId',
            'semester'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'semester' => 'required',
            'nilai'    => 'required|array',
        ]);

        foreach ($request->nilai as $siswaId => $scores) {
            $tugas = floatval($scores['tugas'] ?? 0);
            $uts   = floatval($scores['uts'] ?? 0);
            $uas   = floatval($scores['uas'] ?? 0);

            // Perhitungan Bobot: Tugas 30%, UTS 35%, UAS 35%
            $nilaiAkhir = ($tugas * 0.30) + ($uts * 0.35) + ($uas * 0.35);

            Penilaian::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $request->mapel_id,
                    'semester' => $request->semester,
                ],
                [
                    'tugas'       => $tugas,
                    'uts'         => $uts,
                    'uas'         => $uas,
                    'nilai_akhir' => round($nilaiAkhir, 2),
                ]
            );
        }

        return redirect()->route('guru.nilai.index', [
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'semester' => $request->semester,
        ])->with('success', 'Data penilaian berhasil disimpan!');
    }
}
