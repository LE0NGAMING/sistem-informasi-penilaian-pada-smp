<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Dashboard') - SIP SMP</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        $userRole = Auth::user()->role;
                        $dashboardRoute = match($userRole) {
                        \App\Enums\RoleEnum::SUPER_ADMIN => route('superadmin.dashboard'),
                        \App\Enums\RoleEnum::ADMIN_SEKOLAH => route('admin.dashboard'),
                        \App\Enums\RoleEnum::KEPALA_SEKOLAH => route('kepsek.dashboard'),
                        \App\Enums\RoleEnum::KURIKULUM => route('kurikulum.dashboard'),
                        \App\Enums\RoleEnum::GURU => route('guru.dashboard'),
                        \App\Enums\RoleEnum::SISWA => route('siswa.dashboard'),
                        \App\Enums\RoleEnum::ORANG_TUA => route('ortu.dashboard'),
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
                    {{-- Menu Khusus Admin --}}
                    @if(Auth::user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH)
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
                        <a class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}" href="{{ route('admin.kelas.index') }}">
                            <i class="bi bi-building"></i>
                            <span>Data Kelas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.presensi.*') ? 'active' : '' }}" href="{{ route('admin.presensi.index') }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Presensi Siswa</span>
                        </a>
                    </li>
                    @endif

                    {{-- Menu Khusus Guru --}}
                    @if(Auth::user()->role === \App\Enums\RoleEnum::GURU)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}" href="{{ route('guru.nilai.index') }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Input Penilaian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.presensi.*') ? 'active' : '' }}" href="{{ route('guru.presensi.index') }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Presensi Siswa</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rapor.*') ? 'active' : '' }}" href="#">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Cetak Rapor</span>
                        </a>
                    </li>
                </ul>

                {{-- Pengaturan Hanya Tampil Untuk Admin --}}
                @if(Auth::user()->role === \App\Enums\RoleEnum::ADMIN_SEKOLAH || Auth::user()->role === \App\Enums\RoleEnum::SUPER_ADMIN)
                <div class="sidebar-heading">Pengaturan</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-gear-fill"></i>
                            <span>Pengaturan Sistem</span>
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
                    <button class="btn btn-light btn-sm border" id="sidebarToggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="mb-0 fw-semibold d-none d-sm-block">@yield('page-title', 'Dashboard')</h5>
                </div>

                <!-- Navbar Profil & User Status -->
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark gap-2" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block text-start me-1">
                                <div class="fw-semibold small text-truncate" style="max-width: 150px;">
                                    {{ Auth::user()->name ?? 'Pengguna' }}
                                </div>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userDropdown">
                            <li class="px-3 py-2 border-bottom d-md-none">
                                <div class="fw-semibold">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                                <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#">
                                    <i class="bi bi-person me-2 text-secondary"></i>Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#">
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

                <!-- Alert Success Session -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Alert Error Session -->
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Dynamic Content -->
                @yield('content')

            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const wrapper = document.getElementById('wrapper');

            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                wrapper.classList.toggle('toggled');
            });
        });
    </script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto close alert 2 detik
            const alertElements = document.querySelectorAll('.alert');
            alertElements.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }, 2000);
            });

            // SweetAlert Confirm Delete
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const nama = this.getAttribute('data-nama') || 'data ini';

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        html: `Data <strong>${nama}</strong> akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-4 border-0 shadow-lg',
                            confirmButton: 'btn btn-danger px-4 py-2 me-2',
                            cancelButton: 'btn btn-secondary px-4 py-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>