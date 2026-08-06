@extends('layouts.app') {{-- Sesuaikan nama layout Anda --}}

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Rombongan Belajar (Rombel)</h3>
            <p class="text-muted small mb-0">Perbarui data rombel, tingkat kelas, atau wali kelas.</p>
        </div>
        <a href="{{ route('admin.rombel.index') }}" class="btn btn-outline-secondary btn-sm">
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
            <form action="{{ route('admin.rombel.update', $rombel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nama Rombel --}}
                    <div class="col-md-6">
                        <label for="nama_rombel" class="form-label fw-semibold">Nama Rombel <span class="text-danger">*</span></label>
                        <input type="text"
                            name="nama_rombel"
                            id="nama_rombel"
                            class="form-control text-uppercase @error('nama_rombel') is-invalid @enderror"
                            value="{{ old('nama_rombel') }}"
                            oninput="this.value = this.value.toUpperCase()"
                            placeholder="Contoh: 8-A"
                            required>
                        @error('nama_rombel')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dropdown Tingkat Kelas --}}
                    <div class="col-md-6 mb-3">
                        <label for="tingkat" class="form-label fw-semibold">Tingkat Kelas <span class="text-danger">*</span></label>
                        <select name="tingkat" id="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                            <option value="">-- Pilih Tingkat Kelas --</option>
                            <option value="7" {{ old('tingkat') == '7' ? 'selected' : '' }}>Kelas 7</option>
                            <option value="8" {{ old('tingkat') == '8' ? 'selected' : '' }}>Kelas 8</option>
                            <option value="9" {{ old('tingkat') == '9' ? 'selected' : '' }}>Kelas 9</option>
                        </select>
                        @error('tingkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dropdown Wali Kelas --}}
                    <div class="col-md-12">
                        <label for="wali_kelas_id" class="form-label fw-semibold">Wali Kelas</label>
                        <select name="wali_kelas_id" id="wali_kelas_id" class="form-select @error('wali_kelas_id') is-invalid @enderror">
                            <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                            @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $rombel->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama_lengkap }}{{ $guru->gelar ? ', ' . $guru->gelar : '' }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Bisa dikosongkan jika belum ada wali kelas.</div>
                        @error('wali_kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.rombel.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Perbarui Data Rombel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection