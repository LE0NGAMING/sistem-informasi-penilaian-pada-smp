@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- Header Salam & Info Pengajar --}}
    <div class="bg-indigo-600 rounded-2xl shadow-sm text-white p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-indigo-100 text-sm flex items-center flex-wrap gap-2">
                <span>NIP: {{ $guru->nip ?? '-' }}</span>
                <span>&bull;</span>
                <span>Mata Pelajaran Utama:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white text-indigo-700">
                    {{ $guru->mata_pelajaran ?? 'Pendidik' }}
                </span>
            </p>
        </div>
        @if($rombelWali)
        <div class="bg-white/10 border border-white/20 rounded-xl p-4 text-right backdrop-blur-sm self-start md:self-auto">
            <span class="text-[10px] uppercase tracking-wider text-indigo-200 font-semibold block">Status Wali Kelas</span>
            <span class="text-base sm:text-lg font-bold flex items-center gap-1.5 justify-end mt-0.5">
                <svg class="w-4 h-4 text-amber-300 fill-amber-300" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                {{ $rombelWali->nama_rombel }}
            </span>
        </div>
        @endif
    </div>

    {{-- Cards Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card Kelas Mengajar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Kelas Mengajar</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalKelas }} Kelas</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>

        {{-- Card Total Siswa Diampu --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Total Siswa Diampu</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalSiswaDiampu }} Siswa</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Card Status Progress Penilaian --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Status Penilaian</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $kelasSelesai }} / {{ $totalKelas }} <span class="text-xs font-normal text-gray-500">Selesai</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Card Status Wali Kelas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Tugas Wali Kelas</span>
                <h3 class="text-lg font-bold text-gray-800 truncate max-w-[170px]" title="{{ $rombelWali ? $rombelWali->nama_rombel : 'Tidak Ada' }}">{{ $rombelWali ? $rombelWali->nama_rombel : 'Tidak Ada' }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Tabel Ringkasan Mengajar & Status Input Nilai --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Status Input Nilai Per Kelas
                    </h2>
                </div>
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-[10px] font-semibold tracking-wider border-b border-gray-100">
                                <th class="py-3 px-4 pl-6">Mata Pelajaran</th>
                                <th class="py-3 px-4">Rombel / Kelas</th>
                                <th class="py-3 px-4">Input Siswa</th>
                                <th class="py-3 px-4">Progress Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse($kelasList as $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4 pl-6 font-semibold text-gray-800">{{ $item->mapel }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $item->rombel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">{{ $item->jumlah_terisi }} / {{ $item->jumlah_siswa }} Siswa</td>
                                <td class="py-3.5 px-4 w-1/3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="@if($item->progress == 100) bg-emerald-500 @elseif($item->progress > 0) bg-amber-500 @else bg-rose-500 @endif h-full rounded-full transition-all duration-300"
                                                style="width: {{ $item->progress }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-mono font-semibold text-gray-600 min-w-[35px] text-right">{{ $item->progress }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-400 text-sm">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    Belum ada data kelas yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Akses Cepat & Menu Wali Kelas --}}
        <div class="lg:col-span-4 space-y-6">
            @if($rombelWali)
            {{-- Panel Khusus Wali Kelas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-amber-500 p-6">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Menu Wali Kelas ({{ $rombelWali->nama_rombel }})
                </h3>
                <div class="space-y-2">
                    <a href="#" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-amber-200 text-amber-900 bg-amber-50/50 hover:bg-amber-100/60 font-medium text-xs transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Kelola Absensi & Sikap Siswa
                    </a>
                    <a href="#" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-amber-200 text-amber-900 bg-amber-50/50 hover:bg-amber-100/60 font-medium text-xs transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Rapor Digital Kelas
                    </a>
                </div>
            </div>
            @endif

            {{-- Panel Akses Cepat Guru --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    Akses Cepat Penilaian
                </h3>
                <div class="space-y-2">
                    <a href="#" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-indigo-100 text-indigo-700 bg-indigo-50/40 hover:bg-indigo-50 font-medium text-xs transition">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Input Nilai Formatif & Sumatif
                    </a>
                    <a href="#" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-gray-50 hover:bg-gray-100 font-medium text-xs transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat Rekapitulasi Nilai Mapel
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection