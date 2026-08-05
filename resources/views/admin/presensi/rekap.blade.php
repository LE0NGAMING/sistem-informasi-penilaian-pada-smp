@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Rekapitulasi Presensi (Rapor)</h3>
            <p class="text-muted small mb-0">Ringkasan total kehadiran akumulatif seluruh siswa per kelas.</p>
        </div>
        <a href="{{ route('admin.presensi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Presensi Harian
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.presensi.rekap') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="kelas_id" class="form-label fw-semibold">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedKelas)
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th class="text-center text-success">Hadir (H)</th>
                            <th class="text-center text-warning">Sakit (S)</th>
                            <th class="text-center text-info">Izin (I)</th>
                            <th class="text-center text-danger">Alpa (A)</th>
                            <th class="text-center pe-4">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekaps as $index => $rekap)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="font-monospace small text-muted">{{ $rekap['nisn'] }}</td>
                            <td><span class="fw-bold text-dark">{{ $rekap['nama_lengkap'] }}</span></td>
                            <td class="text-center fw-bold text-success">{{ $rekap['hadir'] }}</td>
                            <td class="text-center fw-bold text-warning">{{ $rekap['sakit'] }}</td>
                            <td class="text-center fw-bold text-info">{{ $rekap['izin'] }}</td>
                            <td class="text-center fw-bold text-danger">{{ $rekap['alpa'] }}</td>
                            <td class="text-center pe-4">
                                <span class="badge {{ $rekap['persentase'] >= 85 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2 fs-7">
                                    {{ $rekap['persentase'] }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data akumulasi presensi di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection