@extends('layouts.app') {{-- Sesuaikan nama layout Anda --}}

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tambah Data Guru</h3>
            <p class="text-muted small mb-0">Tambahkan informasi profil dan akun guru baru.</p>
        </div>
        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alert Error Validasi Global --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan!</strong> Mohon periksa kembali inputan Anda.
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    {{-- NIP / NUPTK --}}
                    <div class="col-md-6">
                        <label for="nip" class="form-label fw-semibold">NIP / NUPTK</label>
                        <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Contoh: 198501012010011001">
                        @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap tanpa gelar" required>
                        @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gelar --}}
                    <div class="col-md-6">
                        <label for="gelar" class="form-label fw-semibold">Gelar Akademik</label>
                        <input type="text" name="gelar" id="gelar" class="form-control @error('gelar') is-invalid @enderror" value="{{ old('gelar') }}" placeholder="Contoh: S.Kom., M.Pd.">
                        @error('gelar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor HP / WhatsApp --}}
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890">
                        @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dropdown Bidang Mata Pelajaran --}}
                    <div class="col-md-6">
                        <label for="mapel_id" class="form-label fw-semibold">Bidang Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapelList as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Mulai Mengajar --}}
                    <div class="col-md-3">
                        <label for="tanggal_mulai_mengajar" class="form-label fw-semibold">Awal Masuk Mengajar</label>
                        <input type="date" name="tanggal_mulai_mengajar" id="tanggal_mulai_mengajar" class="form-control @error('tanggal_mulai_mengajar') is-invalid @enderror" value="{{ old('tanggal_mulai_mengajar') }}">
                        @error('tanggal_mulai_mengajar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Pensiun --}}
                    <div class="col-md-3">
                        <label for="tanggal_pensiun" class="form-label fw-semibold">Tanggal Pensiun</label>
                        <input type="date" name="tanggal_pensiun" id="tanggal_pensiun" class="form-control @error('tanggal_pensiun') is-invalid @enderror" value="{{ old('tanggal_pensiun') }}">
                        @error('tanggal_pensiun')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Akun --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Akun <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Contoh: guru@sekolah.sch.id" required>
                        <div class="form-text">Email ini akan digunakan untuk login. Password default: <code>guru123</code></div>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto Profil --}}
                    <div class="col-md-6">
                        <label for="foto_path" class="form-label fw-semibold">Foto Profil</label>
                        <input type="file" name="foto_path" id="foto_path" class="form-control @error('foto_path') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP (Maksimal 2MB)</div>
                        @error('foto_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Data Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection