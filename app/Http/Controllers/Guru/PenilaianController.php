<?php

namespace App\Http\Controllers\Guru;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\TahunAjaran;
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

        $tahunAjaranList = TahunAjaran::orderBy('tahun', 'desc')->get();

        $rombelId = $request->query('rombel_id');
        $mapelId = $request->query('mapel_id');
        $semester = $request->query('semester_id');
        $tahunAjaranId = $request->query('tahun_ajaran_id');

        $siswaList = collect();

        // Jika filter dipilih, pastikan kombinasi rombel & mapel tersebut diampu oleh guru ini
        if ($rombelId && $mapelId && $semester && $tahunAjaranId) {
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
                ->with(['penilaian' => function ($query) use ($mapelId, $semester, $tahunAjaranId) {
                    $query->where('mapel_id', $mapelId)
                        ->where('semester', $semester)
                        ->where('tahun_ajaran_id', $tahunAjaranId);
                }])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('guru.nilai.index', [
            'rombelList'   => $rombelList,
            'mapelList'    => $mapelList,
            'semesterList' => SemesterEnum::cases(),
            'tahunAjaranList' => $tahunAjaranList,
            'siswaList'    => $siswaList,
            'rombelId'     => $rombelId,
            'mapelId'      => $mapelId,
            'semesterId'   => $semester,
            'tahunAjaranId'   => $tahunAjaranId,
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
            'tahun_ajaran_id'               => ['required', 'exists:tahun_ajaran,id'],
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

                $deskPengetahuan  = $scores['deskripsi_pengetahuan'] ?? null;
                $deskKeterampilan = $scores['deskripsi_keterampilan'] ?? null;
                $catatan          = $scores['catatan'] ?? null;

                // -------------------------------------------------------------
                // CEK: Jika baris siswa ini 100% KOSONG, abaikan (jangan simpan)
                // -------------------------------------------------------------
                $isAllEmpty = is_null($harian) && is_null($tugas) && is_null($quiz) &&
                    is_null($uts) && is_null($uas) && is_null($praktik) &&
                    is_null($proyek) && is_null($portofolio) &&
                    empty($deskPengetahuan) && empty($deskKeterampilan) && empty($catatan);

                if ($isAllEmpty) {
                    continue; // Skip siswa yang tidak memiliki data input
                }

                // 1. Kalkulasi Pengetahuan (KI-3)
                $nilaiPengetahuan    = $this->calculatePengetahuan($harian, $tugas, $quiz, $uts, $uas);
                $predikatPengetahuan = $this->calculatePredikat($nilaiPengetahuan);

                // 2. Kalkulasi Keterampilan (KI-4)
                $nilaiKeterampilan    = $this->calculateKeterampilan($praktik, $proyek, $portofolio);
                $predikatKeterampilan = $this->calculatePredikat($nilaiKeterampilan);

                // 3. Overall Nilai Akhir
                $nilaiAkhir = $this->calculateOverallNilai($nilaiPengetahuan, $nilaiKeterampilan);
                $predikat   = $this->calculatePredikat($nilaiAkhir);

                Penilaian::updateOrCreate(
                    [
                        'siswa_id'        => $siswaId,
                        'mapel_id'        => $validated['mapel_id'],
                        'rombel_id'       => $validated['rombel_id'],
                        'semester'        => $validated['semester_id'],
                        'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
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
                        'deskripsi_pengetahuan' => $deskPengetahuan,

                        // KI-4
                        'praktik'               => $praktik,
                        'proyek'                => $proyek,
                        'portofolio'            => $portofolio,
                        'nilai_keterampilan'    => $nilaiKeterampilan,
                        'predikat_keterampilan' => $predikatKeterampilan,
                        'deskripsi_keterampilan' => $deskKeterampilan,

                        // Field Umum
                        'nilai_akhir'           => $nilaiAkhir,
                        'predikat'              => $predikat,
                        'is_remedial'           => ($nilaiAkhir !== null && $nilaiAkhir < 75),
                        'catatan'               => $catatan,
                    ]
                );
            }
        });

        return redirect()
            ->route('guru.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id', 'tahun_ajaran_id']))
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
     * Menghitung Nilai Pengetahuan (KI-3).
     * Hanya dihitung jika KELIMA komponen SUDAH TERISI LENGKAP.
     */
    private function calculatePengetahuan(?float $harian, ?float $tugas, ?float $quiz, ?float $uts, ?float $uas): ?float
    {
        if (is_null($harian) || is_null($tugas) || is_null($quiz) || is_null($uts) || is_null($uas)) {
            return null;
        }

        $avgFormatif = ($harian + $tugas + $quiz) / 3;
        $weightedSum = ($avgFormatif * 0.40) + ($uts * 0.30) + ($uas * 0.30);

        return round($weightedSum, 2);
    }

    /**
     * Menghitung Nilai Keterampilan (KI-4).
     * Hanya dihitung jika KETIGA komponen SUDAH TERISI LENGKAP.
     */
    private function calculateKeterampilan(?float $praktik, ?float $proyek, ?float $portofolio): ?float
    {
        if (is_null($praktik) || is_null($proyek) || is_null($portofolio)) {
            return null;
        }

        return round(($praktik + $proyek + $portofolio) / 3, 2);
    }

    /**
     * Menghitung gabungan Nilai Akhir (KI-3 & KI-4).
     */
    private function calculateOverallNilai(?float $pengetahuan, ?float $keterampilan): ?float
    {
        if (is_null($pengetahuan) || is_null($keterampilan)) {
            return null;
        }

        return round(($pengetahuan + $keterampilan) / 2, 2);
    }

    /**
     * Penentuan Predikat K13 standar.
     * Mengembalikan null jika nilai belum tersedia.
     */
    private function calculatePredikat(?float $nilai): ?string
    {
        if (is_null($nilai)) {
            return null;
        }

        return match (true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B',
            $nilai >= 75 => 'C',
            default      => 'D',
        };
    }
}
