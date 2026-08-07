<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Menampilkan daftar data guru.
     */
    public function index(Request $request): View
    {
        $gurus = Guru::with('mapel')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nip', 'like', "%{$search}%")
                        ->orWhere('nuptk', 'like', "%{$search}%")
                        ->orWhere('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('mapel_id'), fn($q) => $q->where('mapel_id', $request->mapel_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $mapels = Mapel::select('id', 'nama_mapel')->orderBy('nama_mapel')->get();

        return view('admin.guru.index', compact('gurus', 'mapels'));
    }

    /**
     * Form tambah guru baru.
     */
    public function create(): View
    {
        $mapelList = Mapel::select('id', 'nama_mapel')->orderBy('nama_mapel')->get();

        return view('admin.guru.create', compact('mapelList'));
    }

    /**
     * Menyimpan data guru baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip'                    => ['nullable', 'string', 'max:50', 'unique:guru,nip'],
            'nama_lengkap'           => ['required', 'string', 'max:255'],
            'gelar'                  => ['nullable', 'string', 'max:50'],
            'jenis_kelamin'          => ['required', 'in:L,P'],
            'tanggal_lahir'          => ['nullable', 'date'],
            'no_hp'                  => ['nullable', 'string', 'max:20'],
            'foto_path'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email'                  => ['required', 'email', 'max:255', 'unique:users,email'],
            'mapel_id'               => ['nullable', 'exists:mapel,id'],
            'tanggal_mulai_mengajar' => ['nullable', 'date'],
            'tanggal_pensiun'        => ['nullable', 'date', 'after_or_equal:tanggal_mulai_mengajar'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $fotoPath = $request->hasFile('foto_path')
                ? $request->file('foto_path')->store('guru', 'public')
                : null;

            $namaUser = $validated['nama_lengkap'] . ($validated['gelar'] ? ', ' . $validated['gelar'] : '');

            // 1. Buat Akun User
            $user = User::create([
                'name'     => $namaUser,
                'email'    => $validated['email'],
                'password' => Hash::make('guru123'),
                'role'     => RoleEnum::GURU->value,
            ]);

            // 2. Buat Profile Guru
            Guru::create([
                'user_id'                => $user->id,
                'nip'                    => $validated['nip'],
                'nama_lengkap'           => $validated['nama_lengkap'],
                'gelar'                  => $validated['gelar'],
                'jenis_kelamin'          => $validated['jenis_kelamin'],
                'tanggal_lahir'          => $validated['tanggal_lahir'],
                'no_hp'                  => $validated['no_hp'],
                'foto_path'              => $fotoPath,
                'mapel_id'               => $validated['mapel_id'],
                'tanggal_mulai_mengajar' => $validated['tanggal_mulai_mengajar'],
                'tanggal_pensiun'        => $validated['tanggal_pensiun'],
            ]);
        });

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik guru.
     */
    public function show(Guru $guru): View
    {
        $guru->load('user', 'mapel');

        return view('admin.guru.show', compact('guru'));
    }

    /**
     * Form edit data guru.
     */
    public function edit(Guru $guru): View
    {
        $guru->load('user', 'mapel');
        $mapelList = Mapel::select('id', 'nama_mapel')->orderBy('nama_mapel')->get();

        return view('admin.guru.edit', compact('guru', 'mapelList'));
    }

    /**
     * Memperbarui data guru di database.
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip'                    => ['nullable', 'string', 'max:50', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'nama_lengkap'           => ['required', 'string', 'max:255'],
            'gelar'                  => ['nullable', 'string', 'max:50'],
            'jenis_kelamin'          => ['required', 'in:L,P'],
            'tanggal_lahir'          => ['nullable', 'date'],
            'no_hp'                  => ['nullable', 'string', 'max:20'],
            'foto_path'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email'                  => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($guru->user_id)],
            'mapel_id'               => ['nullable', 'exists:mapel,id'],
            'tanggal_mulai_mengajar' => ['nullable', 'date'],
            'tanggal_pensiun'        => ['nullable', 'date', 'after_or_equal:tanggal_mulai_mengajar'],
        ]);

        DB::transaction(function () use ($request, $guru, $validated) {
            $fotoPath = $guru->foto_path;

            if ($request->hasFile('foto_path')) {
                if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
                    Storage::disk('public')->delete($guru->foto_path);
                }
                $fotoPath = $request->file('foto_path')->store('guru', 'public');
            }

            // Update Profile Guru
            $guru->update([
                'nip'                    => $validated['nip'],
                'nama_lengkap'           => $validated['nama_lengkap'],
                'gelar'                  => $validated['gelar'],
                'jenis_kelamin'          => $validated['jenis_kelamin'],
                'tanggal_lahir'          => $validated['tanggal_lahir'],
                'no_hp'                  => $validated['no_hp'],
                'foto_path'              => $fotoPath,
                'mapel_id'               => $validated['mapel_id'],
                'tanggal_mulai_mengajar' => $validated['tanggal_mulai_mengajar'],
                'tanggal_pensiun'        => $validated['tanggal_pensiun'],
            ]);

            // Update Akun User terkait
            if ($guru->user) {
                $namaUser = $validated['nama_lengkap'] . ($validated['gelar'] ? ', ' . $validated['gelar'] : '');
                $guru->user->update([
                    'name'  => $namaUser,
                    'email' => $validated['email'],
                ]);
            }
        });

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Menghapus data guru dan akun user terkait.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        DB::transaction(function () use ($guru) {
            if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
                Storage::disk('public')->delete($guru->foto_path);
            }

            $user = $guru->user;

            $guru->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
