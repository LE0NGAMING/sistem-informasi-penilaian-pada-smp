<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        $query = Rombel::with(['kelas', 'waliKelas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_rombel', 'like', "%{$search}%")
                    ->orWhereHas('waliKelas', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $rombels = $query->latest()->paginate(10)->withQueryString();
        return view('admin.rombel.index', compact('rombels'));
    }

    public function create()
    {
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        $gurus   = Guru::orderBy('nama_lengkap', 'asc')->get();
        //$tahunAjarans = TahunAjaran::orderBy('id', 'desc')->get();
        $tahunAjarans = TahunAjaran::all();
        return view('admin.rombel.create', compact('kelases', 'gurus', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', 1)->first();

        if ($tahunAjaranAktif) {
            $request->merge([
                'tahun_ajaran_id' => $tahunAjaranAktif->id
            ]);
        }

        $validated = $request->validate([
            'nama_rombel'   => 'required|string|max:50',
            'tingkat'      => 'required|string',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ]);

        Rombel::create($validated);

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombongan belajar berhasil ditambahkan.');
    }

    public function edit(Rombel $rombel)
    {
        $kelases = Kelas::orderBy('tingkat', 'asc')->get();
        $gurus   = Guru::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.rombel.edit', compact('rombel', 'kelases', 'gurus'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $validated = $request->validate([
            'nama_rombel'   => 'required|string|max:50',
            'tingkat'      => 'required|string',
            'wali_kelas_id' => 'nullable|exists:guru,id',
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
