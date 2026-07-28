@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')
<!-- Header Salam & Info Pengajar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-0 opacity-75">
                            NIP: {{ $guru->nip ?? '-' }} &bull; Mata Pelajaran Utama: <span class="badge bg-white text-primary fw-semibold">{{ $guru->mata_pelajaran ?? 'Pendidik' }}</span>
                        </p>
                    </div>
                    @if($rombelWali)
                    <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-3 p-3 text-end">
                        <span class="extra-small text-white-50 d-block text-uppercase fw-semibold">Status Wali Kelas</span>
                        <span class="fs-5 fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> {{ $rombelWali->nama_rombel }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cards Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <!-- Card Kelas Mengajar -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Kelas Mengajar</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalKelas }} Kelas</h3>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-journal-bookmark-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Siswa Diampu -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Siswa Diampu</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalSiswaDiampu }} Siswa</h3>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Status Progress Penilaian -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Status Penilaian</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $kelasSelesai }} / {{ $totalKelas }} <span class="fs-6 text-muted fw-normal">Selesai</span></h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Status Wali Kelas -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Tugas Wali Kelas</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $rombelWali ? $rombelWali->nama_rombel : 'Tidak Ada' }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-person-workspace fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Ringkasan Mengajar & Status Input Nilai -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-list-task me-2 text-primary"></i>Status Input Nilai Per Kelas
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">Mata Pelajaran</th>
                            <th>Rombel / Kelas</th>
                            <th>Input Siswa</th>
                            <th>Progress Nilai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelasList as $item)
                        <tr>
                            <td class="ps-3 fw-semibold text-dark">{{ $item->mapel }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->rombel }}</span></td>
                            <td class="small text-muted">{{ $item->jumlah_terisi }} / {{ $item->jumlah_siswa }} Siswa</td>
                            <td style="width: 25%;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="flex-grow: 1; height: 6px;">
                                        <div class="progress-bar @if($item->progress == 100) bg-success @elseif($item->progress > 0) bg-warning @else bg-danger @endif"
                                            role="progressbar"
                                            @style(["width: {$item->progress}%"])
                                            aria-valuenow="{{ $item->progress }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="small font-monospace text-muted">{{ $item->progress }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada data kelas yang tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Akses Cepat & Menu Wali Kelas -->
    <div class="col-12 col-lg-4">
        @if($rombelWali)
        <!-- Panel Khusus Wali Kelas -->
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-star-fill text-warning me-2"></i>Menu Wali Kelas ({{ $rombelWali->nama_rombel }})
                </h6>
            </div>
            <div class="card-body pt-0 d-grid gap-2">
                <a href="#" class="btn btn-outline-warning text-dark btn-sm text-start py-2">
                    <i class="bi bi-file-earmark-person me-2"></i>Kelola Absensi & Sikap Siswa
                </a>
                <a href="#" class="btn btn-outline-warning text-dark btn-sm text-start py-2">
                    <i class="bi bi-printer me-2"></i>Cetak Rapor Digital Kelas
                </a>
            </div>
        </div>
        @endif

        <!-- Panel Akses Cepat Guru -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-lightning-charge-fill me-2 text-primary"></i>Akses Cepat Penilaian
                </h6>
            </div>
            <div class="card-body pt-0 d-grid gap-2">
                <a href="#" class="btn btn-outline-primary btn-sm text-start py-2">
                    <i class="bi bi-journal-plus me-2"></i>Input Nilai Formatif & Sumatif
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm text-start py-2">
                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Rekapitulasi Nilai Mapel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection