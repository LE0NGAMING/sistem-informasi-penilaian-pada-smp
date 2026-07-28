<aside class="app-sidebar" id="appSidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-dark">SMPN 1 Digital</h6>
            <small class="text-muted" style="font-size: 0.75rem;">Sistem Penilaian Modern</small>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="nav-section-title">Menu Utama</div>

        {{-- Menentukan Route Dashboard Otomatis Sesuai Role User --}}
        @php
        $dashboardRoute = match(auth()->user()->role) {
        'super_admin' => 'superadmin.dashboard',
        'admin_sekolah' => 'admin.dashboard',
        'kepala_sekolah' => 'kepsek.dashboard',
        'kurikulum' => 'kurikulum.dashboard',
        'guru' => 'guru.dashboard',
        'siswa' => 'siswa.dashboard',
        'orang_tua' => 'ortu.dashboard',
        default => 'login',
        };
        @endphp

        <a href="{{ route($dashboardRoute) }}" class="sidebar-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        {{-- Menu Khusus Super Admin --}}
        @if(auth()->user()->role === 'super_admin')
        <div class="nav-section-title">Akses & Pengguna</div>
        <a href="{{ route('superadmin.users.index') }}" class="sidebar-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Kelola User & Admin</span>
        </a>
        @endif

        {{-- Master Data untuk Super Admin & Admin Sekolah --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'admin_sekolah']))
        <div class="nav-section-title">Master Data</div>
        <a href="#" class="sidebar-link">
            <i class="bi bi-building"></i>
            <span>Sekolah & Akademik</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-person-badge"></i>
            <span>Data Guru</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-people"></i>
            <span>Data Siswa</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-journal-bookmark"></i>
            <span>Mata Pelajaran</span>
        </a>
        @endif

        {{-- Akademik & Nilai --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'admin_sekolah', 'guru_mapel', 'wali_kelas', 'guru']))
        <div class="nav-section-title">Akademik & Nilai</div>
        <a href="#" class="sidebar-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
            <i class="bi bi-pencil-square"></i>
            <span>Penilaian Siswa</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-card-checklist"></i>
            <span>Absensi Kelas</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-journal-check"></i>
            <span>Cetak & Validasi Rapor</span>
        </a>
        @endif

        {{-- Laporan Siswa & Orang Tua --}}
        @if(in_array(auth()->user()->role, ['siswa', 'orang_tua']))
        <div class="nav-section-title">Laporan Siswa</div>
        <a href="#" class="sidebar-link">
            <i class="bi bi-journal-richtext"></i>
            <span>Nilai Akademik</span>
        </a>
        <a href="#" class="sidebar-link">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>Rapor Digital</span>
        </a>
        @endif

        <div class="nav-section-title">Pengaturan</div>
        <a href="#" class="sidebar-link">
            <i class="bi bi-person-gear"></i>
            <span>Profil Saya</span>
        </a>
    </div>
</aside>