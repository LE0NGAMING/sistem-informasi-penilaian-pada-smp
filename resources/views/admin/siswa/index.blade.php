@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Data Siswa</h3>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
        @endif
    </div>

    {{-- Filter & Pencarian --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.siswa.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Cari Nama, NIS, atau NISN..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-3">Cari</button>
                    @if(request()->filled('search'))
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary px-3" title="Reset Filter">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table Data --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%" class="text-center">#</th>
                            <th scope="col" width="18%">NIS / NISN</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col" width="12%">Gender</th>
                            <th scope="col" width="22%">Email (Akun)</th>
                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <th scope="col" width="12%" class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                {{ $siswas->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $siswa->nis ?? '-' }}</div>
                                <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                <small class="text-muted">
                                    {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}
                                    {{ $siswa->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}
                                </small>
                            </td>
                            <td>
                                @if(str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']))
                                <span class="badge bg-info-subtle text-info fw-semibold px-2 py-1">Laki-laki</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger fw-semibold px-2 py-1">Perempuan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <span>{{ $siswa->user?->email ?? '-' }}</span>
                                </div>
                            </td>
                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH ? 6 : 5 }}" class="text-center py-5">
                                <i class="bi bi-people text-muted fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Siswa</h6>
                                <p class="text-muted small mb-0">Klik tombol "Tambah Data" di atas untuk menambahkan data baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($siswas->hasPages())
            <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-3">
                <span class="text-muted small">
                    Menampilkan {{ $siswas->firstItem() }} - {{ $siswas->lastItem() }} dari {{ $siswas->total() }} data
                </span>
                <div>
                    {{ $siswas->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection