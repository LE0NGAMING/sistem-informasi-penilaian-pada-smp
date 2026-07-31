<aside class="w-64 bg-slate-900 text-slate-300 min-h-screen flex flex-col shrink-0 border-r border-slate-800">
    <!-- BRAND LOGO -->
    <div class="p-5 flex items-center gap-3 border-b border-slate-800">
        <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-md shrink-0">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="overflow-hidden">
            <h2 class="font-bold text-white text-base leading-tight truncate">SMPN 110 Jakarta</h2>
            <p class="text-xs text-slate-400">Sistem Penilaian Modern</p>
        </div>
    </div>

    <!-- SIDEBAR MENU -->
    <div class="p-4 space-y-6 flex-1 overflow-y-auto">

        <!-- MENU UTAMA -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
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
            <a href="{{ route($dashboardRoute) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('*.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="bi bi-grid-1x2-fill text-base"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- AKSES & PENGGUNA (SUPER ADMIN) -->
        @if(auth()->user()->role === 'super_admin')
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Akses & Pengguna</p>
            <a href="{{ route('superadmin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('superadmin.users.*') ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="bi bi-people-fill text-base"></i>
                <span>Kelola User & Admin</span>
            </a>
        </div>
        @endif

        <!-- MASTER DATA -->
        @if(in_array(auth()->user()->role, ['super_admin', 'admin_sekolah']))
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Master Data</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-building"></i> <span>Sekolah & Akademik</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-person-badge"></i> <span>Data Guru</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-people"></i> <span>Data Siswa</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-journal-bookmark"></i> <span>Mata Pelajaran</span>
                </a>
            </div>
        </div>
        @endif

        <!-- AKADEMIK & NILAI -->
        @if(in_array(auth()->user()->role, ['super_admin', 'admin_sekolah', 'guru_mapel', 'wali_kelas', 'guru']))
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Akademik & Nilai</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-pencil-square"></i> <span>Penilaian Siswa</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-card-checklist"></i> <span>Absensi Kelas</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                    <i class="bi bi-journal-check"></i> <span>Cetak & Validasi Rapor</span>
                </a>
            </div>
        </div>
        @endif

        <!-- PENGATURAN -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pengaturan</p>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition hover:bg-slate-800 hover:text-white">
                <i class="bi bi-person-gear"></i> <span>Profil Saya</span>
            </a>
        </div>

    </div>
</aside>