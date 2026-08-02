@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tambah Kelas Baru</h3>
            <p class="text-muted small mb-0">Isi formulir berikut untuk menambahkan data kelas baru.</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Nama Kelas --}}
                    <div class="col-md-6">
                        <label for="nama_kelas" class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}" placeholder="Contoh: VII-A, VIII B, IX-1" required>
                        @error('nama_kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tingkat --}}
                    <div class="col-md-6">
                        <label for="tingkat" class="form-label fw-semibold">Tingkat / Tingkatan <span class="text-danger">*</span></label>
                        <select name="tingkat" id="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Tingkat --</option>
                            <option value="7" {{ old('tingkat') == '7' ? 'selected' : '' }}>Kelas 7 (VII)</option>
                            <option value="8" {{ old('tingkat') == '8' ? 'selected' : '' }}>Kelas 8 (VIII)</option>
                            <option value="9" {{ old('tingkat') == '9' ? 'selected' : '' }}>Kelas 9 (IX)</option>
                        </select>
                        @error('tingkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Wali Kelas --}}
                    <div class="col-md-12">
                        <label for="guru_id" class="form-label fw-semibold">Wali Kelas</label>
                        <select name="guru_id" id="guru_id" class="form-select @error('guru_id') is-invalid @enderror">
                            <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                            @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama_lengkap }} {{ $guru->nip ? '('.$guru->nip.')' : '' }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Bisa dikosongkan jika wali kelas belum ditentukan.</div>
                        @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection