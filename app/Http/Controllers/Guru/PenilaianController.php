<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Rombel;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\Siswa;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $rombelList   = Rombel::orderBy('nama_rombel', 'asc')->get();
        $mapelList    = Mapel::orderBy('nama_mapel', 'asc')->get();
        $semesterList = Semester::orderBy('id', 'desc')->get();

        $rombelId   = $request->get('rombel_id');
        $mapelId    = $request->get('mapel_id');
        $semesterId = $request->get('semester_id');

        $siswaList = collect();

        if ($rombelId && $mapelId && $semesterId) {
            $siswaList = Siswa::where('rombel_id', $rombelId)
                ->with(['penilaian' => function ($q) use ($mapelId, $semesterId) {
                    $q->where('mapel_id', $mapelId)
                        ->where('semester_id', $semesterId);
                }])
                ->orderBy('nama_lengkap', 'asc')
                ->get();
        }

        return view('guru.nilai.index', compact(
            'rombelList',
            'mapelList',
            'semesterList',
            'siswaList',
            'rombelId',
            'mapelId',
            'semesterId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id'   => 'required',
            'mapel_id'    => 'required',
            'semester_id' => 'required',
            'nilai'       => 'required|array',
        ]);

        foreach ($request->nilai as $siswaId => $scores) {
            $harian  = floatval($scores['nilai_harian'] ?? 0);
            $tugas   = floatval($scores['tugas'] ?? 0);
            $quiz    = floatval($scores['quiz'] ?? 0);
            $uts     = floatval($scores['uts'] ?? 0);
            $uas     = floatval($scores['uas'] ?? 0);
            $praktik = floatval($scores['praktik'] ?? 0);
            $catatan = $scores['catatan'] ?? null;

            // Perhitungan Nilai Akhir (Bobot: Harian 15%, Tugas 15%, Quiz 10%, UTS 20%, UAS 20%, Praktik 20%)
            $nilaiAkhir = ($harian * 0.15) + ($tugas * 0.15) + ($quiz * 0.10) +
                ($uts * 0.20) + ($uas * 0.20) + ($praktik * 0.20);
            $nilaiAkhir = round($nilaiAkhir, 2);

            // Penentuan Predikat (A, B, C, D)
            if ($nilaiAkhir >= 90) {
                $predikat = 'A';
            } elseif ($nilaiAkhir >= 80) {
                $predikat = 'B';
            } elseif ($nilaiAkhir >= 75) {
                $predikat = 'C';
            } else {
                $predikat = 'D';
            }

            // Penentuan Status Remedial (Standar KKM = 75)
            $isRemedial = $nilaiAkhir < 75;

            Penilaian::updateOrCreate(
                [
                    'siswa_id'    => $siswaId,
                    'mapel_id'    => $request->mapel_id,
                    'rombel_id'   => $request->rombel_id,
                    'semester_id' => $request->semester_id,
                ],
                [
                    'nilai_harian' => $harian,
                    'tugas'        => $tugas,
                    'quiz'         => $quiz,
                    'uts'          => $uts,
                    'uas'          => $uas,
                    'praktik'      => $praktik,
                    'nilai_akhir'  => $nilaiAkhir,
                    'predikat'     => $predikat,
                    'is_remedial'  => $isRemedial,
                    'catatan'      => $catatan,
                ]
            );
        }

        return redirect()->route('guru.nilai.index', [
            'rombel_id'   => $request->rombel_id,
            'mapel_id'    => $request->mapel_id,
            'semester_id' => $request->semester_id,
        ])->with('success', 'Data penilaian berhasil disimpan!');
    }
}
