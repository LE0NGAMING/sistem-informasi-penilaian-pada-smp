<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Penilaian;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Menampilkan lembar input dan daftar penilaian siswa untuk Admin (Semua Akses).
     */
    public function index(Request $request): View
    {
        // Admin dapat melihat semua Rombel dan Mapel tanpa batasan tabel pengampu
        $rombelList = Rombel::orderBy('nama_rombel')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');

        $siswaList = collect();

        // Jika filter dipilih, ambil siswa berdasarkan rombel yang dipilih
        if ($rombelId && $mapelId && $semester) {
            $siswaList = Siswa::query()
                ->where('rombel_id', $rombelId)
                ->with(['penilaian' => function ($query) use ($mapelId, $semester) {
                    $query->where('mapel_id', $mapelId)
                        ->where('semester', $semester);
                }])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('admin.nilai.index', [
            'rombelList'   => $rombelList,
            'mapelList'    => $mapelList,
            'semesterList' => SemesterEnum::cases(),
            'siswaList'    => $siswaList,
            'rombelId'     => $rombelId,
            'mapelId'      => $mapelId,
            'semesterId'   => $semester,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data penilaian siswa oleh Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rombel_id'            => ['required', 'exists:rombel,id'],
            'mapel_id'             => ['required', 'exists:mapel,id'],
            'semester_id'          => ['required', Rule::enum(SemesterEnum::class)],
            'nilai'                => ['required', 'array'],
            'nilai.*.nilai_harian' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.tugas'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.quiz'         => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uts'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uas'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.praktik'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.catatan'      => ['nullable', 'string', 'max:255'],
        ]);

        // Tidak ada pengecekan auth()->user()->guru di sini (Bypass hak ampu)

        // Ambil daftar ID siswa yang SAH berada di rombel tersebut
        $validSiswaIds = Siswa::where('rombel_id', $validated['rombel_id'])
            ->pluck('id')
            ->toArray();

        DB::transaction(function () use ($validated, $validSiswaIds) {
            foreach ($validated['nilai'] as $siswaId => $scores) {
                // Pastikan siswa benar-benar berada di rombel yang dimaksud
                if (!in_array((int) $siswaId, $validSiswaIds, true)) {
                    continue;
                }

                $harian  = isset($scores['nilai_harian']) && $scores['nilai_harian'] !== '' ? (float) $scores['nilai_harian'] : null;
                $tugas   = isset($scores['tugas']) && $scores['tugas'] !== '' ? (float) $scores['tugas'] : null;
                $quiz    = isset($scores['quiz']) && $scores['quiz'] !== '' ? (float) $scores['quiz'] : null;
                $uts     = isset($scores['uts']) && $scores['uts'] !== '' ? (float) $scores['uts'] : null;
                $uas     = isset($scores['uas']) && $scores['uas'] !== '' ? (float) $scores['uas'] : null;
                $praktik = isset($scores['praktik']) && $scores['praktik'] !== '' ? (float) $scores['praktik'] : null;

                $nilaiAkhir = round(
                    (($harian ?? 0) * 0.15) +
                        (($tugas ?? 0) * 0.15) +
                        (($quiz ?? 0) * 0.10) +
                        (($uts ?? 0) * 0.20) +
                        (($uas ?? 0) * 0.20) +
                        (($praktik ?? 0) * 0.20),
                    2
                );

                $predikat = match (true) {
                    $nilaiAkhir >= 90 => 'A',
                    $nilaiAkhir >= 80 => 'B',
                    $nilaiAkhir >= 75 => 'C',
                    default           => 'D',
                };

                Penilaian::updateOrCreate(
                    [
                        'siswa_id'  => $siswaId,
                        'mapel_id'  => $validated['mapel_id'],
                        'rombel_id' => $validated['rombel_id'],
                        'semester'  => $validated['semester_id'],
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
                        'is_remedial'  => $nilaiAkhir < 75,
                        'catatan'      => $scores['catatan'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id']))
            ->with('success', 'Data penilaian berhasil dikelola (Mode Admin Sekolah)!');
    }
}
