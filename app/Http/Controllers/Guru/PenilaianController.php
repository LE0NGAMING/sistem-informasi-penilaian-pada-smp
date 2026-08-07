<?php

namespace App\Http\Controllers\Guru;

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
     * Menampilkan lembar input dan daftar penilaian siswa.
     */
    public function index(Request $request): View
    {
        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');

        $siswaList = collect();

        if ($rombelId && $mapelId && $semester) {
            $siswaList = Siswa::query()
                ->where('rombel_id', $rombelId)
                ->with(['penilaian' => function ($query) use ($mapelId, $semester) {
                    $query->where('mapel_id', $mapelId)
                        ->where('semester', $semester); // Disesuaikan ke nama kolom 'semester' di DB
                }])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('guru.nilai.index', [
            'rombelList'   => Rombel::select('id', 'nama_rombel')->orderBy('nama_rombel')->get(),
            'mapelList'    => Mapel::select('id', 'nama_mapel')->orderBy('nama_mapel')->get(),
            'semesterList' => SemesterEnum::cases(),
            'siswaList'    => $siswaList,
            'rombelId'     => $rombelId,
            'mapelId'      => $mapelId,
            'semesterId'   => $semester,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data penilaian siswa secara masal.
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

        DB::transaction(function () use ($validated) {
            foreach ($validated['nilai'] as $siswaId => $scores) {
                $harian  = (float) ($scores['nilai_harian'] ?? 0);
                $tugas   = (float) ($scores['tugas'] ?? 0);
                $quiz    = (float) ($scores['quiz'] ?? 0);
                $uts     = (float) ($scores['uts'] ?? 0);
                $uas     = (float) ($scores['uas'] ?? 0);
                $praktik = (float) ($scores['praktik'] ?? 0);

                // Perhitungan Nilai Akhir
                $nilaiAkhir = round(
                    ($harian * 0.15) + ($tugas * 0.15) + ($quiz * 0.10) +
                        ($uts * 0.20) + ($uas * 0.20) + ($praktik * 0.20),
                    2
                );

                // Penentuan Predikat
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
                        'semester'  => $validated['semester_id'], // Disesuaikan ke nama kolom 'semester' di DB
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
            ->route('guru.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id']))
            ->with('success', 'Data penilaian berhasil disimpan!');
    }
}
