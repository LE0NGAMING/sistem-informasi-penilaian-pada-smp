@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Data Guru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi profil dan akun guru.</p>
        </div>
        <a href="{{ route('admin.guru.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- Alert Error Validasi Global --}}
    @if ($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
        <div class="flex gap-3">
            <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-bold text-red-800">Terjadi kesalahan!</h3>
                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Baris 1: NIP & Nama Lengkap --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <label for="nip" class="block text-sm font-medium text-slate-700 mb-2">NIP / NUPTK</label>
                            <input type="text" name="nip" id="nip"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nip') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                value="{{ old('nip', $guru->nip) }}" placeholder="Contoh: 198501012010011001">
                            @error('nip') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2">
                            <label for="nama_lengkap" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('nama_lengkap') border-red-500 @enderror"
                                value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                            @error('nama_lengkap') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Gelar & Jenis Kelamin --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <label for="gelar" class="block text-sm font-medium text-slate-700 mb-2">Gelar Akademik</label>
                            <input type="text" name="gelar" id="gelar"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('gelar') border-red-500 @enderror"
                                value="{{ old('gelar', $guru->gelar) }}" placeholder="Contoh: S.Kom., M.Pd.">
                            @error('gelar') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            @php
                            $jkValue = old('jenis_kelamin', is_object($guru->jenis_kelamin) ? $guru->jenis_kelamin->value : $guru->jenis_kelamin);
                            @endphp
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('jenis_kelamin') border-red-500 @enderror" required>
                                <option value="L" {{ strtoupper($jkValue) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ strtoupper($jkValue) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 3: Tanggal Lahir & Nomor HP --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('tanggal_lahir') border-red-500 @enderror"
                                value="{{ old('tanggal_lahir', is_object($guru->tanggal_lahir) ? $guru->tanggal_lahir->format('Y-m-d') : $guru->tanggal_lahir) }}">
                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2">
                            <label for="no_hp" class="block text-sm font-medium text-slate-700 mb-2">Nomor WhatsApp / HP</label>
                            <input type="text" name="no_hp" id="no_hp"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('no_hp') border-red-500 @enderror"
                                value="{{ old('no_hp', $guru->no_hp) }}" placeholder="Contoh: 081234567890">
                            @error('no_hp') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 4: Mapel & Group Tanggal --}}
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <label for="mapel_id" class="block text-sm font-medium text-slate-700 mb-2">Bidang Mata Pelajaran</label>
                            <select name="mapel_id" id="mapel_id"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('mapel_id') border-red-500 @enderror">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapelList as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mapel_id', $guru->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                                @endforeach
                            </select>
                            @error('mapel_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2 flex flex-col sm:flex-row gap-6">
                            <div class="w-full sm:w-1/2">
                                <label for="tanggal_mulai_mengajar" class="block text-sm font-medium text-slate-700 mb-2">Awal Mengajar</label>
                                <input type="date" name="tanggal_mulai_mengajar" id="tanggal_mulai_mengajar"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('tanggal_mulai_mengajar') border-red-500 @enderror"
                                    value="{{ old('tanggal_mulai_mengajar', is_object($guru->tanggal_mulai_mengajar) ? $guru->tanggal_mulai_mengajar->format('Y-m-d') : $guru->tanggal_mulai_mengajar) }}">
                                @error('tanggal_mulai_mengajar') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-full sm:w-1/2">
                                <label for="tanggal_pensiun" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Pensiun</label>
                                <input type="date" name="tanggal_pensiun" id="tanggal_pensiun"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('tanggal_pensiun') border-red-500 @enderror"
                                    value="{{ old('tanggal_pensiun', is_object($guru->tanggal_pensiun) ? $guru->tanggal_pensiun->format('Y-m-d') : $guru->tanggal_pensiun) }}">
                                @error('tanggal_pensiun') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Baris 5: Email Akun --}}
                    <div class="w-full">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Akun <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('email') border-red-500 @enderror"
                            value="{{ old('email', $guru->user->email ?? '') }}" required>
                        @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Baris 6: Foto Profil --}}
                    <div class="w-full">
                        <label for="foto_path" class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
                        <input type="file" name="foto_path" id="foto_path"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('foto_path') border-red-500 @enderror"
                            accept="image/*">
                        <p class="text-slate-500 text-xs mt-1.5 mb-3">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP (Maksimal 2MB)</p>

                        @if($guru->foto_path)
                        <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg bg-slate-50 w-fit">
                            <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->nama_lengkap }}" class="w-12 h-12 rounded-full object-cover border border-slate-300">
                            <span class="text-sm text-slate-500 font-medium pr-2">Foto Saat Ini</span>
                        </div>
                        @endif
                        @error('foto_path') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="my-8 border-slate-200">

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.guru.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        Perbarui Data Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection