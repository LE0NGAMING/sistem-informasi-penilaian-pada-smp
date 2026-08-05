@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Master Data Guru</h3>
            <p class="text-muted small mb-0">Kelola data tenaga pengajar dan profil pengajar.</p>
        </div>
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Guru
        </a>
    </div>

    <!-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif -->

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
                            <th>Mata Pelajaran</th>
                            <th>No. WA/HP</th>
                            <!-- <th>Email Akun</th> -->
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $index => $guru)
                        @php
                        $jk = $guru->jenis_kelamin->value ?? $guru->jenis_kelamin;
                        @endphp
                        <tr>
                            <td class="ps-4">{{ $gurus->firstItem() + $index }}</td>

                            {{-- Column Foto --}}
                            <td>
                                @if($guru->foto_path)
                                <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->nama_lengkap }}" class="rounded-circle object-fit-cover" width="40" height="40">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary fw-bold border" style="width: 40px; height: 40px; font-size: 14px;">
                                    {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                                </div>
                                @endif
                            </td>

                            <td><span class="fw-semibold text-secondary">{{ $guru->nip ?? '-' }}</span></td>
                            <td class="fw-bold text-dark">{{ $guru->nama_lengkap }}</td>
                            <td>
                                @if(in_array(strtoupper(trim($jk)), ['L', 'LAKI-LAKI']))
                                <span class="badge bg-info-subtle text-info px-2 py-1">Laki-laki</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger px-2 py-1">Perempuan</span>
                                @endif
                            </td>
                            <td>{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
                            <td>{{ $guru->mapel->nama_mapel ?? '-' }}</td>
                            <td><span class="text-muted small">{{ $guru->no_hp ?? '-' }}</span></td>
                            <!-- <td class="text-muted small">{{ $guru->user->email ?? '-' }}</td> -->
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-delete"
                                            data-nama="{{ $guru->nama_lengkap }}"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
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
            {{ $gurus->links() }}
        </div>
        @endif
    </div>
</div>
@endsection