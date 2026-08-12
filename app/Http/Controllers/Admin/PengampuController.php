<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Pengampu;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengampuController extends Controller
{
    /**
     * Menampilkan daftar seluruh penugasan mengajar guru.
     */
    public function index(): View
    {
        $pengampus = Pengampu::with(['guru', 'mapel', 'rombel', 'tahunAjaran'])
            ->latest()
            ->paginate(15);

        return view('admin.pengampu.index', compact('pengampus'));
    }

    /**
     * Menampilkan form tambah penugasan.
     */
    public function create(): View
    {
        return view('admin.pengampu.create', [
            'guruList'        => Guru::orderBy('nama_lengkap')->get(),
            'mapelList'       => Mapel::orderBy('nama_mapel')->get(),
            'rombelList'      => Rombel::orderBy('nama_rombel')->get(),
            'tahunAjaranList' => TahunAjaran::orderBy('tahun', 'desc')->get(),
        ]);
    }

    /**
     * Menyimpan data penugasan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'        => ['required', 'exists:guru,id'],
            'mapel_id'       => ['required', 'exists:mapel,id'],
            'rombel_id'      => ['required', 'exists:rombel,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
        ]);

        // Cegah duplikasi penugasan yang persis sama di tahun ajaran yang sama
        $exists = Pengampu::where($validated)->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Penugasan untuk guru, mapel, dan rombel tersebut sudah ada pada tahun ajaran ini.');
        }

        Pengampu::create($validated);

        return redirect()
            ->route('admin.pengampu.index')
            ->with('success', 'Penugasan mengajar berhasil ditambahkan!');
    }

    /**
     * Menghapus penugasan.
     */
    public function destroy(Pengampu $pengampu): RedirectResponse
    {
        $pengampu->delete();

        return redirect()
            ->route('admin.pengampu.index')
            ->with('success', 'Penugasan mengajar berhasil dihapus!');
    }

    public function edit(Pengampu $pengampu): View
    {
        return view('admin.pengampu.edit', [
            'pengampu'        => $pengampu,
            'guruList'        => Guru::orderBy('nama_lengkap')->get(),
            'mapelList'       => Mapel::orderBy('nama_mapel')->get(),
            'rombelList'      => Rombel::orderBy('nama_rombel')->get(),
            'tahunAjaranList' => TahunAjaran::orderBy('tahun', 'desc')->get(),
        ]);
    }

    public function update(Request $request, Pengampu $pengampu): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'         => ['required', 'exists:guru,id'],
            'mapel_id'        => ['required', 'exists:mapel,id'],
            'rombel_id'       => ['required', 'exists:rombel,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
        ]);

        // Cegah duplikasi, abaikan ID data yang sedang diedit ini sendiri
        $exists = Pengampu::where($validated)
            ->where('id', '!=', $pengampu->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Penugasan untuk guru, mapel, dan rombel tersebut sudah ada pada tahun ajaran ini.');
        }

        $pengampu->update($validated);

        return redirect()
            ->route('admin.pengampu.index')
            ->with('success', 'Penugasan mengajar berhasil diperbarui!');
    }
}
