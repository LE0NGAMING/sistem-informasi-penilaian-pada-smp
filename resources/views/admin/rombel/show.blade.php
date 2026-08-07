@extends('layouts.app')

@section('title', 'Detail Rombel')

@section('content')
<div class="container-fluid px-0">

    {{-- Alert Notifikasi --}}
    @session('success')
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    {{-- Card Informasi Rombel --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Rombel
            </h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Nama Rombel</small>
                    <span class="fs-5 fw-bold text-dark">{{ $rombel->nama_rombel }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Tingkat Kelas</small>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $rombel->tingkat }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Wali Kelas</small>
                    <span class="fw-semibold text-dark">{{ $rombel->waliKelas?->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Tahun Ajaran</small>
                    <span class="fw-bold text-dark">{{ $rombel->tahunAjaran?->tahun ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Daftar Siswa Dalam Rombel --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Daftar Siswa Dalam Rombel</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary rounded-pill px-3 py-2">Total: {{ $siswaList->count() }} Siswa</span>
                <button type="button" class="btn btn-primary rounded-3 btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalPlottingSiswa">
                    <i class="bi bi-person-plus me-1"></i> Plotting Siswa
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th style="width: 180px;">NISN</th>
                            <th>Nama Lengkap</th>
                            <th style="width: 180px;">Jenis Kelamin</th>
                            <th class="pe-4 text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $siswa->nisn ?? '-' }}</td>
                            <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td>
                                {{ str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']) ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.rombel.unplot-siswa', [$rombel, $siswa]) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini dari rombel?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-3" title="Keluarkan Siswa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x display-6 d-block mb-2 text-secondary"></i>
                                <span>Belum ada siswa yang terdaftar di rombel ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Plotting Siswa --}}
<div class="modal fade" id="modalPlottingSiswa" tabindex="-1" aria-labelledby="modalPlottingSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.rombel.plot-siswa', $rombel) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalPlottingSiswaLabel">
                        <i class="bi bi-person-plus text-primary me-2"></i>Tambah Siswa ke Rombel {{ $rombel->nama_rombel }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Pilih siswa tanpa rombel yang akan dimasukkan ke rombel ini:</p>

                    @if($siswaTersedia->isNotEmpty())
                    <div class="mb-3 d-flex align-items-center justify-content-between bg-light p-2 rounded-3">
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="checkbox" id="selectAllSiswa">
                            <label class="form-check-label fw-semibold small" for="selectAllSiswa">Pilih Semua</label>
                        </div>
                        <span class="badge bg-info text-dark rounded-pill">{{ $siswaTersedia->count() }} Siswa Tersedia</span>
                    </div>

                    <div class="list-group rounded-3" style="max-height: 350px; overflow-y: auto;">
                        @foreach($siswaTersedia as $siswa)
                        <label class="list-group-item d-flex align-items-center justify-content-between gap-3 py-2 px-3 border-light">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input checkbox-siswa mt-0" type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                </div>
                            </div>
                            <span class="badge bg-light text-secondary border">
                                {{ str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']) ? 'L' : 'P' }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                        Semua siswa terdaftar sudah memiliki rombel.
                    </div>
                    @endif
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    @if($siswaTersedia->isNotEmpty())
                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                        <i class="bi bi-save me-1"></i> Simpan Plotting
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('selectAllSiswa');
        const checkboxes = document.querySelectorAll('.checkbox-siswa');

        if (selectAll) {
            selectAll.addEventListener('change', (e) => {
                checkboxes.forEach(cb => cb.checked = e.target.checked);
            });
        }
    });
</script>
@endpush