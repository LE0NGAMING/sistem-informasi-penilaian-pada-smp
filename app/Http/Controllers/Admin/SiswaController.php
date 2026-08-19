<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar data siswa.
     */
    public function index(Request $request): View
    {
        // 1. Ganti 'kelas' menjadi 'rombel' atau 'rombel.kelas' (nested eager loading)
        $siswas = Siswa::with(['rombel.kelas', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Form tambah siswa baru.
     */
    public function create(): View
    {
        // 2. Ambil data rombel beserta relasi kelasnya
        // Sesuaikan 'nama_rombel' dengan nama kolom yang ada di tabel rombels
        $rombels = Rombel::with('kelas')->get();

        return view('admin.siswa.create', compact('rombels')); // Ubah passing variable
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis'           => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nisn'          => ['nullable', 'string', 'size:10', 'unique:siswa,nisn'],
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir'  => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama'         => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            'rombel_id'     => ['nullable', 'exists:rombels,id'], // 3. Tambahkan validasi rombel_id
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat Akun User Siswa
            $user = User::create([
                'name'     => $validated['nama_lengkap'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'siswa123'),
                'role'     => RoleEnum::SISWA->value ?? 'siswa',
            ]);

            // 2. Buat Profile Siswa
            Siswa::create([
                'user_id'       => $user->id,
                'nis'           => $validated['nis'],
                'nisn'          => $validated['nisn'] ?? null,
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'agama'         => $validated['agama'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
                'rombel_id'     => $validated['rombel_id'] ?? null, // 4. Simpan rombel_id
            ]);
        });

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik siswa.
     */
    public function show(Siswa $siswa): View
    {
        // 5. Sesuaikan relasi load
        $siswa->load('rombel.kelas', 'user');

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Form edit data siswa.
     */
    public function edit(Siswa $siswa): View
    {
        // 6. Gunakan data rombel untuk form edit
        $rombels = Rombel::with('kelas')->get();
        $siswa->load('user', 'rombel');

        return view('admin.siswa.edit', compact('siswa', 'rombels'));
    }

    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'nis'           => ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($siswa->id)],
            'nisn'          => ['nullable', 'string', 'size:10', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'rombel_id'     => ['nullable', 'exists:rombels,id'], // 7. Ganti kelas_id jadi rombel_id
            'tempat_lahir'  => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama'         => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($siswa->user_id)],
            'password'      => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated, $siswa) {
            // 1. Update Akun User
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
                'rombel_id'     => $validated['rombel_id'] ?? null, // 8. Update menggunakan rombel_id
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

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dan akun user terkait.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;

            $siswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa beserta akun berhasil dihapus.');
    }
}
