@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Data Guru</h3>
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
        @endif
    </div>

    <!-- {{-- Alert Notifikasi --}}
    @session('success')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession -->

    {{-- Filter & Pencarian --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.guru.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Cari NIP, NUPTK, atau Nama Guru..."
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <select name="mapel_id" class="form-select">
                        <option value="">-- Semua Mata Pelajaran --</option>
                        @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}" @selected(request('mapel_id')==$mapel->id)>
                            {{ $mapel->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-3">Cari</button>
                    @if(request()->hasAny(['search', 'mapel_id']))
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary px-3" title="Reset Filter">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Foto</th>
                            <th>NIP / NUPTK</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Mata Pelajaran Utama</th>
                            <th>No. WA/HP</th>
                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <th class="text-center pe-4">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $guru)
                        <tr>
                            <td class="ps-4">{{ $gurus->firstItem() + $loop->index }}</td>

                            {{-- Foto Profil --}}
                            <td>
                                @if($guru->foto_path)
                                <img src="{{ Storage::url($guru->foto_path) }}" alt="{{ $guru->nama_lengkap }}" class="rounded-circle object-fit-cover" width="40" height="40">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary fw-bold border" style="width: 40px; height: 40px; font-size: 14px;">
                                    {{ str($guru->nama_lengkap)->substr(0, 1)->upper() }}
                                </div>
                                @endif
                            </td>

                            <td><span class="fw-semibold text-secondary">{{ $guru->nip ?? '-' }}</span></td>
                            <td class="fw-bold text-dark">{{ $guru->nama_lengkap }}</td>
                            <td>
                                @if(str($guru->jenis_kelamin?->value ?? $guru->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']))
                                <span class="badge bg-info-subtle text-info px-2 py-1">Laki-laki</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">Perempuan</span>
                                @endif
                            </td>
                            <td>{{ $guru->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}</td>
                            <td>{{ $guru->mapel?->nama_mapel ?? '-' }}</td>
                            <td><span class="text-muted small">{{ $guru->no_hp ?? '-' }}</span></td>

                            @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.guru.edit', $guru) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" class="delete-form inline">
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
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data guru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($gurus->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $gurus->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection