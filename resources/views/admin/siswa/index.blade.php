@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Master Data Siswa</h3>
            <p class="text-muted small mb-0">Kelola data siswa dan akun akses sistem.</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Siswa
        </a>
    </div>

    <!-- Alert Success (Opsi jika alert bawaan ingin ditampilkan) -->
    <!-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif -->

    <!-- Card Data Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas</th>
                            <th>Email Akun</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">
                                {{ method_exists($siswas, 'firstItem') && $siswas->firstItem() ? $siswas->firstItem() + $index : $index + 1 }}
                            </td>
                            <td><span class="fw-semibold text-secondary">{{ $siswa->nisn }}</span></td>
                            <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td>
                                @php
                                $jk = $siswa->jenis_kelamin->value ?? $siswa->jenis_kelamin;
                                @endphp
                                @if(in_array(strtoupper(trim($jk)), ['L', 'LAKI-LAKI']))
                                <span class="badge bg-info-subtle text-info px-2 py-1">Laki-laki</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">Perempuan</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                    {{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $siswa->user->email ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-delete"
                                            data-nama="{{ $siswa->nama_lengkap }}"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data siswa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($siswas, 'hasPages') && $siswas->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Skrip Penanganan Hapus Data --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const nama = this.getAttribute('data-nama') || 'siswa ini';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Data siswa <strong>${nama}</strong> beserta akun aksesnya akan dihapus!`,
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