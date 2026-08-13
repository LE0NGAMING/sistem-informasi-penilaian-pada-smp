<?php

declare(strict_types=1);

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresensiRequest;
use App\Models\PresensiHarian;
use App\Models\Rombel;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresensiHarianController extends Controller
{
    /**
     * Menampilkan halaman rekap presensi.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $guru = $user?->guru;

        // 1. Pengecekan profil guru untuk mencegah Error Null Pointer
        if (!$guru) {
            return redirect()->route('guru.dashboard')->with('error', 'Data profil guru tidak ditemukan.');
        }

        // 2. Cari rombel di mana guru ini menjadi wali kelas
        $rombelBinaan = Rombel::where('wali_kelas_id', $guru->id)->first();

        if (!$rombelBinaan) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak terdaftar sebagai wali kelas.');
        }

        // Ambil tanggal dari parameter URL, jika kosong gunakan hari ini
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        $isLocked = Carbon::parse($tanggal)->lt(Carbon::today());

        // Ambil data siswa beserta presensinya pada tanggal tersebut
        $siswas = Siswa::query()
            ->where('rombel_id', $rombelBinaan->id)
            ->with(['presensiHarian' => function ($query) use ($tanggal) {
                $query->where('tanggal', $tanggal);
            }])
            ->orderBy('nama_lengkap')
            ->get();

        // Buat mapping array agar $presensis[$siswa->id] terbaca dengan benar di view
        $presensis = $siswas->mapWithKeys(function ($siswa) {
            return [$siswa->id => $siswa->presensiHarian->first()];
        });

        return view('walikelas.presensi.index', [
            'rombelBinaan' => $rombelBinaan,
            'daftarKelas'  => collect([$rombelBinaan]),
            'kelasId'      => $rombelBinaan->id,
            'siswas'       => $siswas,
            'presensis'    => $presensis,
            'tanggal'      => $tanggal,
        ]);
    }

    /**
     * Menyimpan presensi menggunakan optimasi Bulk Upsert.
     */
    public function store(StorePresensiRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tanggal = $validated['tanggal'];
        $waktuSekarang = now();

        // Validasi Sisi Server: Tolak jika mencoba menyimpan tanggal yang sudah terkunci
        if (Carbon::parse($tanggal)->lt(Carbon::today())) {
            return back()->with('error', 'Presensi tanggal ini sudah dikunci dan tidak dapat diubah.');
        }

        $dataPresensi = [];

        // Format data menjadi array flat untuk keperluan upsert
        foreach ($validated['presensi'] as $siswaId => $data) {
            $dataPresensi[] = [
                'siswa_id'   => (int) $siswaId,
                'tanggal'    => $tanggal,
                'status'     => $data['status'],
                // Mencegah bug mismatch antara key 'keterangan' dan 'catatan'
                'keterangan' => $data['keterangan'] ?? $data['catatan'] ?? null,
                'created_at' => $waktuSekarang,
                'updated_at' => $waktuSekarang,
            ];
        }

        // Simpan semua data hanya dengan 1 kali query ke database
        PresensiHarian::upsert(
            $dataPresensi,
            ['siswa_id', 'tanggal'], // Unik ID pencarian
            ['status', 'keterangan', 'updated_at'] // Kolom yang di-update jika data sudah ada
        );

        return back()->with('success', 'Data presensi untuk tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y') . ' berhasil disimpan!');
    }

    /**
     * Export Rekap Presensi Bulanan ke Excel (HTML Table Stream)
     */
    public function exportExcel(Request $request): StreamedResponse|RedirectResponse
    {
        $guru = Auth::user()?->guru;
        $rombelBinaan = Rombel::where('wali_kelas_id', $guru?->id)->first();

        if (!$rombelBinaan) {
            return back()->with('error', 'Anda tidak terdaftar sebagai wali kelas.');
        }

        $bulan = $request->query('bulan', Carbon::now()->month);
        $tahun = $request->query('tahun', Carbon::now()->year);
        $namaBulan = Carbon::createFromDate($tahun, (int)$bulan, 1)->translatedFormat('F Y');

        $siswas = Siswa::where('rombel_id', $rombelBinaan->id)->orderBy('nama_lengkap')->get();

        $presensiData = PresensiHarian::whereIn('siswa_id', $siswas->pluck('id'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy('siswa_id');

        $fileName = 'Rekap_Presensi_' . str_replace(' ', '_', $rombelBinaan->nama_rombel ?? $rombelBinaan->nama_kelas) . '_' . $namaBulan . '.xls';

        return response()->stream(function () use ($siswas, $presensiData, $rombelBinaan, $namaBulan) {
            echo view('walikelas.presensi.excel', [
                'siswas'       => $siswas,
                'presensiData' => $presensiData,
                'rombelBinaan' => $rombelBinaan,
                'namaBulan'    => $namaBulan,
            ])->render();
        }, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Export / Cetak Rekap Presensi PDF
     */
    public function exportPdf(Request $request)
    {
        $guru = Auth::user()?->guru;
        $rombelBinaan = Rombel::where('wali_kelas_id', $guru?->id)->first();

        if (!$rombelBinaan) {
            return back()->with('error', 'Anda tidak terdaftar sebagai wali kelas.');
        }

        $bulan = $request->query('bulan', Carbon::now()->month);
        $tahun = $request->query('tahun', Carbon::now()->year);
        $namaBulan = Carbon::createFromDate($tahun, (int)$bulan, 1)->translatedFormat('F Y');

        $siswas = Siswa::where('rombel_id', $rombelBinaan->id)->orderBy('nama_lengkap')->get();

        $presensiData = PresensiHarian::whereIn('siswa_id', $siswas->pluck('id'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy('siswa_id');

        // Menggunakan view cetak HTML khusus siap print / DomPDF
        return view('walikelas.presensi.pdf', [
            'siswas'       => $siswas,
            'presensiData' => $presensiData,
            'rombelBinaan' => $rombelBinaan,
            'namaBulan'    => $namaBulan,
            'guru'         => $guru,
        ]);
    }
}
