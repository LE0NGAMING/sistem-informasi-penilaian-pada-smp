<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - SIP SMP</title>

    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Load Vite jika sudah ada build asset -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between hidden md:flex shrink-0">
            <div>
                <!-- Brand Logo -->
                <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
                    <div class="bg-blue-600 text-white p-2 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-white tracking-wide">SIP SMP 110 Jakarta</span>
                </div>

                <!-- Navigation Links -->
                <nav class="px-4 py-6 space-y-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Menu Utama</p>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white font-medium shadow-sm transition">
                            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Akses & Pengguna</p>
                        <div class="space-y-1">
                            <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Kelola User & Admin
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Role & Hak Akses
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Sistem & Keamanan</p>
                        <div class="space-y-1">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Audit Log
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                </svg>
                                Backup Database
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Pengaturan Sistem
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- TOPBAR HEADER (Ramping & Proporsional) -->
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-lg font-bold text-slate-800">Dashboard Administrator</h1>

                <!-- DROPDOWN PROFIL HEADER -->
                <div class="relative text-left">
                    <button type="button" id="btnUserDropdown" class="flex items-center gap-2.5 bg-slate-50 hover:bg-slate-100 px-4 py-1.5 rounded-full border border-slate-200 transition cursor-pointer">
                        <div class="text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Super Administrator' }}</p>
                            <!--<p class="text-[10px] text-blue-600 font-medium leading-tight">{{ ucwords(str_replace('_', ' ', Auth::user()->role ?? 'System Admin')) }}</p>-->
                        </div>

                        <!-- Panah Dropdown -->
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- MENU DROPDOWN (Ukurannya pas & rapi) -->
                    <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="text-[11px] font-semibold text-slate-500 truncate">{{ Auth::user()->email ?? 'superadmin@smpn1.sch.id' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                            <!-- Ikon Profil Di-lock ukuran 16px -->
                            <svg class="w-4 h-4 text-slate-400 shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Pengaturan Profil</span>
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer">
                                <!-- Ikon Logout Di-lock ukuran 16px -->
                                <svg class="w-4 h-4 text-rose-500 shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar / Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- DASHBOARD BODY -->
            <main class="p-8 space-y-8">

                <!-- 1. STATS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Admin Sekolah</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalAdmin ?? 1 }}</p>
                        <span class="text-xs text-emerald-600 font-medium">Aktif Mengelola</span>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Pengguna Terdaftar</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalUser ?? 5 }}</p>
                        <span class="text-xs text-slate-400">Guru, Staff & Siswa</span>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Status Database</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">Normal</p>
                        <span class="text-xs text-slate-400">Backup Otomatis Aktif</span>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Keamanan Sistem</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">Aman</p>
                        <span class="text-xs text-emerald-600 font-medium">OWASP Standard Validated</span>
                    </div>
                </div>

                <!-- 2. QUICK ACTIONS & MAINTENANCE -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2">
                        <h2 class="text-base font-bold text-slate-800 mb-4">Aksi Cepat Administrator</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                            <a href="#" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500 hover:shadow-md transition text-center group">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-slate-700">Tambah Admin</span>
                            </a>

                            <a href="#" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500 hover:shadow-md transition text-center group">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-slate-700">Kelola User</span>
                            </a>

                            <a href="#" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500 hover:shadow-md transition text-center group">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-slate-700">Backup DB</span>
                            </a>

                            <a href="#" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500 hover:shadow-md transition text-center group">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-slate-700">Konfigurasi</span>
                            </a>

                        </div>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-800 mb-4">Status Server & Maintenance</h2>
                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <span class="text-slate-500">Versi Aplikasi</span>
                                <span class="font-semibold text-slate-800">v1.0.0</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <span class="text-slate-500">Framework</span>
                                <span class="font-semibold text-slate-800">Laravel 13</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500">Jadwal Maintenance</span>
                                <span class="font-semibold text-amber-600">Minggu, 02:00 WIB</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 3. AUDIT LOG TABLE -->
                <div>
                    <h2 class="text-base font-bold text-slate-800 mb-4">Aktivitas Sistem Terbaru (Audit Log)</h2>
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                    <th class="p-4">Waktu</th>
                                    <th class="p-4">Pengguna</th>
                                    <th class="p-4">Aksi</th>
                                    <th class="p-4">Modul</th>
                                    <th class="p-4">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 text-slate-500">Baru saja</td>
                                    <td class="p-4 font-bold">Super Administrator</td>
                                    <td class="p-4"><span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">LOGIN</span></td>
                                    <td class="p-4">Auth API</td>
                                    <td class="p-4 text-slate-500">127.0.0.1</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 text-slate-500">10 menit lalu</td>
                                    <td class="p-4 font-bold">Admin Sekolah (Budi)</td>
                                    <td class="p-4"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">UPDATE</span></td>
                                    <td class="p-4">Data Siswa</td>
                                    <td class="p-4 text-slate-500">192.168.1.15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>

    </div>

    <!-- SCRIPT TOGGLE DROPDOWN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btnUserDropdown');
            const menu = document.getElementById('userDropdownMenu');

            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

</body>

</html>