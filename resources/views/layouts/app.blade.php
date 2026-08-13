@use('App\Enums\RoleEnum')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SIP SMP</title>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 70px;
            --primary-color: #2563eb;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            overflow-x: hidden;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: #94a3b8;
            flex-shrink: 0;
            transition: margin-left 0.3s ease;
            z-index: 1000;
        }

        #sidebar-wrapper .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.15rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #sidebar-wrapper .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.8rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        #sidebar-wrapper .nav-link i {
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
            display: inline-block;
        }

        #sidebar-wrapper .nav-link:hover,
        #sidebar-wrapper .nav-link.active {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        #sidebar-wrapper .nav-link.active {
            background-color: var(--primary-color);
        }

        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1.2rem 1.5rem 0.4rem;
            font-weight: 700;
        }

        /* Main Content Styling */
        #page-content-wrapper {
            flex-grow: 1;
            width: 100%;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* Topbar Styling */
        .topbar {
            height: var(--topbar-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Responsive Sidebar Toggle */
        #wrapper.toggled #sidebar-wrapper {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background-color: #e2e8f0;
            color: #475569;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <i class="bi bi-mortarboard-fill text-primary me-2 fs-4"></i>
                <span>SIP SMPN 110 Jakarta</span>
            </div>

            <div class="py-3">
                <div class="sidebar-heading">Menu Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        @php
                        $userRole = auth()->user()?->role;
                        $dashboardRoute = match($userRole) {
                        RoleEnum::SUPER_ADMIN => route('superadmin.dashboard'),
                        RoleEnum::ADMIN_SEKOLAH => route('admin.dashboard'),
                        RoleEnum::KEPALA_SEKOLAH => route('kepalasekolah.dashboard'),
                        RoleEnum::KURIKULUM => route('kurikulum.dashboard'),
                        RoleEnum::GURU => route('guru.dashboard'),
                        RoleEnum::SISWA => route('siswa.dashboard'),
                        RoleEnum::ORANG_TUA => route('ortu.dashboard'),
                        default => route('login'),
                        };
                        @endphp

                        <a href="{{ $dashboardRoute }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">Akademik & Penilaian</div>
                <ul class="nav flex-column">
                    {{-- Menu Master Data (Tampil untuk Admin & Guru) --}}
                    {{--@if(in_array($userRole, [RoleEnum::ADMIN_SEKOLAH, RoleEnum::GURU], true))--}}
                    @if($userRole === RoleEnum::ADMIN_SEKOLAH)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}" href="{{ route('admin.siswa.index') }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}" href="{{ route('admin.guru.index') }}">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Data Guru</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}" href="{{ route('admin.mapel.index') }}">
                            <i class="bi bi-book-half"></i>
                            <span>Data Mata Pelajaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.rombel.*') ? 'active' : '' }}" href="{{ route('admin.rombel.index') }}">
                            <i class="bi bi-building"></i>
                            <span>Data Rombel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pengampu.*') ? 'active' : '' }}" href="{{ route('admin.pengampu.index') }}">
                            <i class="bi bi-person-fill-add"></i>
                            <span>Penugasan Mengajar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.presensi.*') ? 'active' : '' }}" href="{{ route('admin.presensi.index') }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Presensi Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.nilai.*') ? 'active' : '' }}" href="{{ route('admin.nilai.index') }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Input Penilaian</span>
                        </a>
                    </li>
                    @endif

                    {{-- Menu Khusus Guru --}}
                    @if($userRole === RoleEnum::GURU)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}" href="{{ route('guru.nilai.index') }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Input Penilaian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.rekap.*') ? 'active' : '' }}" href="{{ route('guru.rekap.index') }}">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Rekap Penilaian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('walikelas.presensi.*') ? 'active' : '' }}" href="{{ route('walikelas.presensi.index') }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Presensi Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.rapor.*') ? 'active' : '' }}" href="{{ route('guru.rapor.index') }}">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Cetak Rapor</span>
                        </a>
                    </li>
                    @endif
                </ul>

                {{-- Pengaturan Hanya Tampil Untuk Admin --}}
                @if(in_array($userRole, [RoleEnum::ADMIN_SEKOLAH, RoleEnum::SUPER_ADMIN], true))
                <div class="sidebar-heading">Pengaturan</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-gear-fill"></i>
                            <span>Pengaturan Sistem</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-heading">Sistem</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </aside>

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper">
            <!-- Topbar / Header -->
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light btn-sm border" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="mb-0 fw-semibold d-none d-sm-block">@yield('page-title', 'Dashboard')</h5>
                </div>

                <!-- Navbar Profil & User Status -->
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark gap-2" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block text-start me-1">
                                <div class="fw-semibold small text-truncate" style="max-width: 150px;">
                                    {{ auth()->user()?->name ?? 'Pengguna' }}
                                </div>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userDropdown">
                            <li class="px-3 py-2 border-bottom d-md-none">
                                <div class="fw-semibold">{{ auth()->user()?->name ?? 'Pengguna' }}</div>
                                <small class="text-muted">{{ auth()->user()?->email ?? '' }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2 text-secondary"></i>Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-key me-2 text-secondary"></i>Ubah Password
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main Content Body -->
            <main class="p-4">

                {{-- Alert Success Session --}}
                @session('success')
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ $value }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endsession

                {{-- Alert Error Session --}}
                @session('error')
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $value }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endsession

                {{-- Dynamic Content --}}
                @yield('content')

            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle & Global UI Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Sidebar Toggle Logic
            const sidebarToggle = document.getElementById('sidebarToggle');
            const wrapper = document.getElementById('wrapper');

            if (sidebarToggle && wrapper) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }

            // 2. Auto close Bootstrap alert after 3 seconds
            const alertElements = document.querySelectorAll('.alert');
            alertElements.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }, 3000);
            });

            // 3. Global SweetAlert Confirm Delete (Event Delegation yang bersih & stabil)
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form && form.classList.contains('delete-form')) {
                    e.preventDefault(); // Hentikan submit form sementara

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Lanjutkan submit form asli jika dikonfirmasi
                        }
                    });
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>