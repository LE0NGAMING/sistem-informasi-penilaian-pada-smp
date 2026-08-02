@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="mb-4">
        <a href="{{ route('admin.kelas.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Kelas
        </a>
        <h3 class="fw-bold mt-2">Edit Data Kelas</h3>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                            @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tingkat" class="form-label fw-semibold">Tingkat / Jenjang <span class="text-danger">*</span></label>
                            <select name="tingkat" id="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                                <option value="7" {{ old('tingkat', $kelas->tingkat) == '7' ? 'selected' : '' }}>Tingkat 7</option>
                                <option value="8" {{ old('tingkat', $kelas->tingkat) == '8' ? 'selected' : '' }}>Tingkat 8</option>
                                <option value="9" {{ old('tingkat', $kelas->tingkat) == '9' ? 'selected' : '' }}>Tingkat 9</option>
                            </select>
                            @error('tingkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kelas.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-warning px-4"><i class="bi bi-pencil-square me-1"></i> Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection