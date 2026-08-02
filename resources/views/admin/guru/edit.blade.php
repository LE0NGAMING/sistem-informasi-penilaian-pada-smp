@extends('layouts.app') {{-- Sesuaikan nama layout Anda --}}

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Data Guru</h3>
            <p class="text-muted small mb-0">Perbarui informasi profil data guru.</p>
        </div>
        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- NIP / NUPTK --}}
                    <div class="col-md-6">
                        <label for="nip" class="form-label fw-semibold">NIP / NUPTK</label>
                        <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $guru->nip) }}" placeholder="Contoh: 198501012010011001">
                        @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                        @php
                        $jkValue = old('jenis_kelamin', $guru->jenis_kelamin->value ?? $guru->jenis_kelamin);
                        @endphp
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="L" {{ in_array(strtoupper(trim($jkValue)), ['L', 'LAKI-LAKI']) ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ in_array(strtoupper(trim($jkValue)), ['P', 'PEREMPUAN']) ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '') }}">
                        @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor HP / WhatsApp --}}
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $guru->no_hp) }}" placeholder="Contoh: 081234567890">
                        @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Akun (Read-only) --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Akun</label>
                        <input type="email" class="form-control bg-light" value="{{ $guru->user->email ?? '-' }}" disabled readonly>
                        <div class="form-text">Email akun user terhubung.</div>
                    </div>

                    {{-- Foto Profil --}}
                    <div class="col-md-12">
                        <label for="foto_path" class="form-label fw-semibold">Foto Profil</label>
                        <input type="file" name="foto_path" id="foto_path" class="form-control @error('foto_path') is-invalid @enderror" accept="image/*">
                        <div class="form-text mb-2">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP (Maksimal 2MB)</div>

                        @if($guru->foto_path)
                        <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light" style="width: fit-content;">
                            <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->nama_lengkap }}" class="rounded-circle object-fit-cover border" width="50" height="50">
                            <span class="text-muted small">Foto Profil Saat Ini</span>
                        </div>
                        @endif
                        @error('foto_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Perbarui Data Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection