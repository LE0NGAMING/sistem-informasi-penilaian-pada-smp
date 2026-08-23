<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * Menampilkan lembar input dan daftar penilaian siswa untuk Admin (Tanpa batasan ampu).
     */
    public function index(Request $request): View
    {
        // Admin memiliki akses penuh ke seluruh data master
        $rombelList       = Rombel::orderBy('nama_rombel', 'asc')->get();
        $mapelList        = Mapel::orderBy('nama_mapel', 'asc')->get();
        $tahunAjaranList  = TahunAjaran::orderBy('tahun', 'desc')->get();

        $rombelId       = $request->query('rombel_id');
        $mapelId        = $request->query('mapel_id');
        $semester       = $request->query('semester_id');
        $tahunAjaranId  = $request->query('tahun_ajaran_id');

        $siswaList = collect();

        // Jika semua filter terisi, ambil data siswa beserta nilai terkait
        if ($rombelId && $mapelId && $semester && $tahunAjaranId) {
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

        return view('admin.nilai.index', [
            'rombelList'      => $rombelList,
            'mapelList'       => $mapelList,
            'semesterList'    => SemesterEnum::cases(),
            'tahunAjaranList' => $tahunAjaranList,
            'siswaList'       => $siswaList,
            'rombelId'        => $rombelId,
            'mapelId'         => $mapelId,
            'semesterId'      => $semester,
            'tahunAjaranId'   => $tahunAjaranId,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data penilaian siswa oleh Admin secara masal.
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

        // Ambil daftar ID siswa sah di rombel ini
        $validSiswaIds = Siswa::where('rombel_id', $validated['rombel_id'])
            ->pluck('id')
            ->toArray();

        DB::transaction(function () use ($validated, $validSiswaIds) {
            foreach ($validated['nilai'] as $siswaId => $scores) {
                if (!in_array((int) $siswaId, $validSiswaIds, true)) {
                    continue;
                }

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

                $mapel = Mapel::findOrFail($validated['mapel_id']);
                $mapelName = $mapel->nama_mapel;

                // Abaikan baris jika data kosong sepenuhnya
                $isAllEmpty = is_null($harian) && is_null($tugas) && is_null($quiz) &&
                    is_null($uts) && is_null($uas) && is_null($praktik) &&
                    is_null($proyek) && is_null($portofolio) &&
                    empty($deskPengetahuan) && empty($deskKeterampilan) && empty($catatan);

                if ($isAllEmpty) {
                    continue;
                }

                $nilaiPengetahuan     = $this->calculatePengetahuan($harian, $tugas, $quiz, $uts, $uas);
                $predikatPengetahuan  = $this->calculatePredikat($nilaiPengetahuan);

                $nilaiKeterampilan    = $this->calculateKeterampilan($praktik, $proyek, $portofolio);
                $predikatKeterampilan = $this->calculatePredikat($nilaiKeterampilan);

                $nilaiAkhir           = $this->calculateOverallNilai($nilaiPengetahuan, $nilaiKeterampilan);
                $predikat             = $this->calculatePredikat($nilaiAkhir);

                Penilaian::updateOrCreate(
                    [
                        'siswa_id'        => $siswaId,
                        'mapel_id'        => $validated['mapel_id'],
                        'rombel_id'       => $validated['rombel_id'],
                        'semester'        => $validated['semester_id'],
                        'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                    ],
                    [
                        'nilai_harian'           => $harian,
                        'tugas'                  => $tugas,
                        'quiz'                   => $quiz,
                        'uts'                    => $uts,
                        'uas'                    => $uas,
                        'nilai_pengetahuan'      => $nilaiPengetahuan,
                        'predikat_pengetahuan'   => $predikatPengetahuan,
                        'deskripsi_pengetahuan'  => $deskPengetahuan,

                        'praktik'                => $praktik,
                        'proyek'                 => $proyek,
                        'portofolio'             => $portofolio,
                        'nilai_keterampilan'     => $nilaiKeterampilan,
                        'predikat_keterampilan'  => $predikatKeterampilan,
                        'deskripsi_keterampilan' => $deskKeterampilan,

                        'nilai_akhir'            => $nilaiAkhir,
                        'predikat'               => $predikat,
                        'is_remedial'            => ($nilaiAkhir !== null && $nilaiAkhir < 75),
                        'catatan'                => $catatan,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.nilai.index', $request->only(['rombel_id', 'mapel_id', 'semester_id', 'tahun_ajaran_id']))
            ->with('success', 'Data penilaian berhasil diperbarui oleh Admin!');
    }

    private function parseFloat(mixed $val): ?float
    {
        return ($val !== null && $val !== '') ? (float) $val : null;
    }

    private function calculatePengetahuan(?float $harian, ?float $tugas, ?float $quiz, ?float $uts, ?float $uas): ?float
    {
        $formatif = array_filter([$harian, $tugas, $quiz], fn($v) => !is_null($v));
        $avgFormatif = count($formatif) > 0 ? array_sum($formatif) / count($formatif) : null;

        $components = [];
        if (!is_null($avgFormatif)) $components[] = ['val' => $avgFormatif, 'weight' => 0.40];
        if (!is_null($uts))         $components[] = ['val' => $uts, 'weight' => 0.30];
        if (!is_null($uas))         $components[] = ['val' => $uas, 'weight' => 0.30];

        if (empty($components)) return null;

        $totalWeight = array_sum(array_column($components, 'weight'));
        $weightedSum = array_reduce($components, fn($carry, $item) => $carry + ($item['val'] * $item['weight']), 0);

        return round($weightedSum / $totalWeight, 2);
    }

    private function calculateKeterampilan(?float $praktik, ?float $proyek, ?float $portofolio): ?float
    {
        $scores = array_filter([$praktik, $proyek, $portofolio], fn($v) => !is_null($v));
        if (empty($scores)) return null;

        return round(array_sum($scores) / count($scores), 2);
    }

    private function calculateOverallNilai(?float $pengetahuan, ?float $keterampilan): ?float
    {
        $scores = array_filter([$pengetahuan, $keterampilan], fn($v) => !is_null($v));
        if (empty($scores)) return null;

        return round(array_sum($scores) / count($scores), 2);
    }

    private function calculatePredikat(?float $nilai): ?string
    {
        if (is_null($nilai)) return null;

        return match (true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B',
            $nilai >= 75 => 'C',
            default      => 'D',
        };
    }
}
