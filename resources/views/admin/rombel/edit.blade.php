@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Rombongan Belajar</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui data rombel, tingkat kelas, atau wali kelas.</p>
        </div>
        <a href="{{ route('admin.rombel.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
        <div class="flex gap-3 items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg"></i>
            <p class="text-sm font-semibold text-red-800">Terjadi kesalahan validasi!</p>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.rombel.update', $rombel->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Nama Rombel --}}
                        <div class="w-full md:w-1/2">
                            <label for="nama_rombel" class="block text-sm font-medium text-slate-700 mb-2">Nama Rombel <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_rombel" id="nama_rombel"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama_rombel') border-red-500 @enderror"
                                value="{{ old('nama_rombel', $rombel->nama_rombel) }}"
                                oninput="this.value = this.value.toUpperCase()"
                                placeholder="Contoh: 8-A" required>
                            @error('nama_rombel') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tingkat Kelas --}}
                        <div class="w-full md:w-1/2">
                            <label for="tingkat" class="block text-sm font-medium text-slate-700 mb-2">Tingkat Kelas <span class="text-red-500">*</span></label>
                            <select name="tingkat" id="tingkat"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('tingkat') border-red-500 @enderror" required>
                                <option value="">-- Pilih Tingkat Kelas --</option>
                                <option value="7" {{ old('tingkat', $rombel->tingkat) == '7' ? 'selected' : '' }}>Kelas 7</option>
                                <option value="8" {{ old('tingkat', $rombel->tingkat) == '8' ? 'selected' : '' }}>Kelas 8</option>
                                <option value="9" {{ old('tingkat', $rombel->tingkat) == '9' ? 'selected' : '' }}>Kelas 9</option>
                            </select>
                            @error('tingkat') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Wali Kelas --}}
                    <div>
                        <label for="wali_kelas_id" class="block text-sm font-medium text-slate-700 mb-2">Wali Kelas</label>
                        <select name="wali_kelas_id" id="wali_kelas_id"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('wali_kelas_id') border-red-500 @enderror">
                            <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                            @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $rombel->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama_lengkap }}{{ $guru->gelar ? ', ' . $guru->gelar : '' }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-slate-400 text-xs mt-1.5">Bisa dikosongkan jika belum ada wali kelas.</p>
                        @error('wali_kelas_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.rombel.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        Perbarui Data Rombel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection