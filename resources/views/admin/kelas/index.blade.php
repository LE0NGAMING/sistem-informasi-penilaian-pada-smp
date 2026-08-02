@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Master Data Kelas</h3>
            <p class="text-muted small mb-0">Kelola daftar kelas dan rombongan jenjang tingkat.</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Jumlah Siswa</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelases as $index => $kelas)
                        <tr>
                            <td class="ps-4">{{ $kelases->firstItem() + $index }}</td>
                            <td><span class="fw-bold text-dark">{{ $kelas->nama_kelas }}</span></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    Tingkat {{ $kelas->tingkat }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                    {{ $kelas->siswa_count ?? 0 }} Siswa
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-delete"
                                            data-nama="{{ $kelas->nama_kelas }}"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data kelas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($kelases->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $kelases->links() }}
        </div>
        @endif
    </div>
</div>
@endsection