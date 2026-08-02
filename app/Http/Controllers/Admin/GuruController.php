<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->latest()->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'           => 'nullable|string|max:50|unique:guru,nip',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp'      => 'nullable|string|max:20',
            'foto_path'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'email'         => 'required|email|max:255|unique:users,email',
        ]);

        DB::transaction(function () use ($request) {
            // Process upload foto
            $fotoPath = null;
            if ($request->hasFile('foto_path')) {
                $fotoPath = $request->file('foto_path')->store('guru', 'public');
            }

            // 1. Buat User Account
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make('guru123'),
                'role'     => RoleEnum::GURU->value ?? 'guru',
            ]);

            // 2. Buat Profile Guru
            Guru::create([
                'user_id'       => $user->id,
                'nip'           => $request->nip,
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp'      => $request->no_hp,
                'foto_path'          => $fotoPath,
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip'           => ['nullable', 'string', 'max:50', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'no_hp'      => 'nullable|string|max:20',
            'foto_path'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $fotoPath = $guru->foto_path;

            // Jika mengupload foto baru
            if ($request->hasFile('foto_path')) {
                // Hapus foto lama dari storage jika ada
                if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
                    Storage::disk('public')->delete($guru->foto_path);
                }
                $fotoPath = $request->file('foto_path')->store('guru', 'public');
            }

            $guru->update([
                'nip'           => $request->nip,
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp'      => $request->no_hp,
                'foto_path'          => $fotoPath,
            ]);

            if ($guru->user) {
                $guru->user->update([
                    'name' => $request->nama_lengkap,
                ]);
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
                Storage::disk('public')->delete($guru->foto_path);
            }

            $guru->delete();

            if ($guru->user) {
                $guru->user->delete();
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
