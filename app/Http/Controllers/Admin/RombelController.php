<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlotSiswaRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RombelController extends Controller
{
    /**
     * Menampilkan daftar Rombongan Belajar dengan pencarian dan paginasi.
     */
    public function index(Request $request): View
    {
        $rombels = Rombel::query()
            ->with(['kelas', 'waliKelas'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->input('search');
                $query->where(function ($q) use ($search): void {
                    $q->where('nama_rombel', 'like', "%{$search}%")
                        ->orWhereHas('waliKelas', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rombel.index', [
            'rombels' => $rombels,
        ]);
    }

    /**
     * Menampilkan form tambah Rombongan Belajar.
     */
    public function create(): View
    {
        return view('admin.rombel.create', [
            'kelases'      => Kelas::orderBy('nama_kelas')->get(),
            'gurus'        => Guru::orderBy('nama_lengkap')->get(),
            'tahunAjarans' => TahunAjaran::latest()->get(),
        ]);
    }

    /**
     * Menyimpan Rombongan Belajar baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

        if ($tahunAjaranAktif) {
            $request->merge([
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
            ]);
        }

        $validated = $request->validate([
            'nama_rombel'     => ['required', 'string', 'max:50'],
            'tingkat'         => ['required', 'string'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'wali_kelas_id'   => ['nullable', 'exists:guru,id'],
        ]);

        Rombel::create($validated);

        return to_route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail Rombongan Belajar beserta daftar siswa.
     */
    public function show(Rombel $rombel): View
    {
        // PERBAIKAN: Memuat relasi relasi secara bersih tanpa pemanggilan $rombel->load() bersarang
        $rombel->load([
            'waliKelas',
            'tahunAjaran',
            'siswa' => fn($query) => $query->orderBy('nama_lengkap'),
        ]);

        $siswaTersedia = Siswa::query()
            ->whereNull('rombel_id')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.rombel.show', [
            'rombel'        => $rombel,
            'siswaList'     => $rombel->siswa,
            'siswaTersedia' => $siswaTersedia,
        ]);
    }

    /**
     * Menampilkan form edit Rombongan Belajar.
     */
    public function edit(Rombel $rombel): View
    {
        return view('admin.rombel.edit', [
            'rombel'  => $rombel,
            'kelases' => Kelas::orderBy('tingkat')->get(),
            'gurus'   => Guru::orderBy('nama_lengkap')->get(),
        ]);
    }

    /**
     * Memperbarui data Rombongan Belajar di database.
     */
    public function update(Request $request, Rombel $rombel): RedirectResponse
    {
        $validated = $request->validate([
            'nama_rombel'   => ['required', 'string', 'max:50'],
            'tingkat'       => ['required', 'string'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
        ]);

        $rombel->update($validated);

        return to_route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil diperbarui.');
    }

    /**
     * Menghapus Rombongan Belajar dari database.
     */
    public function destroy(Rombel $rombel): RedirectResponse
    {
        $rombel->delete();

        return to_route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil dihapus.');
    }

    /**
     * Mengaitkan banyak siswa ke dalam Rombongal Belajar (Plotting).
     */
    public function plotSiswa(PlotSiswaRequest $request, Rombel $rombel): RedirectResponse
    {
        DB::transaction(function () use ($request, $rombel) {
            Siswa::whereIn('id', $request->validated('siswa_ids'))
                ->update(['rombel_id' => $rombel->id]);
        });

        return to_route('admin.rombel.show', $rombel)
            ->with('success', 'Siswa berhasil ditambahkan ke rombel ini!');
    }

    /**
     * Mengeluarkan siswa dari Rombongan Belajar.
     */
    public function unplotSiswa(Rombel $rombel, Siswa $siswa): RedirectResponse
    {
        $siswa->update(['rombel_id' => null]);

        return to_route('admin.rombel.show', $rombel)
            ->with('success', 'Siswa berhasil dikeluarkan dari rombel.');
    }
}
