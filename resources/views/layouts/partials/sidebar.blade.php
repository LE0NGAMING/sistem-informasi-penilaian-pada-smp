@use('App\Enums\RoleEnum')

@php
$user = auth()->user();
$userRole = $user?->role;

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

<aside id="sidebar-wrapper" class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 min-h-screen transition-all duration-300 flex flex-col z-20">
    <!-- Brand -->
    <div class="h-[70px] flex items-center px-6 border-b border-slate-800/80 gap-3">
        <i class="bi bi-mortarboard-fill text-blue-500 text-2xl"></i>
        <span class="font-bold text-white text-base tracking-wide">SIP SMPN 110</span>
    </div>

    <div class="py-4 space-y-6 overflow-y-auto flex-1">
        <!-- Menu Utama -->
        <div>
            <div class="px-6 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Menu Utama</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('*.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-speedometer2 text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Akademik & Penilaian -->
        <div>
            <div class="px-6 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Akademik & Penilaian</div>
            <ul class="space-y-1">
                {{-- Menu Admin Sekolah --}}
                @if($userRole === RoleEnum::ADMIN_SEKOLAH)
                <li>
                    <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-people-fill text-lg"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.guru.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-person-badge-fill text-lg"></i>
                        <span>Data Guru</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mapel.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.mapel.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-book-half text-lg"></i>
                        <span>Data Mata Pelajaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.rombel.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.rombel.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-building text-lg"></i>
                        <span>Data Rombel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pengampu.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pengampu.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-person-fill-add text-lg"></i>
                        <span>Penugasan Mengajar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.presensi.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.presensi.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-calendar-check-fill text-lg"></i>
                        <span>Presensi Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nilai.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.nilai.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-pencil-square text-lg"></i>
                        <span>Input Penilaian</span>
                    </a>
                </li>
                @endif

                {{-- Menu Guru & Wali Kelas --}}
                @if($userRole === RoleEnum::GURU)
                <li>
                    <a href="{{ route('guru.nilai.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('guru.nilai.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-pencil-square text-lg"></i>
                        <span>Input Penilaian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.rekap.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('guru.rekap.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-file-earmark-text-fill text-lg"></i>
                        <span>Rekap Penilaian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('walikelas.presensi.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('walikelas.presensi.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-calendar-check-fill text-lg"></i>
                        <span>Presensi Rombel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('walikelas.ekskul.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('walikelas.ekskul.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-calendar-check-fill text-lg"></i>
                        <span>Input Nilai Ekskul</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('walikelas.rapor.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('walikelas.rapor.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-printer-fill text-lg"></i>
                        <span>Cetak Rapor</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        {{-- Pengaturan & Sistem --}}
        @if(in_array($userRole, [RoleEnum::ADMIN_SEKOLAH, RoleEnum::SUPER_ADMIN], true))
        <div>
            <div class="px-6 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pengaturan</div>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        <i class="bi bi-gear-fill text-lg"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <div class="px-6 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sistem</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.logs.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-clock-history text-lg"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif
    </div>
</aside>