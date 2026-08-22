@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Data Guru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola seluruh data tenaga pengajar sekolah.</p>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.guru.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm transition">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Data</span>
        </a>
        @endif
    </div>

    {{-- Filter & Pencarian --}}
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.guru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            {{-- Input Search --}}
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="bi bi-search"></i>
                </div>
                <input type="text"
                    name="search"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                    placeholder="Cari NIP, NUPTK, atau Nama Guru..."
                    value="{{ request('search') }}">
            </div>

            {{-- Dropdown Mapel --}}
            <div class="md:col-span-4">
                <select name="mapel_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}" @selected(request('mapel_id')==$mapel->id)>
                        {{ $mapel->nama_mapel }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'mapel_id']))
                <a href="{{ route('admin.guru.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-lg transition" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold border-b border-slate-200">
                    <tr>
                        <th class="pl-6 pr-3 py-3.5 whitespace-nowrap w-12">No</th>
                        <th class="px-3 py-3.5 whitespace-nowrap w-16">Foto</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">NIP / NUPTK</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Jenis Kelamin</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tanggal Lahir</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Mapel Utama</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">No. WA/HP</th>
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <th class="pr-6 pl-4 py-3.5 text-center whitespace-nowrap w-24">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 align-middle">
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="pl-6 pr-3 py-3.5 font-medium text-slate-400 whitespace-nowrap">{{ $gurus->firstItem() + $loop->index }}</td>

                        {{-- Foto Profil --}}
                        <td class="px-3 py-3.5 whitespace-nowrap">
                            @if($guru->foto_path)
                            <img src="{{ Storage::url($guru->foto_path) }}" alt="{{ $guru->nama_lengkap }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                            @else
                            <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold flex items-center justify-center text-xs">
                                {{ str($guru->nama_lengkap)->substr(0, 1)->upper() }}
                            </div>
                            @endif
                        </td>

                        <td class="px-4 py-3.5 font-mono text-xs text-slate-500 whitespace-nowrap">{{ $guru->nip ?? '-' }}</td>
                        <td class="px-4 py-3.5 font-semibold text-slate-800 whitespace-nowrap">{{ $guru->nama_lengkap }}</td>

                        {{-- Badge JK --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if(str($guru->jenis_kelamin?->value ?? $guru->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
                                Laki-laki
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-700 border border-pink-200">
                                Perempuan
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                            {{ is_string($guru->tanggal_lahir) ? $guru->tanggal_lahir : ($guru->tanggal_lahir?->translatedFormat('d F Y') ?? '-') }}
                        </td>
                        <td class="px-4 py-3.5 text-slate-700 font-medium whitespace-nowrap">{{ $guru->mapel?->nama_mapel ?? '-' }}</td>
                        <td class="px-4 py-3.5 text-slate-500 text-xs font-mono whitespace-nowrap">{{ $guru->no_hp ?? '-' }}</td>

                        {{-- Aksi --}}
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <td class="pr-6 pl-4 py-3.5 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.guru.edit', $guru) }}"
                                    class="p-1.5 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition"
                                    title="Edit Data">
                                    <i class="bi bi-pencil-square text-base"></i>
                                </a>
                                <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
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
                        <td colspan="9" class="text-center py-12 text-slate-400">
                            <i class="bi bi-inbox text-4xl block mb-2 opacity-60"></i>
                            <p class="text-sm">Belum ada data guru yang tersimpan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
        <div class="p-4 bg-white border-t border-slate-100">
            {{ $gurus->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection