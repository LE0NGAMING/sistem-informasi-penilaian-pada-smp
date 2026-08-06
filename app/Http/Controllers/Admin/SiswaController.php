<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'user']);

        // Filter Pencarian NIS, NISN, atau Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        $siswas = $query->latest()->paginate(10)->withQueryString();
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
            'nis'           => 'required|string|max:20|unique:siswa,nis',
            'nisn'          => 'nullable|string|size:10|unique:siswa,nisn',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'nullable|string|min:8', // Opsional, default jika kosong
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat User Akun Siswa
            $user = User::create([
                'name'     => $validated['nama_lengkap'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'siswa123'), // Gunakan input password atau fallback default
                'role'     => 'siswa',
            ]);

            // 2. Buat Data Siswa
            Siswa::create([
                'user_id'       => $user->id,
                'kelas_id'      => $validated['kelas_id'],
                'nis'           => $validated['nis'],
                'nisn'          => $validated['nisn'] ?? null,
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'agama'         => $validated['agama'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
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
            'nis'           => ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($siswa->id)],
            'nisn'          => ['nullable', 'string', 'size:10', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($siswa->user_id)],
            'password'      => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $siswa) {
            // 1. Update User Akun
            if ($siswa->user) {
                $userData = [
                    'name'  => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $siswa->user->update($userData);
            }

            // 2. Update Data Siswa
            $siswa->update([
                'kelas_id'      => $validated['kelas_id'],
                'nis'           => $validated['nis'],
                'nisn'          => $validated['nisn'] ?? null,
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'agama'         => $validated['agama'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            // Hapus akun user terkait terlebih dahulu
            if ($siswa->user) {
                $siswa->user()->delete();
            }
            $siswa->delete();
        });

        return back()->with('success', 'Data siswa beserta akun berhasil dihapus.');
    }
}
