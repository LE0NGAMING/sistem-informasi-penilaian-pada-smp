<?php

namespace App\Http\Controllers\Guru;

use App\Enums\SemesterEnum;
use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaporController extends Controller
{
    /**
     * Menampilkan halaman pencarian & daftar siswa untuk cetak rapor.
     */
    public function index(Request $request)
    {
        // Ambil data rombel untuk dropdown pilihan rombel
        $rombelList = Rombel::with('kelas')->latest()->get();

        // Ambil parameter filter rombel dan semester
        $rombelId   = $request->get('rombel_id');
        $semesterId = $request->get('semester_id');

        // Gunakan Enum Semester langsung
        $semesterList = SemesterEnum::cases();

        // Ambil data siswa jika rombel sudah dipilih
        $siswaList = [];
        if ($rombelId) {
            $rombel = Rombel::with(['siswa' => function ($query) {
                $query->orderBy('nama_lengkap', 'asc');
            }])->find($rombelId);

            $siswaList = $rombel ? $rombel->siswa : [];
        }

        return view('guru.rapor.index', compact(
            'rombelList',
            'rombelId',
            'semesterList',
            'semesterId',
            'siswaList'
        ));
    }

    /**
     * Cetak Rapor Per Siswa (Individu)
     */
    public function cetakSiswa(Request $request, Siswa $siswa)
    {
        $semesterFilter = $request->query('semester_id');

        // 1. Eager Load Relasi Rombel & Penilaian (KI-3 & KI-4)
        $siswa->load([
            'rombel.waliKelas',
            'penilaian' => function ($query) use ($semesterFilter) {
                if ($semesterFilter) {
                    $query->where('semester', $semesterFilter);
                }
                $query->with('mapel');
            }
        ]);

        // 2. Ambil Rekap Presensi Individu (Case-Insensitive Status)
        $siswa->rekap_presensi = DB::table('presensi_harian')
            ->where('siswa_id', $siswa->id)
            ->selectRaw("
                COUNT(CASE WHEN LOWER(status) = 'sakit' THEN 1 END) as sakit,
                COUNT(CASE WHEN LOWER(status) = 'izin' THEN 1 END) as izin,
                COUNT(CASE WHEN LOWER(status) IN ('alpa', 'alfa') THEN 1 END) as alpa
            ")
            ->first();

        // Normalisasi: Bungkus ke dalam Collection agar Blade menggunakan 1 perulangan yang sama
        $siswaList = collect([$siswa]);

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'siswaList'    => $siswaList,
            'semester'     => $semesterFilter,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $filename = "Rapor_{$siswa->nisn}_{$siswa->nama_lengkap}.pdf";

        return $pdf->stream($filename);
    }

    /**
     * Cetak Rapor Massal 1 Rombel (Bulk Print)
     */
    public function cetakRombel(Request $request, Rombel $rombel)
    {
        $semesterFilter = $request->query('semester_id');

        // 1. Fetch Seluruh Siswa Rombel & Penilaiannya
        $siswaList = $rombel->siswa()
            ->with([
                'rombel.waliKelas',
                'penilaian' => function ($query) use ($semesterFilter) {
                    if ($semesterFilter) {
                        $query->where('semester', $semesterFilter);
                    }
                    $query->with('mapel');
                }
            ])
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // 2. Batch Query Presensi seluruh siswa dalam Rombel (Mencegah N+1 Query)
        $siswaIds = $siswaList->pluck('id')->toArray();

        $presensiRekapMap = DB::table('presensi_harian')
            ->whereIn('siswa_id', $siswaIds)
            ->select(
                'siswa_id',
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN LOWER(status) IN ('alpa', 'alfa') THEN 1 END) as alpa")
            )
            ->groupBy('siswa_id')
            ->get()
            ->keyBy('siswa_id');

        // 3. Tempelkan data rekap presensi ke masing-masing objek Siswa
        foreach ($siswaList as $siswa) {
            $siswa->rekap_presensi = $presensiRekapMap->get($siswa->id) ?? (object) [
                'sakit' => 0,
                'izin'  => 0,
                'alpa'  => 0,
            ];
        }

        $pdf = Pdf::loadView('guru.rapor.pdf', [
            'siswaList'    => $siswaList,
            'semester'     => $semesterFilter,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $filename = "Rapor_Rombel_{$rombel->nama_rombel}.pdf";

        return $pdf->stream($filename);
    }
}
