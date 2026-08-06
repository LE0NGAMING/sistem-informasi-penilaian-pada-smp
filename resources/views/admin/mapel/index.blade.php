@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Data Mata Pelajaran</h3>
            <!-- <p class="text-muted small mb-0">Kelola daftar mata pelajaran yang diajarkan di sekolah.</p> -->
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
        @endif
    </div>

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
                        <option value="Kelompok A" {{ request('kelompok') == 'Kelompok A' ? 'selected' : '' }}>Kelompok A (Umum)</option>
                        <option value="Kelompok B" {{ request('kelompok') == 'Kelompok B' ? 'selected' : '' }}>Kelompok B (Muatan Lokal/Seni)</option>
                        <option value="Kelompok C" {{ request('kelompok') == 'Kelompok C' ? 'selected' : '' }}>Kelompok C (Peminatan)</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-3">Cari</button>
                    @if(request('search') || request('kelompok'))
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
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="width: 150px;">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kelompok</th>
                            @if(auth()->user()->role === 'admin')
                            <th class="text-end pe-4" style="width: 150px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapels as $index => $mapel)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">
                                {{ method_exists($mapels, 'firstItem') && $mapels->firstItem() ? $mapels->firstItem() + $index : $index + 1 }}
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
                            @if(auth()->user()->role === 'admin')
                            <td class="text-center">
                                <a href="{{ route('mapel.edit', $mapel->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('mapel.destroy', $mapel->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-bookmark fs-1 d-block mb-2"></i>
                                Belum ada data mata pelajaran yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination jika ada --}}
        @if(method_exists($mapels, 'hasPages') && $mapels->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $mapels->links() }}
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