@extends('layouts.app')

@section('title', 'Data Penilaian Siswa')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Penilaian Akademik Siswa</h4>
        <p class="text-muted mb-0 fs-7">Kelola masukan nilai harian, tugas, UTS, UAS, dan capaian kompetensi.</p>
    </div>
    <a href="{{ route('penilaian.create') }}" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Input Nilai Baru
    </a>
</div>

<div class="card-premium p-4">
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('penilaian.index') }}" class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <select name="rombel_id" class="form-select fs-7">
                <option value="">-- Semua Kelas / Rombel --</option>
                <!-- Options populated dynamically -->
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="mapel_id" class="form-select fs-7">
                <option value="">-- Semua Mata Pelajaran --</option>
            </select>
        </div>
        <div class="col-12 col-md-4">
            <input type="text" name="search" class="form-control fs-7" placeholder="Cari nama siswa / NISN..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-secondary w-100 fs-7"><i class="bi bi-filter me-1"></i> Filter</button>
        </div>
    </form>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>NH / Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                    <th>Status Remedial</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $item)
                <tr>
                    <td class="ps-3 fw-semibold text-dark">
                        {{ $item->siswa->nama_lengkap ?? '-' }}
                        <div class="text-muted fs-8">NISN: {{ $item->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $item->nilai_harian }} / {{ $item->tugas }}</td>
                    <td>{{ $item->uts }}</td>
                    <td>{{ $item->uas }}</td>
                    <td class="fw-bold text-dark">{{ number_format($item->nilai_akhir, 2) }}</td>
                    <td>
                        <span class="badge {{ $item->predikat == 'A' ? 'bg-success' : ($item->predikat == 'B' ? 'bg-primary' : ($item->predikat == 'C' ? 'bg-warning' : 'bg-danger')) }}">
                            {{ $item->predikat }}
                        </span>
                    </td>
                    <td>
                        @if($item->is_remedial)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Perlu Remedial</span>
                        @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Tuntas</span>
                        @endif
                    </td>
                    <td class="text-end pe-3">
                        <a href="{{ route('penilaian.edit', $item->id) }}" class="btn btn-sm btn-light border me-1" title="Edit">
                            <i class="bi bi-pencil-square text-secondary"></i>
                        </a>
                        <form action="{{ route('penilaian.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data penilaian tersimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $penilaian->links() }}
    </div>
</div>
@endsection