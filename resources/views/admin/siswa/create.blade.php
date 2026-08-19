@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- 1. HEADER HALAMAN & TOMBOL KEMBALI -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Tambah Data Siswa</h3>
            <p class="text-slate-500 text-sm">Input identitas lengkap siswa beserta akun penggunanya.</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-medium text-sm px-4 py-2.5 rounded-lg transition shadow-sm shrink-0">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- 2. NOTIFIKASI ERROR VALIDASI -->
    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm space-y-2">
        <div class="flex items-center gap-2 font-bold text-red-800">
            <i class="bi bi-exclamation-triangle-fill text-lg"></i>
            <span>Terjadi kesalahan! Mohon periksa kembali inputan Anda.</span>
        </div>
        <ul class="list-disc list-inside pl-2 space-y-1 text-red-600 text-xs">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- 3. FORM INPUT DATA SISWA -->
    <x-card class="p-6">
        <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- SEKSI 1: INFORMASI IDENTITAS -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="bi bi-person-vcard text-lg"></i>
                    </div>
                    <h5 class="text-base font-bold text-slate-800">Informasi Identitas</h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <!-- NIS -->
                    <div class="md:col-span-6">
                        <label for="nis" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            NIS (Lokal) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nis" id="nis"
                            class="w-full bg-slate-50 border @error('nis') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('nis') }}" placeholder="Contoh: 2526001" required>
                        @error('nis')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NISN -->
                    <div class="md:col-span-6">
                        <label for="nisn" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            NISN (Nasional 10 Digit)
                        </label>
                        <input type="text" name="nisn" id="nisn"
                            class="w-full bg-slate-50 border @error('nisn') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('nisn') }}" placeholder="Contoh: 0081234567" maxlength="10">
                        @error('nisn')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="md:col-span-8">
                        <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap"
                            class="w-full bg-slate-50 border @error('nama_lengkap') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap siswa" required>
                        @error('nama_lengkap')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="md:col-span-4">
                        <label for="jenis_kelamin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full bg-slate-50 border @error('jenis_kelamin') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 transition" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="md:col-span-4">
                        <label for="tempat_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tempat Lahir
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                            class="w-full bg-slate-50 border @error('tempat_lahir') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta">
                        @error('tempat_lahir')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="md:col-span-4">
                        <label for="tanggal_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                            class="w-full bg-slate-50 border @error('tanggal_lahir') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div class="md:col-span-4">
                        <label for="agama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Agama
                        </label>
                        <select name="agama" id="agama"
                            class="w-full bg-slate-50 border @error('agama') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 transition">
                            <option value="">-- Pilih Agama --</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                            <option value="{{ $agm }}" {{ old('agama') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                        @error('agama')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-12">
                        <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Lengkap
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full bg-slate-50 border @error('alamat') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            placeholder="Jl. Contoh No. 123, RT/RW 01/02...">{{ old('alamat') }}</textarea>
                        @error('alamat')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEKSI 2: AKUN LOGIN -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="bi bi-shield-lock text-lg"></i>
                    </div>
                    <h5 class="text-base font-bold text-slate-800">Akun Login</h5>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <!-- Email -->
                    <div class="md:col-span-6">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            class="w-full bg-slate-50 border @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            value="{{ old('email') }}" placeholder="siswa@smp.sch.id" required>
                        @error('email')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-6">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full bg-slate-50 border @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-slate-200 focus:ring-blue-500/20 focus:border-blue-500 @enderror rounded-lg px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition"
                            placeholder="Minimal 8 karakter" required>
                        @error('password')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- TOMBOL SIMPAN / BATAL -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.siswa.index') }}"
                    class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium text-sm px-5 py-2.5 rounded-lg transition shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition shadow-sm">
                    Simpan
                </button>
            </div>

        </form>
    </x-card>

</div>
@endsection