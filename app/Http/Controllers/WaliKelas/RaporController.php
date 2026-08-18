<?php

namespace App\Http\Controllers\WaliKelas;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class RaporController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa rombel milik Wali Kelas untuk cetak rapor.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $guru = $request->user()->guru;

        if (!$guru) {
            return redirect()->route('login')->with('error', 'Akun Anda tidak terhubung dengan data Guru.');
        }

        // 1. Ambil list Tahun Ajaran & tentukan ID aktif/pilihan
        $tahunAjaranList = TahunAjaran::orderBy('tahun', 'desc')->get();
        $tahunAjaranId   = $request->input('tahun_ajaran_id', $tahunAjaranList->first()?->id);

        // 2. Filter Rombel Wali Kelas sesuai Tahun Ajaran terpilih
        $rombelQuery = Rombel::where('wali_kelas_id', $guru->id);

        if ($tahunAjaranId) {
            $rombelQuery->where('tahun_ajaran_id', $tahunAjaranId);
        }

        $rombel = $rombelQuery->first() ?? Rombel::where('wali_kelas_id', $guru->id)->first();

        if (!$rombel) {
            return redirect()
                ->route('guru.dashboard')
                ->with('error', 'Akses ditolak! Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // 3. Tentukan Semester (sediakan alias $semester dan $semesterId agar cocok dengan Blade)
        $semesterList = SemesterEnum::cases();
        $semesterId   = $request->input('semester_id', $request->input('semester', SemesterEnum::GANJIL->value ?? 'ganjil'));
        $semester     = $semesterId;

        // 4. Eager Load Relasi pada Siswa (MENCEGAH ERROR: count() on null & Issue N+1)
        $siswaList = Siswa::where('rombel_id', $rombel->id)
            ->with([
                'penilaian',
                'nilaiEkskul',
                'presensiHarian',
            ])
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // 5. Total Mata Pelajaran untuk indikator perhitungan persentase/kelengkapan nilai di Blade
        $totalMapel = Mapel::count();

        return view('walikelas.rapor.index', [
            'tahunAjaranList' => $tahunAjaranList,
            'tahunAjaranId'   => $tahunAjaranId,
            'semesterList'    => $semesterList,
            'semesterId'      => $semesterId,
            'semester'        => $semester,
            'rombel'          => $rombel,
            'siswaList'       => $siswaList,
            'totalMapel'      => $totalMapel,
        ]);
    }

    /**
     * Cetak Rapor Per Siswa (Individu)
     */
    public function cetakSiswa(Request $request, Siswa $siswa): Response|RedirectResponse
    {
        $guru   = $request->user()->guru;
        $rombel = Rombel::where('wali_kelas_id', $guru?->id)->first();

        // 1. Proteksi Otorisasi
        if (!$rombel || $siswa->rombel_id !== $rombel->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses mencetak rapor siswa ini.');
        }

        $semesterFilter = $request->query('semester_id', $request->query('semester'));

        // 2. Load Relasi Penilaian & Ekskul
        $siswaList = new Collection([$siswa]);
        $this->loadSiswaRelations($siswaList, $semesterFilter);

        // 3. Rekap Presensi Individu dengan Fallback Default
        $presensi = DB::table('presensi_harian')
            ->where('siswa_id', $siswa->id)
            //->when($semesterFilter, fn($q) => $q->where('semester', $semesterFilter))
            ->selectRaw("
                COUNT(CASE WHEN LOWER(status) = 'sakit' THEN 1 END) as sakit,
                COUNT(CASE WHEN LOWER(status) = 'izin' THEN 1 END) as izin,
                COUNT(CASE WHEN LOWER(status) IN ('alpa', 'alfa') THEN 1 END) as alpa
            ")
            ->first();

        $siswa->rekap_presensi = $presensi ?? (object) ['sakit' => 0, 'izin' => 0, 'alpa' => 0];

        $filename = "Rapor_{$siswa->nisn}_{$siswa->nama_lengkap}.pdf";

        return $this->generatePdfResponse($siswaList, $rombel, $semesterFilter, $filename);
    }

    /**
     * Cetak Rapor Massal 1 Rombel (Bulk Print)
     */
    public function cetakRombel(Request $request, Rombel $rombel): Response|RedirectResponse
    {
        $guru = $request->user()->guru;

        // 1. Proteksi Otorisasi
        if (!$guru || $rombel->wali_kelas_id !== $guru->id) {
            return redirect()->back()->with('error', 'Akses ditolak! Anda bukan Wali Kelas dari rombel ini.');
        }

        $semesterFilter = $request->query('semester_id', $request->query('semester'));

        // 2. Fetch Siswa & Eager Load Relasi
        $siswaList = $rombel->siswa()
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $this->loadSiswaRelations($siswaList, $semesterFilter);

        // 3. Batch Query Presensi seluruh siswa dalam Rombel
        $siswaIds = $siswaList->pluck('id')->toArray();

        $presensiRekapMap = DB::table('presensi_harian')
            ->whereIn('siswa_id', $siswaIds)
            //->when($semesterFilter, fn($q) => $q->where('semester', $semesterFilter))
            ->select(
                'siswa_id',
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN LOWER(status) IN ('alpa', 'alfa') THEN 1 END) as alpa")
            )
            ->groupBy('siswa_id')
            ->get()
            ->keyBy('siswa_id');

        // 4. Mapping data presensi ke tiap Siswa
        foreach ($siswaList as $siswa) {
            $siswa->rekap_presensi = $presensiRekapMap->get($siswa->id) ?? (object) [
                'sakit' => 0,
                'izin'  => 0,
                'alpa'  => 0,
            ];
        }

        $filename = "Rapor_Rombel_{$rombel->nama_rombel}.pdf";

        return $this->generatePdfResponse($siswaList, $rombel, $semesterFilter, $filename);
    }

    /**
     * Helper Private: Eager Load Relasi Penilaian & Ekskul
     */
    private function loadSiswaRelations(Collection $siswaCollection, ?string $semesterFilter): void
    {
        // Konversi ke Eloquent Collection jika variabel yang masuk bukan Eloquent Collection
        if (!$siswaCollection instanceof Collection) {
            $siswaCollection = new Collection(
                is_iterable($siswaCollection) ? $siswaCollection : [$siswaCollection]
            );
        }

        $siswaCollection->load([
            'rombel.waliKelas',
            'penilaian' => function ($query) use ($semesterFilter) {
                if ($semesterFilter) {
                    $query->where('semester', $semesterFilter);
                }
                $query->with('mapel');
            },
            'nilaiEkskul' => function ($query) use ($semesterFilter) {
                if ($semesterFilter) {
                    $query->where('semester', $semesterFilter);
                }
                $query->with('ekstrakurikuler');
            }
        ]);
    }

    /**
     * Helper Private: Generate PDF Response
     */
    private function generatePdfResponse(Collection $siswaList, Rombel $rombel, ?string $semester, string $filename): Response
    {
        $pdf = Pdf::loadView('walikelas.rapor.pdf', [
            'siswaList'    => $siswaList,
            'rombel'       => $rombel,
            'semester'     => $semester,
            'tanggalCetak' => now()->locale('id')->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }
}
