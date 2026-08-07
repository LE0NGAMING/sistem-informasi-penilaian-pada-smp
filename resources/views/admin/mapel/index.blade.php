@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Data Mata Pelajaran</h3>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
        @endif
    </div>

    {{-- Alert Notifikasi --}}
    @session('success')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    @session('error')
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    {{-- Filter & Pencarian --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.mapel.index') }}" method="GET" class="row g-2 align-items-center">
                {{-- Input Pencarian Kode / Nama --}}
                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Cari Kode atau Nama Mapel..."
                            value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Dropdown Kelompok Mapel --}}
                <div class="col-md-4 col-lg-3">
                    <select name="kelompok" class="form-select">
                        <option value="">-- Semua Kelompok --</option>
                        <option value="Kelompok A" @selected(request('kelompok')==='Kelompok A' )>Kelompok A (Umum)</option>
                        <option value="Kelompok B" @selected(request('kelompok')==='Kelompok B' )>Kelompok B (Muatan Lokal/Seni)</option>
                        <option value="Kelompok C" @selected(request('kelompok')==='Kelompok C' )>Kelompok C (Peminatan)</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-3">Cari</button>
                    @if(request()->hasAny(['search', 'kelompok']))
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary px-3" title="Reset Filter">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data Mapel --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="width: 150px;">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kelompok</th>
                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <th class="text-center pe-4" style="width: 150px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapels as $mapel)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">
                                {{ $mapels->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 font-monospace fs-7">
                                    {{ $mapel->kode_mapel }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $mapel->nama_mapel }}</span>
                            </td>
                            <td>
                                @if($mapel->kelompok)
                                <span class="badge bg-info-subtle text-info px-2 py-1 fs-7">
                                    {{ $mapel->kelompok }}
                                </span>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.mapel.edit', $mapel) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-nama="{{ $mapel->nama_mapel }}" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH ? 5 : 4 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-bookmark fs-1 d-block mb-2"></i>
                                Belum ada data mata pelajaran yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($mapels->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $mapels->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- SweetAlert2 Script --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const nama = this.getAttribute('data-nama') || 'mata pelajaran ini';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Mata pelajaran <strong>${nama}</strong> akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 border-0 shadow-lg',
                        confirmButton: 'btn btn-danger px-4 py-2 me-2',
                        cancelButton: 'btn btn-secondary px-4 py-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection