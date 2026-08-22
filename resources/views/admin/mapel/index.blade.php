@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Mata Pelajaran</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola data mata pelajaran yang ada di sekolah.</p>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.mapel.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </a>
        @endif
    </div>

    {{-- Filter & Pencarian --}}
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            {{-- Input Pencarian Kode / Nama --}}
            <div class="relative flex-grow max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-slate-400"></i>
                </div>
                <input type="text" name="search"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                    placeholder="Cari Kode atau Nama Mapel..."
                    value="{{ request('search') }}">
            </div>

            {{-- Dropdown Kelompok Mapel --}}
            <div class="w-full sm:w-auto">
                <select name="kelompok" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">-- Semua Kelompok --</option>
                    <option value="Kelompok A" @selected(request('kelompok')==='Kelompok A' )>Kelompok A (Umum)</option>
                    <option value="Kelompok B" @selected(request('kelompok')==='Kelompok B' )>Kelompok B (Muatan Lokal/Seni)</option>
                    <option value="Kelompok C" @selected(request('kelompok')==='Kelompok C' )>Kelompok C (Peminatan)</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'kelompok']))
                <a href="{{ route('admin.mapel.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition" title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Data Mapel --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-medium border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16">No</th>
                        <th scope="col" class="px-6 py-4 w-40">Kode Mapel</th>
                        <th scope="col" class="px-6 py-4">Nama Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-4">Kelompok</th>
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <th scope="col" class="px-6 py-4 text-center w-32">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mapels as $mapel)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ $mapels->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 font-mono border border-slate-200">
                                {{ $mapel->kode_mapel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800">{{ $mapel->nama_mapel }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($mapel->kelompok)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $mapel->kelompok }}
                            </span>
                            @else
                            <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.mapel.edit', $mapel) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50 hover:text-amber-700 transition"
                                    title="Edit">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                <form action="{{ route('admin.mapel.destroy', $mapel) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-700 transition"
                                        title="Hapus">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH ? 5 : 4 }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                    <i class="bi bi-journal-bookmark text-2xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-medium mb-1">Belum ada data</h3>
                                <p class="text-slate-500 text-sm">Tidak ada mata pelajaran yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mapels->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $mapels->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection