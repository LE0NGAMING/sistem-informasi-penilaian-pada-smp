<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MapelController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran.
     */
    public function index(Request $request): View
    {
        $mapels = Mapel::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('kode_mapel', 'like', "%{$search}%")
                        ->orWhere('nama_mapel', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kelompok'), fn($q) => $q->where('kelompok', $request->kelompok))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.mapel.index', compact('mapels'));
    }

    /**
     * Form tambah mata pelajaran baru.
     */
    public function create(): View
    {
        return view('admin.mapel.create');
    }

    /**
     * Menyimpan mata pelajaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', 'unique:mapel,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kelompok'   => ['nullable', 'string', 'max:50'],
        ], [
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
        ]);

        Mapel::create($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik mata pelajaran.
     */
    public function show(Mapel $mapel): View
    {
        return view('admin.mapel.show', compact('mapel'));
    }

    /**
     * Form edit mata pelajaran.
     */
    public function edit(Mapel $mapel): View
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    /**
     * Memperbarui data mata pelajaran di database.
     */
    public function update(Request $request, Mapel $mapel): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', Rule::unique('mapel', 'kode_mapel')->ignore($mapel->id)],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kelompok'   => ['nullable', 'string', 'max:50'],
        ], [
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
        ]);

        $mapel->update($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran dari database.
     */
    public function destroy(Mapel $mapel): RedirectResponse
    {
        $mapel->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
