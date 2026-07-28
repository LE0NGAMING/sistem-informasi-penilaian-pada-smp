<header class="app-header">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-icon d-lg-none p-0 border-0 fs-4 text-dark" id="sidebarToggle" type="button">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-none d-md-block">
            <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">@yield('title', 'Dashboard')</h5>
            <small class="text-muted" style="font-size: 0.8rem;">Tahun Ajaran 2025/2026 — Semester Ganjil</small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Quick Action Notification Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light rounded-circle position-relative p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #F3F4F6;" data-bs-toggle="dropdown">
                <i class="bi bi-bell text-secondary"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-2" style="width: 300px; border-radius: var(--radius-md);">
                <li>
                    <h6 class="dropdown-header fw-bold">Notifikasi</h6>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item rounded py-2 fs-7" href="#">
                        <div class="fw-semibold">Rapor 7-A Selesai Di-generate</div>
                        <small class="text-muted">10 menit yang lalu</small>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn p-0 border-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; background: linear-gradient(135deg, #557262 0%, #2C3531 100%) !important;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-start d-none d-sm-block">
                    <div class="fw-semibold fs-7 leading-tight" style="font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                    <div class="text-muted fs-8" style="font-size: 0.75rem; text-transform: capitalize;">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
                <i class="bi bi-chevron-down text-muted fs-8 ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-2" style="border-radius: var(--radius-md);">
                <li>
                    <a class="dropdown-item rounded" href="#"><i class="bi bi-person me-2"></i> Profil Anda</a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item rounded text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>