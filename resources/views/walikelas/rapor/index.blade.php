@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">Daftar & Status Rapor Siswa</h4>
            <p class="text-muted small mb-0">Rombongan Belajar: <span class="fw-bold text-primary">{{ $rombel->nama_rombel }}</span></p>
        </div>

        @if($siswaList->isNotEmpty())
        <div>
            <a href="{{ route('walikelas.rapor.rombel', ['rombel' => $rombel->id, 'semester_id' => $semesterId]) }}"
                target="_blank"
                class="btn btn-outline-primary rounded-3 shadow-sm">
                <i class="bi bi-printer me-2"></i> Cetak Rapor Semua Siswa (Kolektif)
            </a>
        </div>
        @endif
    </div>

    {{-- Alert System --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('walikelas.rapor.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="tahun_ajaran_id" class="form-label small fw-semibold text-secondary">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select rounded-3">
                        @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>
                            {{ $ta->tahun }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="semester" class="form-label small fw-semibold text-secondary">Semester</label>
                    <select name="semester" id="semester" class="form-select rounded-3">
                        <option value="ganjil" @selected($semesterId=='ganjil' )>Ganjil</option>
                        <option value="genap" @selected($semesterId=='genap' )>Genap</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        <i class="bi bi-filter me-1"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Monitoring & Akses Cetak Rapor --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center px-3" style="width: 50px;">No</th>
                            <th>NISN / Nama Siswa</th>
                            <th class="text-center">Kelengkapan Nilai Mapel</th>
                            <th class="text-center">Nilai Ekskul</th>
                            <th class="text-center">Data Presensi</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                        @php
                        $totalNilaiTerisi = $siswa->penilaian?->count() ?? 0;
                        $isNilaiComplete = $totalNilaiTerisi >= $totalMapel && $totalMapel > 0;
                        $hasEkskul = $siswa->nilaiEkskul?->isNotEmpty() ?? false;
                        $hasPresensi = $siswa->presensiHarian?->isNotEmpty() ?? false;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                                <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                            </td>

                            {{-- Indicator Nilai Mapel --}}
                            <td class="text-center">
                                @if($isNilaiComplete)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Lengkap ({{ $totalNilaiTerisi }}/{{ $totalMapel }})
                                </span>
                                @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                                    <i class="bi bi-exclamation-circle me-1"></i> Belum Lengkap ({{ $totalNilaiTerisi }}/{{ $totalMapel }})
                                </span>
                                @endif
                            </td>

                            {{-- Indicator Ekskul --}}
                            <td class="text-center">
                                @if($hasEkskul)
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill">
                                    <i class="bi bi-star me-1"></i> Ada Data
                                </span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                    Belum Ada
                                </span>
                                @endif
                            </td>

                            {{-- Indicator Presensi --}}
                            <td class="text-center">
                                @if($hasPresensi)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                    <i class="bi bi-calendar-check me-1"></i> Terisi
                                </span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                    Belum Ada
                                </span>
                                @endif
                            </td>

                            {{-- Action Button --}}
                            <td class="text-center">
                                <a href="{{ route('walikelas.rapor.siswa', ['siswa' => $siswa->id, 'semester_id' => $semesterId]) }}" target="_blank" class="...">
                                    <i class="bi bi-printer"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-6 d-block mb-2"></i>
                                Belum ada siswa terdaftar di rombel ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection