<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        // Mengambil rombel beserta relasi kelas dan wali kelas
        $rombels = Rombel::with(['kelas', 'waliKelas'])->latest()->paginate(10);
        return view('admin.rombel.index', compact('rombels'));
    }

    public function create()
    {
        $kelases = Kelas::all();
        $gurus = Guru::all();
        return view('admin.rombel.create', compact('kelases', 'gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'guru_id'      => 'nullable|exists:guru,id',
            'tahun_ajaran' => 'required|string|max:20', // Contoh: 2025/2026
            'semester'     => 'required|in:ganjil,genap',
        ]);

        Rombel::create($validated);

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil ditambahkan.');
    }

    public function edit(Rombel $rombel)
    {
        $kelases = Kelas::all();
        $gurus = Guru::all();
        return view('admin.rombel.edit', compact('rombel', 'kelases', 'gurus'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $validated = $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'guru_id'      => 'nullable|exists:guru,id',
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|in:ganjil,genap',
        ]);

        $rombel->update($validated);

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil diperbarui.');
    }

    public function destroy(Rombel $rombel)
    {
        $rombel->delete();

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil dihapus.');
    }
}
