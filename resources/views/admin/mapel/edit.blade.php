@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Mata Pelajaran</h3>
            <p class="text-muted small mb-0">Perbarui informasi mata pelajaran.</p>
        </div>
        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Kode Mapel --}}
                    <div class="col-md-6">
                        <label for="kode_mapel" class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="kode_mapel" id="kode_mapel" class="form-control @error('kode_mapel') is-invalid @enderror" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required>
                        @error('kode_mapel')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Mapel --}}
                    <div class="col-md-6">
                        <label for="nama_mapel" class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mapel" id="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                        @error('nama_mapel')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kelompok Mapel --}}
                    <div class="col-md-12">
                        <label for="kelompok" class="form-label fw-semibold">Kelompok / Kategori</label>
                        <select name="kelompok" id="kelompok" class="form-select @error('kelompok') is-invalid @enderror">
                            <option value="">-- Pilih Kelompok (Opsional) --</option>
                            <option value="Kelompok A (Wajib)" {{ old('kelompok', $mapel->kelompok) == 'Kelompok A (Wajib)' ? 'selected' : '' }}>Kelompok A (Wajib)</option>
                            <option value="Kelompok B" {{ old('kelompok', $mapel->kelompok) == 'Kelompok B' ? 'selected' : '' }}>Kelompok B</option>
                            <option value="Muatan Lokal" {{ old('kelompok', $mapel->kelompok) == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal</option>
                        </select>
                        @error('kelompok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Perbarui Mapel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection