<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Http\Requests\WaliKelas\StoreNilaiEkskulRequest;
use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkskul;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EkskulController extends Controller
{
    /**
     * Tampilkan halaman input nilai ekstrakurikuler siswa.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $guru = Auth::user()->guru;

        // 1. Otorisasi: Pastikan guru terdaftar sebagai Wali Kelas
        $rombel = Rombel::where('wali_kelas_id', $guru->id)->first();

        if (!$rombel) {
            return redirect()
                ->route('guru.dashboard')
                ->with('error', 'Akses ditolak! Anda tidak terdaftar sebagai Wali Kelas.');
        }

        // 2. Ambil Master Data Filter
        $tahunAjaranList = TahunAjaran::orderBy('tahun', 'desc')->get();
        $ekskulList      = Ekstrakurikuler::orderBy('nama_ekskul', 'asc')->get();

        // 3. Tangkap Parameter Filter (Default ke TA aktif/pertama dan semester ganjil)
        $tahunAjaranId = $request->query('tahun_ajaran_id', $tahunAjaranList->first()?->id);
        $semester      = $request->query('semester', 'ganjil');

        // 4. Eager Loading Siswa + Relasi NilaiEkskul (Cegah N+1 Query Problem)
        $siswaList = Siswa::query()
            ->where('rombel_id', $rombel->id)
            ->with(['nilaiEkskul' => function ($query) use ($tahunAjaranId, $semester) {
                $query->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('semester', $semester);
            }])
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('walikelas.ekskul.index', [
            'rombel'          => $rombel,
            'tahunAjaranList' => $tahunAjaranList,
            'ekskulList'      => $ekskulList,
            'siswaList'       => $siswaList,
            'tahunAjaranId'   => $tahunAjaranId,
            'semester'        => $semester,
        ]);
    }

    /**
     * Simpan / Update nilai ekstrakurikuler siswa per Rombel.
     */
    public function store(StoreNilaiEkskulRequest $request): RedirectResponse
    {
        $guru = Auth::user()->guru;
        $rombel = Rombel::where('wali_kelas_id', $guru->id)->first();

        if (!$rombel) {
            return redirect()->back()->with('error', 'Anda tidak memiliki otorisasi Wali Kelas.');
        }

        $validated = $request->validated();

        // Ambil daftar ID siswa sah di rombel ini untuk proteksi keamanan data
        $validSiswaIds = Siswa::where('rombel_id', $rombel->id)->pluck('id')->toArray();

        try {
            DB::transaction(function () use ($validated, $validSiswaIds) {
                foreach ($validated['nilai'] as $siswaId => $ekskulGroup) {

                    // Keamanan: Hanya proses siswa yang benar-benar anggota Rombel Wali Kelas ini
                    if (!in_array($siswaId, $validSiswaIds)) {
                        continue;
                    }

                    foreach ($ekskulGroup as $ekskulId => $detail) {
                        $predikat   = trim($detail['predikat'] ?? '');
                        $keterangan = trim($detail['keterangan'] ?? '');

                        // Jika predikat dan keterangan kosong, hapus record jika sebelumnya ada
                        if (empty($predikat) && empty($keterangan)) {
                            NilaiEkskul::where([
                                'siswa_id'          => $siswaId,
                                'ekstrakurikuler_id' => $ekskulId,
                                'tahun_ajaran_id'   => $validated['tahun_ajaran_id'],
                                'semester'          => $validated['semester'],
                            ])->delete();

                            continue;
                        }

                        // Upsert (Update or Create) Nilai Ekskul
                        NilaiEkskul::updateOrCreate(
                            [
                                'siswa_id'          => $siswaId,
                                'ekstrakurikuler_id' => $ekskulId,
                                'tahun_ajaran_id'   => $validated['tahun_ajaran_id'],
                                'semester'          => $validated['semester'],
                            ],
                            [
                                'predikat'   => strtoupper($predikat),
                                'keterangan' => $keterangan,
                            ]
                        );
                    }
                }
            });

            return redirect()
                ->route('walikelas.ekskul.index', [
                    'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                    'semester'        => $validated['semester'],
                ])
                ->with('success', 'Data Nilai Ekstrakurikuler berhasil disimpan!');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
