<?php

namespace App\Http\Controllers\Guru;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Menampilkan lembar input dan daftar penilaian siswa berdasarkan hak ampu guru.
     */
    public function index(Request $request): View
    {
        $guru = auth()->user()->guru;

        // Ambil daftar penugasan (pengampu) milik guru yang sedang login
        // Di-load beserta relasi rombel dan mapel-nya
        $pengampus = $guru->pengampus()->with(['rombel', 'mapel'])->get();

        // Ekstraksi pilihan Rombel dan Mapel yang HANYA diajar oleh guru ini untuk dropdown
        $rombelList = $pengampus->pluck('rombel')->unique('id')->sortBy('nama_rombel');
        $mapelList = $pengampus->pluck('mapel')->unique('id')->sortBy('nama_mapel');

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');

        $siswaList = collect();

        // Jika filter dipilih, pastikan kombinasi rombel & mapel tersebut benar-benar diampu oleh guru ini
        if ($rombelId && $mapelId && $semester) {
            $isAuthorized = $pengampus->contains(function ($item) use ($rombelId, $mapelId) {
                return $item->rombel_id == $rombelId && $item->mapel_id == $mapelId;
            });

            if (!$isAuthorized) {
                return redirect()
                    ->route('guru.nilai.index')
                    ->with('error', 'Anda tidak memiliki hak akses untuk mengajar kelas dan mata pelajaran tersebut.');
            }

            $siswaList = Siswa::query()
                ->where('rombel_id', $rombelId)
                ->with(['penilaian' => function ($query) use ($mapelId, $semester) {
                    $query->where('mapel_id', $mapelId)
                        ->where('semester', $semester);
                }])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('guru.nilai.index', [
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
     * Menyimpan atau memperbarui data penilaian siswa secara masal dengan validasi pengampu.
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

        $guru = auth()->user()->guru;

        // Validasi Ketat: Pastikan guru yang menginput benar-benar mengampu rombel & mapel ini
        $isAuthorized = $guru->pengampus()
            ->where('rombel_id', $validated['rombel_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->exists();

        if (!$isAuthorized) {
            abort(403, 'Aksi ditolak. Anda tidak memiliki wewenang untuk mengisi nilai pada kelas dan mata pelajaran ini.');
        }

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
            ->route('guru.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id']))
            ->with('success', 'Data penilaian berhasil disimpan!');
    }
}
