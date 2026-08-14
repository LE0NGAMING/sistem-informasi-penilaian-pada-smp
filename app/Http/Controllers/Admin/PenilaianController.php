<?php

namespace App\Http\Controllers\Guru;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Menampilkan lembar input dan daftar penilaian siswa berdasarkan hak ampu guru.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $guru = Auth::user()->guru;

        // Ambil daftar penugasan (pengampu) milik guru yang sedang login
        $pengampus = $guru->pengampus()->with(['rombel', 'mapel'])->get();

        // Ekstraksi pilihan Rombel dan Mapel HANYA yang diajar oleh guru ini
        $rombelList = $pengampus->pluck('rombel')->unique('id')->sortBy('nama_rombel');
        $mapelList = $pengampus->pluck('mapel')->unique('id')->sortBy('nama_mapel');

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');

        $siswaList = collect();

        // Jika filter dipilih, pastikan kombinasi rombel & mapel tersebut diampu oleh guru ini
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
     * Menyimpan atau memperbarui data penilaian siswa K13 secara masal.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rombel_id'                     => ['required', 'exists:rombel,id'],
            'mapel_id'                      => ['required', 'exists:mapel,id'],
            'semester_id'                   => ['required', Rule::enum(SemesterEnum::class)],
            'nilai'                         => ['required', 'array'],

            // Komponen KI-3 (Pengetahuan)
            'nilai.*.nilai_harian'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.tugas'                 => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.quiz'                  => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uts'                   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uas'                   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.deskripsi_pengetahuan' => ['nullable', 'string', 'max:500'],

            // Komponen KI-4 (Keterampilan)
            'nilai.*.praktik'               => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.proyek'                => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.portofolio'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.deskripsi_keterampilan' => ['nullable', 'string', 'max:500'],

            'nilai.*.catatan'               => ['nullable', 'string', 'max:255'],
        ]);

        $guru = Auth::user()->guru;

        // Validasi Ketat Hak Ampu
        $isAuthorized = $guru->pengampus()
            ->where('rombel_id', $validated['rombel_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->exists();

        if (!$isAuthorized) {
            abort(403, 'Aksi ditolak. Anda tidak memiliki wewenang untuk mengisi nilai pada kelas dan mata pelajaran ini.');
        }

        // Ambil daftar ID siswa sah di rombel ini
        $validSiswaIds = Siswa::where('rombel_id', $validated['rombel_id'])
            ->pluck('id')
            ->toArray();

        DB::transaction(function () use ($validated, $validSiswaIds) {
            foreach ($validated['nilai'] as $siswaId => $scores) {
                if (!in_array((int) $siswaId, $validSiswaIds, true)) {
                    continue;
                }

                // Parse input float / null secara aman
                $harian     = $this->parseFloat($scores['nilai_harian'] ?? null);
                $tugas      = $this->parseFloat($scores['tugas'] ?? null);
                $quiz       = $this->parseFloat($scores['quiz'] ?? null);
                $uts        = $this->parseFloat($scores['uts'] ?? null);
                $uas        = $this->parseFloat($scores['uas'] ?? null);

                $praktik    = $this->parseFloat($scores['praktik'] ?? null);
                $proyek     = $this->parseFloat($scores['proyek'] ?? null);
                $portofolio = $this->parseFloat($scores['portofolio'] ?? null);

                // 1. Kalkulasi Pengetahuan (KI-3)
                $nilaiPengetahuan    = $this->calculatePengetahuan($harian, $tugas, $quiz, $uts, $uas);
                $predikatPengetahuan = $this->calculatePredikat($nilaiPengetahuan);

                // 2. Kalkulasi Keterampilan (KI-4)
                $nilaiKeterampilan    = $this->calculateKeterampilan($praktik, $proyek, $portofolio);
                $predikatKeterampilan = $this->calculatePredikat($nilaiKeterampilan);

                // 3. Overall Nilai Akhir (Rata-rata KI-3 & KI-4 untuk kompatibilitas data lama)
                $nilaiAkhir = $this->calculateOverallNilai($nilaiPengetahuan, $nilaiKeterampilan);
                $predikat   = $this->calculatePredikat($nilaiAkhir);

                Penilaian::updateOrCreate(
                    [
                        'siswa_id'  => $siswaId,
                        'mapel_id'  => $validated['mapel_id'],
                        'rombel_id' => $validated['rombel_id'],
                        'semester'  => $validated['semester_id'],
                    ],
                    [
                        // KI-3
                        'nilai_harian'          => $harian,
                        'tugas'                 => $tugas,
                        'quiz'                  => $quiz,
                        'uts'                   => $uts,
                        'uas'                   => $uas,
                        'nilai_pengetahuan'     => $nilaiPengetahuan,
                        'predikat_pengetahuan'  => $predikatPengetahuan,
                        'deskripsi_pengetahuan' => $scores['deskripsi_pengetahuan'] ?? null,

                        // KI-4
                        'praktik'               => $praktik,
                        'proyek'                => $proyek,
                        'portofolio'            => $portofolio,
                        'nilai_keterampilan'    => $nilaiKeterampilan,
                        'predikat_keterampilan' => $predikatKeterampilan,
                        'deskripsi_keterampilan' => $scores['deskripsi_keterampilan'] ?? null,

                        // Field Umum & Backward Compatibility
                        'nilai_akhir'           => $nilaiAkhir,
                        'predikat'              => $predikat,
                        'is_remedial'           => ($nilaiAkhir !== null && $nilaiAkhir < 75),
                        'catatan'               => $scores['catatan'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('guru.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id']))
            ->with('success', 'Data penilaian K13 berhasil disimpan!');
    }

    /* =========================================================================
     * HELPER FUNCTIONS (CLEAN CODE)
     * ========================================================================= */

    /**
     * Konversi string input ke float atau null.
     */
    private function parseFloat(mixed $val): ?float
    {
        return ($val !== null && $val !== '') ? (float) $val : null;
    }

    /**
     * Menghitung Nilai Pengetahuan (KI-3) proporsional hanya dari komponen yang terisi.
     */
    private function calculatePengetahuan(?float $harian, ?float $tugas, ?float $quiz, ?float $uts, ?float $uas): ?float
    {
        $formatif = array_filter([$harian, $tugas, $quiz], fn($v) => !is_null($v));
        $avgFormatif = count($formatif) > 0 ? array_sum($formatif) / count($formatif) : null;

        $components = [];
        if (!is_null($avgFormatif)) $components[] = ['val' => $avgFormatif, 'weight' => 0.40]; // 40% Formatif
        if (!is_null($uts))         $components[] = ['val' => $uts, 'weight' => 0.30];         // 30% UTS
        if (!is_null($uas))         $components[] = ['val' => $uas, 'weight' => 0.30];         // 30% UAS

        if (empty($components)) {
            return null;
        }

        $totalWeight = array_sum(array_column($components, 'weight'));
        $weightedSum = array_reduce($components, fn($carry, $item) => $carry + ($item['val'] * $item['weight']), 0);

        return round($weightedSum / $totalWeight, 2);
    }

    /**
     * Menghitung Nilai Keterampilan (KI-4) dari rata-rata komponen terisi.
     */
    private function calculateKeterampilan(?float $praktik, ?float $proyek, ?float $portofolio): ?float
    {
        $scores = array_filter([$praktik, $proyek, $portofolio], fn($v) => !is_null($v));

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Menghitung gabungan Nilai Akhir (KI-3 & KI-4).
     */
    private function calculateOverallNilai(?float $pengetahuan, ?float $keterampilan): ?float
    {
        $scores = array_filter([$pengetahuan, $keterampilan], fn($v) => !is_null($v));

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Penentuan Predikat K13 standar.
     */
    private function calculatePredikat(?float $nilai): string
    {
        if (is_null($nilai)) {
            return 'D';
        }

        return match (true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B',
            $nilai >= 75 => 'C',
            default      => 'D',
        };
    }
}
