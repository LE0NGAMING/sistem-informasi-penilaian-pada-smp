@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Rombongan Belajar (Rombel)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola kelompok kelas dan penugasan wali kelas.</p>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.rombel.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-plus-lg"></i> Tambah Rombel
        </a>
        @endif
    </div>

    {{-- Filter & Pencarian --}}
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.rombel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-slate-400"></i>
                </div>
                <input type="text" name="search"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                    placeholder="Cari Rombel atau Nama Wali Kelas..."
                    value="{{ request('search') }}">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('admin.rombel.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition" title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Rombel --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-medium border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16">No</th>
                        <th scope="col" class="px-6 py-4">Nama Rombel</th>
                        <th scope="col" class="px-6 py-4">Tingkat</th>
                        <th scope="col" class="px-6 py-4">Wali Kelas</th>
                        <th scope="col" class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rombels as $index => $rombel)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-500 font-medium">
                            {{ $rombels->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800">{{ $rombel->nama_rombel }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($rombel->tingkat)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                Kelas {{ $rombel->tingkat }}
                            </span>
                            @else
                            <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            {{ $rombel->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.rombel.show', $rombel->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-cyan-600 hover:bg-cyan-50 transition"
                                    title="Detail / Plotting Siswa">
                                    <i class="bi bi-people-fill text-lg"></i>
                                </a>
                                <a href="{{ route('admin.rombel.edit', $rombel->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50 transition"
                                    title="Edit">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                <form action="{{ route('admin.rombel.destroy', $rombel->id) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition"
                                        title="Hapus">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                    <i class="bi bi-collection text-2xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-medium mb-1">Belum ada data</h3>
                                <p class="text-slate-500 text-sm">Tidak ada rombongan belajar yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rombels->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $rombels->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection