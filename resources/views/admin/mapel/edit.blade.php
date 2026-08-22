@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Mata Pelajaran</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi mata pelajaran.</p>
        </div>
        <a href="{{ route('admin.mapel.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Baris 1: Kode & Nama Mapel --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <label for="kode_mapel" class="block text-sm font-medium text-slate-700 mb-2">Kode Mapel <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_mapel" id="kode_mapel"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('kode_mapel') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required>
                            @error('kode_mapel') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2">
                            <label for="nama_mapel" class="block text-sm font-medium text-slate-700 mb-2">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_mapel" id="nama_mapel"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama_mapel') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                            @error('nama_mapel') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Kelompok Mapel --}}
                    <div class="w-full">
                        <label for="kelompok" class="block text-sm font-medium text-slate-700 mb-2">Kelompok / Kategori</label>
                        <select name="kelompok" id="kelompok"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('kelompok') border-red-500 @enderror">
                            <option value="">-- Pilih Kelompok (Opsional) --</option>
                            <option value="Kelompok A (Wajib)" {{ old('kelompok', $mapel->kelompok) == 'Kelompok A (Wajib)' ? 'selected' : '' }}>Kelompok A (Wajib)</option>
                            <option value="Kelompok B" {{ old('kelompok', $mapel->kelompok) == 'Kelompok B' ? 'selected' : '' }}>Kelompok B (Muatan Lokal/Seni)</option>
                            <option value="Kelompok C" {{ old('kelompok', $mapel->kelompok) == 'Kelompok C' ? 'selected' : '' }}>Kelompok C (Peminatan)</option>
                            <option value="Muatan Lokal" {{ old('kelompok', $mapel->kelompok) == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal Tambahan</option>
                        </select>
                        @error('kelompok') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Spasi dan Garis Pemisah yang Rapi --}}
                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.mapel.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        Perbarui Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection