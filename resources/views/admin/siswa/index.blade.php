@extends('layouts.app')

@section('title', 'Daftar Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- 1. HEADER HALAMAN & TOMBOL AKSI -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Daftar Data Siswa</h3>
            <p class="text-slate-500 text-sm">Kelola informasi biodata dan akun pengguna siswa.</p>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition shadow-sm shrink-0">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Data</span>
        </a>
        @endif
    </div>

    <!-- 2. FILTER & PENCARIAN -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
            <div class="flex flex-1 w-full rounded-lg border border-slate-200 bg-slate-50 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition overflow-hidden">
                <span class="inline-flex items-center pl-3.5 pr-1 text-slate-400 text-sm">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                    name="search"
                    class="w-full bg-transparent px-2 py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none"
                    placeholder="Cari Nama, NIS, atau NISN..."
                    value="{{ request('search') }}">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto shrink-0">
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm">
                    Cari
                </button>
                @if(request()->filled('search'))
                <a href="{{ route('admin.siswa.index') }}" class="w-full md:w-auto bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-lg transition text-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 3. TABEL DATA SISWA -->
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-center w-12">#</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">NIS / NISN</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Gender</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Email (Akun)</th>
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <th scope="col" class="px-6 py-3.5 font-semibold text-center w-28">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse ($siswas as $siswa)
                    <tr class="hover:bg-slate-50/60 transition">
                        <!-- Nomor -->
                        <td class="px-6 py-4 text-center font-medium text-slate-400">
                            {{ $siswas->firstItem() + $loop->index }}
                        </td>

                        <!-- NIS / NISN -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $siswa->nis ?? '-' }}</div>
                            <div class="text-xs text-slate-400">NISN: {{ $siswa->nisn ?? '-' }}</div>
                        </td>

                        <!-- Nama Lengkap & TTL -->
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}
                                {{ $siswa->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}
                            </div>
                        </td>

                        <!-- Gender Badge -->
                        <td class="px-6 py-4">
                            @php
                            $isLaki = str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']);
                            @endphp
                            @if($isLaki)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-200/60">
                                Laki-laki
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200/60">
                                Perempuan
                            </span>
                            @endif
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-slate-600">
                                <i class="bi bi-envelope text-slate-400"></i>
                                <span>{{ $siswa->user?->email ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Aksi -->
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.siswa.edit', $siswa) }}"
                                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                    title="Edit Data">
                                    <i class="bi bi-pencil-square text-base"></i>
                                </a>

                                <!-- Cukup gunakan class "delete-form" di sini -->
                                <form action="{{ route('admin.siswa.destroy', $siswa) }}"
                                    method="POST"
                                    class="delete-form inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus Data">
                                        <i class="bi bi-trash text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH ? 6 : 5 }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3 text-xl">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 text-base mb-1">Belum Ada Data Siswa</h6>
                                <p class="text-slate-400 text-xs max-w-sm">
                                    Data siswa tidak ditemukan atau belum ditambahkan ke dalam sistem.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($siswas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Menampilkan <span class="font-semibold text-slate-700">{{ $siswas->firstItem() }}</span> - <span class="font-semibold text-slate-700">{{ $siswas->lastItem() }}</span> dari <span class="font-semibold text-slate-700">{{ $siswas->total() }}</span> data
            </span>
            <div>
                {{ $siswas->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </x-card>

</div>
@endsection