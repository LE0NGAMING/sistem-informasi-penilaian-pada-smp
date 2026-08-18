@php
$user = auth()->user();
@endphp

<header class="h-[70px] bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-10 shadow-xs">
    <div class="flex items-center gap-4">
        <button class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus:outline-none" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="bi bi-list text-xl leading-none"></i>
        </button>
        <h1 class="text-base font-semibold text-slate-800 hidden sm:block">@yield('page-title', 'Dashboard')</h1>
    </div>

    <!-- User Profile Dropdown -->
    <div class="relative">
        <button id="userMenuBtn" class="flex items-center gap-3 text-slate-700 hover:text-slate-900 focus:outline-none py-1">
            <div class="w-9 h-9 bg-slate-200 text-slate-700 rounded-full flex items-center justify-center font-semibold text-sm">
                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
            </div>
            <div class="hidden md:block text-left">
                <div class="font-semibold text-sm truncate max-w-[150px]">
                    {{ $user?->name ?? 'Pengguna' }}
                </div>
            </div>
            <i class="bi bi-chevron-down text-xs text-slate-400"></i>
        </button>

        <!-- Dropdown Menu -->
        <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
            <div class="px-4 py-2 border-b border-slate-100 md:hidden">
                <div class="font-semibold text-sm text-slate-800">{{ $user?->name ?? 'Pengguna' }}</div>
                <div class="text-xs text-slate-500 truncate">{{ $user?->email ?? '' }}</div>
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                <i class="bi bi-person text-slate-400 mr-2.5"></i> Profil Saya
            </a>
            <hr class="my-1 border-slate-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition">
                    <i class="bi bi-box-arrow-right mr-2.5"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('userMenuBtn');
        const menu = document.getElementById('userDropdownMenu');

        if (btn && menu) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
</script>