@extends('layouts.app')

@section('title', 'Dashboard Admin Sekolah')
@section('page-title', 'Dashboard Admin Sekolah')

@section('content')
<!-- Header Salam / Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-0 opacity-75">
                            Kelola data master sekolah, pengguna, dan pemantauan sistem penilaian dari panel kontrol ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cards Statistik Utama -->
<div class="row g-3 mb-4">
    <!-- Card Total Siswa -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Siswa</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalSiswa) }}</h3>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Guru -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Guru</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalGuru) }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-person-badge-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Rombel -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Rombel / Kelas</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalRombel) }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-door-open-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Akun -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Akun Pengguna</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalUser) }}</h3>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-shield-lock-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Akun Pengguna Terbaru -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Pengguna Terbaru Terdaftar</h6>
                <a href="#" class="btn btn-sm btn-light border text-secondary fw-semibold">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">Nama</th>
                            <th>Email</th>
                            <th>Role Access</th>
                            <th>Tanggal Buat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestUsers as $user)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    {{ ucfirst(str_replace('_', ' ', $user->role->value ?? $user->role)) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada pengguna terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Role Akun & Pintasan Akses -->
    <div class="col-12 col-lg-4">
        <!-- Card Distribusi Role -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribusi Role Akun</h6>
            </div>
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush border-top-0">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="small text-muted"><i class="bi bi-shield-check text-danger me-2"></i>Admin Sekolah</span>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $roleCounts['admin_sekolah'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="small text-muted"><i class="bi bi-person-badge text-success me-2"></i>Guru</span>
                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $roleCounts['guru'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="small text-muted"><i class="bi bi-people text-primary me-2"></i>Siswa</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $roleCounts['siswa'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="small text-muted"><i class="bi bi-heart text-warning me-2"></i>Orang Tua</span>
                        <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $roleCounts['orang_tua'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="small text-muted"><i class="bi bi-award text-info me-2"></i>Kepala Sekolah</span>
                        <span class="badge bg-info-subtle text-info rounded-pill">{{ $roleCounts['kepala_sekolah'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Card Pintasan Akses Cepat -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Akses Cepat</h6>
            </div>
            <div class="card-body pt-0 d-grid gap-2">
                <a href="{{ route('admin.siswa.create') }}" class="btn btn-outline-primary btn-sm text-start py-2">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Data Siswa
                </a>
                <a href="{{ route('admin.guru.create') }}" class="btn btn-outline-success btn-sm text-start py-2">
                    <i class="bi bi-person-badge-fill me-2"></i>Tambah Data Guru
                </a>
                <a href="{{ route('admin.mapel.create') }}" class="btn btn-outline-info btn-sm text-start py-2">
                    <i class="bi bi-book-half me-2"></i>Tambah Data Mata Pelajaran
                </a>
                <a href="{{ route('admin.kelas.create') }}" class="btn btn-outline-secondary btn-sm text-start py-2">
                    <i class="bi bi-house-add-fill me-2"></i>Tambah Data Kelas
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm text-start py-2">
                    <i class="bi bi-calendar3 me-2"></i>Kelola Tahun Akademik & Semester
                </a>
            </div>
        </div>
    </div>
</div>
@endsection