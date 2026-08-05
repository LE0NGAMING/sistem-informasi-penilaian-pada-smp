<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Mapel;
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
        // Eager load 'user' dan 'mapel' agar efisien
        $gurus = Guru::with(['user', 'mapel'])->latest()->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        $mapelList = Mapel::orderBy('nama_mapel', 'asc')->get();
        return view('admin.guru.create', compact('mapelList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'                    => 'nullable|string|max:50|unique:guru,nip',
            'nama_lengkap'           => 'required|string|max:255',
            'gelar'                  => 'nullable|string|max:50',
            'jenis_kelamin'          => 'required|in:L,P',
            'tanggal_lahir'          => 'nullable|date',
            'no_hp'                  => 'nullable|string|max:20',
            'foto_path'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'email'                  => 'required|email|max:255|unique:users,email',
            'mapel_id'               => 'nullable|exists:mapel,id',
            'tanggal_mulai_mengajar' => 'nullable|date',
            'tanggal_pensiun'        => 'nullable|date|after_or_equal:tanggal_mulai_mengajar',
        ]);

        DB::transaction(function () use ($request) {
            // Process upload foto
            $fotoPath = null;
            if ($request->hasFile('foto_path')) {
                $fotoPath = $request->file('foto_path')->store('guru', 'public');
            }

            // Format nama user untuk login (termasuk gelar jika ada)
            $namaUser = $request->nama_lengkap . ($request->gelar ? ', ' . $request->gelar : '');

            // 1. Buat User Account
            $user = User::create([
                'name'     => $namaUser,
                'email'    => $request->email,
                'password' => Hash::make('guru123'),
                'role'     => RoleEnum::GURU->value ?? 'guru',
            ]);

            // 2. Buat Profile Guru
            Guru::create([
                'user_id'                => $user->id,
                'nip'                    => $request->nip,
                'nama_lengkap'           => $request->nama_lengkap,
                'gelar'                  => $request->gelar,
                'jenis_kelamin'          => $request->jenis_kelamin,
                'tanggal_lahir'          => $request->tanggal_lahir,
                'no_hp'                  => $request->no_hp,
                'foto_path'              => $fotoPath,
                'mapel_id'               => $request->mapel_id,
                'tanggal_mulai_mengajar' => $request->tanggal_mulai_mengajar,
                'tanggal_pensiun'        => $request->tanggal_pensiun,
            ]);
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user', 'mapel');
        $mapelList = Mapel::orderBy('nama_mapel', 'asc')->get();

        return view('admin.guru.edit', compact('guru', 'mapelList'));
    }

    public function update(Request $request, Guru $guru)
    {
        $userId = $guru->user_id;

        $request->validate([
            'nip'                    => ['nullable', 'string', 'max:50', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'nama_lengkap'           => 'required|string|max:255',
            'gelar'                  => 'nullable|string|max:50',
            'jenis_kelamin'          => 'required|in:L,P',
            'tanggal_lahir'          => 'nullable|date',
            'no_hp'                  => 'nullable|string|max:20',
            'foto_path'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'email'                  => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'mapel_id'               => 'nullable|exists:mapel,id',
            'tanggal_mulai_mengajar' => 'nullable|date',
            'tanggal_pensiun'        => 'nullable|date|after_or_equal:tanggal_mulai_mengajar',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $fotoPath = $guru->foto_path;

            // Jika mengupload foto baru
            if ($request->hasFile('foto_path')) {
                if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
                    Storage::disk('public')->delete($guru->foto_path);
                }
                $fotoPath = $request->file('foto_path')->store('guru', 'public');
            }

            // Update Profile Guru
            $guru->update([
                'nip'                    => $request->nip,
                'nama_lengkap'           => $request->nama_lengkap,
                'gelar'                  => $request->gelar,
                'jenis_kelamin'          => $request->jenis_kelamin,
                'tanggal_lahir'          => $request->tanggal_lahir,
                'no_hp'                  => $request->no_hp,
                'foto_path'              => $fotoPath,
                'mapel_id'               => $request->mapel_id,
                'tanggal_mulai_mengajar' => $request->tanggal_mulai_mengajar,
                'tanggal_pensiun'        => $request->tanggal_pensiun,
            ]);

            // Update Akun User terkait
            if ($guru->user) {
                $namaUser = $request->nama_lengkap . ($request->gelar ? ', ' . $request->gelar : '');
                $guru->user->update([
                    'name'  => $namaUser,
                    'email' => $request->email,
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
