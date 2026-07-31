@extends('layouts.app')

@section('content')
<main class="p-6 md:p-8 space-y-8 bg-slate-50 min-h-screen">

    <!-- 1. HEADER & UCAPAN SELAMAT DATANG -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-teal-100 text-teal-800 text-xs font-semibold rounded-full">
                    Kepala Sekolah
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-medium">T.A. 2026/2027 — Semester Ganjil</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, Dr. H. Ahmad Dahlan, M.Pd.</h1>
            <p class="text-xs text-slate-500 mt-1">Berikut adalah ringkasan aktivitas dan performa sekolah hari ini.</p>
        </div>

        <!-- Tanggal Hari Ini & Quick Action -->
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-semibold text-slate-700">Jumat, 31 Juli 2026</p>
                <p class="text-[11px] text-slate-400">SMP Negeri 1 Jakarta</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 border border-teal-100 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK UTAMA (METRICS CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Card 1: Total Siswa -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Siswa Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">852</h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-1 inline-flex items-center gap-1">
                    <span>↑ 98% Laki & Perempuan</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Guru & Staf -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Guru & Tendik</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">54 <span class="text-xs font-normal text-slate-400">Orang</span></h3>
                <p class="text-[11px] text-slate-500 mt-1">42 Guru • 12 Staf TU</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Kehadiran Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Kehadiran Siswa Hari Ini</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">96.4%</h3>
                <p class="text-[11px] text-slate-400 mt-1">821 Hadir • 31 Sakit/Izin</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Persetujuan Menunggu (Approval) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Perlu Persetujuan</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">3 <span class="text-xs font-normal text-slate-400">Pengajuan</span></h3>
                <p class="text-[11px] text-amber-600 font-medium mt-1">Membutuhkan TTD / ACC</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- 3. KONTEN UTAMA (GRID 2 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI (2/3): LAPORAN & KEHADIRAN KELAS -->
        <div class="lg:col-span-2 space-y-8">

            <!-- SECTION: Pengajuan & Laporan Menunggu Persetujuan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Dokumen & Pengajuan Perlu Persetujuan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Permohonan izin kegiatan, RKP, atau dispensasi</p>
                    </div>
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
                        3 Pending
                    </span>
                </div>

                <div class="divide-y divide-slate-100">

                    <!-- Item 1 -->
                    <div class="p-4 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Proposal Classmeeting & Pentas Seni</h3>
                                <p class="text-[11px] text-slate-500">Diajukan oleh: <span class="font-semibold text-slate-700">Pembina OSIS (Budi Santoso, S.Pd.)</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">30 Juli 2026 • Anggaran: Rp 4.500.000</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition cursor-pointer">Setujui</button>
                            <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">Detail</button>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="p-4 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Permohonan Pengadaan Modul Ajar Kurikulum Merdeka</h3>
                                <p class="text-[11px] text-slate-500">Diajukan oleh: <span class="font-semibold text-slate-700">Waka Kurikulum</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">29 Juli 2026 • Untuk Kelas VII, VIII, IX</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition cursor-pointer">Setujui</button>
                            <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">Detail</button>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="p-4 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Laporan Realisasi Dana BOS Tahap 1</h3>
                                <p class="text-[11px] text-slate-500">Diajukan oleh: <span class="font-semibold text-slate-700">Bendahara Sekolah</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">28 Juli 2026 • Laporan Verifikasi Akhir</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition cursor-pointer">Setujui</button>
                            <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">Detail</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SECTION: Ringkasan Kehadiran per Angkatan -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Ringkasan Kehadiran Siswa Hari Ini</h2>
                        <p class="text-xs text-slate-400">Persentase kehadiran berdasarkan tingkat kelas</p>
                    </div>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Detail Rekap</a>
                </div>

                <div class="space-y-4">
                    <!-- Kelas VII -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Kelas VII (280 Siswa)</span>
                            <span class="text-emerald-600">98% (274 Hadir)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 98%"></div>
                        </div>
                    </div>

                    <!-- Kelas VIII -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Kelas VIII (285 Siswa)</span>
                            <span class="text-emerald-600">95% (270 Hadir)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>

                    <!-- Kelas IX -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">Kelas IX (287 Siswa)</span>
                            <span class="text-emerald-600">96.5% (277 Hadir)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 96.5%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (1/3): AGENDA, STATISTIK GURU & PENGUMUMAN -->
        <div class="space-y-8">

            <!-- Absensi Guru Hari Ini -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Kehadiran Guru & Staf</h2>
                <div class="grid grid-cols-3 gap-2 text-center mb-4">
                    <div class="p-2.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <p class="text-lg font-bold text-emerald-700">40</p>
                        <p class="text-[10px] text-emerald-600 font-medium">Hadir</p>
                    </div>
                    <div class="p-2.5 bg-amber-50 border border-amber-100 rounded-xl">
                        <p class="text-lg font-bold text-amber-700">2</p>
                        <p class="text-[10px] text-amber-600 font-medium">Izin/Sakit</p>
                    </div>
                    <div class="p-2.5 bg-rose-50 border border-rose-100 rounded-xl">
                        <p class="text-lg font-bold text-rose-700">0</p>
                        <p class="text-[10px] text-rose-600 font-medium">Alpha</p>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 text-center">Guru piket hari ini: <span class="font-semibold text-slate-700">Siti Nurhaliza, S.Pd.</span></p>
            </div>

            <!-- Agenda & Kegiatan Mendatang -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800">Agenda Sekolah</h2>
                    <span class="text-[10px] font-semibold text-slate-400">Pekan Ini</span>
                </div>

                <div class="space-y-3">
                    <!-- Agenda 1 -->
                    <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg text-center shrink-0 min-w-[42px]">
                            <p class="text-xs font-bold">01</p>
                            <p class="text-[9px] uppercase font-semibold">Agt</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-slate-800">Rapat Koordinasi Evaluasi Bulanan</h3>
                            <p class="text-[11px] text-slate-400">Ruang Rapat Utama • 09:00 WIB</p>
                        </div>
                    </div>

                    <!-- Agenda 2 -->
                    <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg text-center shrink-0 min-w-[42px]">
                            <p class="text-xs font-bold">04</p>
                            <p class="text-[9px] uppercase font-semibold">Agt</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-slate-800">Supervisi Akademik Guru IPA & Matematika</h3>
                            <p class="text-[11px] text-slate-400">Kelas VIII-A & VIII-B</p>
                        </div>
                    </div>

                    <!-- Agenda 3 -->
                    <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition">
                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg text-center shrink-0 min-w-[42px]">
                            <p class="text-xs font-bold">08</p>
                            <p class="text-[9px] uppercase font-semibold">Agt</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-slate-800">Sosialisasi Program Komite Sekolah</h3>
                            <p class="text-[11px] text-slate-400">Aula Sekolah • Bersama Orang Tua</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan / Pengumuman Singkat -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white p-5 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-amber-400" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pengingat Supervisi</h2>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Jadwal pengisian instrumen penilaian kinerja guru (PKG) semester ganjil dapat dipantau langsung melalui menu Akademik.
                </p>
            </div>

        </div>

    </div>

</main>

@endsection