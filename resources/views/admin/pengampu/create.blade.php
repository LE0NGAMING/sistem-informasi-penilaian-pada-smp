@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Penugasan Mengajar</h1>
        <p class="text-sm text-gray-600">Pilih kombinasi guru, mata pelajaran, dan kelas.</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
        <form action="{{ route('admin.pengampu.store') }}" method="POST">
            @csrf

            {{-- Pilih Guru --}}
            <div class="mb-4">
                <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru</label>
                <select name="guru_id" id="guru_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('guru_id') border-red-500 @enderror">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $guru)
                    <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
                @error('guru_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih Mata Pelajaran --}}
            <div class="mb-4">
                <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('mapel_id') border-red-500 @enderror">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                        {{ $mapel->nama_mapel }}
                    </option>
                    @endforeach
                </select>
                @error('mapel_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih Rombel / Kelas --}}
            <div class="mb-4">
                <label for="rombel_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Rombel / Kelas</label>
                <select name="rombel_id" id="rombel_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('rombel_id') border-red-500 @enderror">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombelList as $rombel)
                    <option value="{{ $rombel->id }}" {{ old('rombel_id') == $rombel->id ? 'selected' : '' }}>
                        {{ $rombel->nama_rombel }}
                    </option>
                    @endforeach
                </select>
                @error('rombel_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih Tahun Ajaran --}}
            <div class="mb-6">
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tahun_ajaran_id') border-red-500 @enderror">
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                        {{ $ta->tahun }}
                    </option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.pengampu.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition no-underline">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition no-underline">
                    Simpan Penugasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection