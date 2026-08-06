<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        // Filter Pencarian Text (Kode Mapel atau Nama Mapel)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mapel', 'like', "%{$search}%")
                    ->orWhere('nama_mapel', 'like', "%{$search}%");
            });
        }

        // Filter Dropdown Kelompok Mapel (misal: Kelompok A, B, C / Umum, Peminatan)
        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }

        $mapels = $query->latest()->paginate(10)->withQueryString();

        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
        ], [
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
