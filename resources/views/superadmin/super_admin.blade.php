@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">
    <!-- 1. STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
            <p class="text-sm font-medium text-slate-500">Total Admin Sekolah</p>
            <p class="text-3xl font-bold text-slate-800 my-1">{{ $totalAdmin ?? 2 }}</p>
            <span class="text-xs text-emerald-600 font-medium">Aktif Mengelola</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
            <p class="text-sm font-medium text-slate-500">Total Pengguna Terdaftar</p>
            <p class="text-3xl font-bold text-slate-800 my-1">{{ $totalUser ?? 18 }}</p>
            <span class="text-xs text-slate-400">Guru, Staff & Siswa</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
            <p class="text-sm font-medium text-slate-500">Status Database</p>
            <p class="text-3xl font-bold text-emerald-600 my-1">Normal</p>
            <span class="text-xs text-slate-400">Backup Otomatis Aktif</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
            <p class="text-sm font-medium text-slate-500">Keamanan Sistem</p>
            <p class="text-3xl font-bold text-slate-800 my-1">Aman</p>
            <span class="text-xs text-emerald-600 font-medium">OWASP Standard Validated</span>
        </div>
    </div>

    <!-- 2. QUICK ACTIONS & MAINTENANCE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Aksi Cepat -->
        <div class="lg:col-span-2 space-y-3">
            <h2 class="text-lg font-bold text-slate-800">Aksi Cepat Administrator</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#" class="group bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-500 hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-sm text-slate-700 group-hover:text-blue-700 transition">Tambah Admin</span>
                </a>

                <a href="{{ route('superadmin.admin-sekolah.index') }}" class="group bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-500 hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-sm text-slate-700 group-hover:text-blue-700 transition">Kelola User</span>
                </a>

                <a href="#" class="group bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-500 hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-sm text-slate-700 group-hover:text-blue-700 transition">Backup DB</span>
                </a>

                <a href="#" class="group bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-500 hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-sm text-slate-700 group-hover:text-blue-700 transition">Konfigurasi</span>
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Status Server -->
        <div class="space-y-3">
            <h2 class="text-lg font-bold text-slate-800">Status Server & Maintenance</h2>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-3">
                    <span class="text-slate-500">Versi Aplikasi</span>
                    <span class="font-semibold text-slate-800">v1.0.0</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-3">
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
    <div class="space-y-3">
        <h2 class="text-lg font-bold text-slate-800">Aktivitas Sistem Terbaru (Audit Log)</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-5 py-4">Waktu</th>
                        <th class="px-5 py-4">Pengguna</th>
                        <th class="px-5 py-4">Aksi</th>
                        <th class="px-5 py-4">Modul</th>
                        <th class="px-5 py-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 text-slate-500">Baru saja</td>
                        <td class="px-5 py-4 font-bold text-slate-800">Super Administrator</td>
                        <td class="px-5 py-4"><span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold tracking-wide">LOGIN</span></td>
                        <td class="px-5 py-4">Auth API</td>
                        <td class="px-5 py-4 text-slate-500">127.0.0.1</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 text-slate-500">10 menit lalu</td>
                        <td class="px-5 py-4 font-bold text-slate-800">Admin Sekolah (Budi)</td>
                        <td class="px-5 py-4"><span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold tracking-wide">UPDATE</span></td>
                        <td class="px-5 py-4">Data Siswa</td>
                        <td class="px-5 py-4 text-slate-500">192.168.1.15</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection