<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelases = Kelas::withCount('siswa')->latest()->paginate(10);
        return view('admin.kelas.index', compact('kelases'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat'    => 'required|in:7,8,9',
        ], [
            'nama_kelas.unique' => 'Nama kelas ini sudah ada.',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        // Parameter otomatis $kela (sesuai penamaan bawaan route resource Laravel untuk 'kelas')
        $kelas = $kela;
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $kelas = $kela;
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $kelas->id,
            'tingkat'    => 'required|in:7,8,9',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
