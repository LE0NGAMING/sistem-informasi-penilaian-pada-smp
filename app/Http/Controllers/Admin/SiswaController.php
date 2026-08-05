<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->latest()->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelases = Kelas::all();
        return view('admin.siswa.create', compact('kelases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'          => 'required|unique:siswa,nisn',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
            'email'         => 'required|email|unique:users,email',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat User Akun Siswa
            $user = User::create([
                'name'     => $validated['nama_lengkap'],
                'email'    => $validated['email'],
                'password' => Hash::make('siswa123'), // Password default
                'role'     => 'siswa',
            ]);

            // 2. Buat Data Siswa
            Siswa::create([
                'user_id'       => $user->id,
                'kelas_id'      => $validated['kelas_id'],
                'nisn'          => $validated['nisn'],
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete(); // Soft delete
        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function edit(Siswa $siswa)
    {
        $kelases = Kelas::all();
        $siswa->load('user', 'kelas');

        return view('admin.siswa.edit', compact('siswa', 'kelases'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn'          => 'required|unique:siswa,nisn,' . $siswa->id,
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
            'email'         => 'nullable|email|unique:users,email,' . $siswa->user_id, // Diubah ke nullable agar tidak gagal saat form tidak punya field email
        ]);

        DB::transaction(function () use ($validated, $siswa) {
            // 1. Update User Akun (jika relasi user ada)
            if ($siswa->user) {
                $userData = ['name' => $validated['nama_lengkap']];
                if (!empty($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                $siswa->user->update($userData);
            }

            // 2. Update Data Siswa
            $siswa->update([
                'kelas_id'      => $validated['kelas_id'],
                'nisn'          => $validated['nisn'],
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diupdate.');
    }
}
