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
}
