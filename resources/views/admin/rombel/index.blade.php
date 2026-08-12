@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Rombongan Belajar (Rombel)</h3>
            <!-- <p class="text-muted small mb-0">Kelola kelompok kelas dan penugasan wali kelas.</p> -->
        </div>
        @if(auth()->user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH )
        <a href="{{ route('admin.rombel.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Rombel
        </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.rombel.index') }}" method="GET" class="row g-2 align-items-center">
                {{-- Input Pencarian Rombel / Nama Wali Kelas --}}
                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Cari Rombel atau Nama Wali Kelas..."
                            value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-3">Cari</button>
                    @if(request('search'))
                    <a href="{{ route('admin.rombel.index') }}" class="btn btn-outline-secondary px-3" title="Reset Filter">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">No</th>
                            <th>Nama Rombel</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rombels as $index => $rombel)
                        <tr>
                            <td class="ps-3">{{ $rombels->firstItem() + $index }}</td>
                            <td><strong class="text-primary">{{ $rombel->nama_rombel }}</strong></td>
                            <td>
                                @if($rombel->tingkat)
                                <span class="badge bg-primary">Kelas {{ $rombel->tingkat }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $rombel->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}</td>
                            <td class="text-end pe-3">
                                {{-- Tombol Detail untuk Plotting Siswa --}}
                                <a href="{{ route('admin.rombel.show', $rombel->id) }}" class="btn btn-sm btn-info text-white me-1" title="Detail / Plotting Siswa">
                                    <i class="bi bi-people-fill"></i>
                                </a>
                                <a href="{{ route('admin.rombel.edit', $rombel->id) }}" class="btn btn-sm btn-warning text-white me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.rombel.destroy', $rombel->id) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data rombel.</td>
                        </tr>
                        @endempty
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection