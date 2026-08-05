@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
        <p class="text-muted small">Panel Kontrol Guru - Kelola kehadiran dan nilai siswa dengan mudah.</p>
    </div>

    {{-- Shortcut Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge bg-primary-subtle text-primary mb-2">Presensi</span>
                        <h4 class="fw-bold text-dark mb-1">Presensi Siswa</h4>
                        <p class="text-muted small mb-3">Catat atau perbarui kehadiran siswa harian.</p>
                        <a href="{{ route('guru.presensi.index') }}" class="btn btn-sm btn-primary px-3">
                            <i class="bi bi-calendar-check me-1"></i> Buka Presensi
                        </a>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-calendar-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge bg-success-subtle text-success mb-2">Penilaian</span>
                        <h4 class="fw-bold text-dark mb-1">Input Nilai Siswa</h4>
                        <p class="text-muted small mb-3">Input nilai tugas, UTS, UAS, dan Rapor.</p>
                        <a href="{{ route('guru.nilai.index') }}" class="btn btn-sm btn-success px-3">
                            <i class="bi bi-pencil-square me-1"></i> Buka Penilaian
                        </a>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="bi bi-pencil-square fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection