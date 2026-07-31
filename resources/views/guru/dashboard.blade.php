@extends('layouts.app') {{-- Menyesuaikan dengan layout utama aplikasi Anda --}}

@section('content')
<main class="p-6 md:p-8 space-y-8 bg-slate-50 min-h-screen">

    <!-- HEADER GURU -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                    Guru Pengajar
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-medium">Matematika — Kelas VIII & IX</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, Budi Santoso, S.Pd.</h1>
            <p class="text-xs text-slate-500 mt-1">Siap untuk mengajar hari ini? Cek jadwal dan tugas pending Anda di bawah ini.</p>
        </div>

        <!-- Tanggal & Ikon Buku (Diperbaiki) -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-semibold text-slate-700">Jumat, 31 Juli 2026</p>
                <p class="text-[11px] text-slate-400">T.A. 2026/2027 (Ganjil)</p>
            </div>
            <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                <!-- Ditambahkan width="20" height="20" dan style agar ukurannya dikunci -->
                <svg class="w-5 h-5" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 2. METRICS / STATISTIK GURU -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Metric 1: Jam Mengajar Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Jadwal Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">3 <span class="text-xs font-normal text-slate-400">Kelas</span></h3>
                <p class="text-[11px] text-indigo-600 font-medium mt-1">6 Jam Pelajaran (JP)</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Metric 2: Total Siswa Diampu -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Siswa Diampu</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">192 <span class="text-xs font-normal text-slate-400">Siswa</span></h3>
                <p class="text-[11px] text-slate-400 mt-1">Tersebar di 6 Rombel</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Metric 3: Tugas Belum Dinilai -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Perlu Dinilai</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">28 <span class="text-xs font-normal text-slate-400">Tugas</span></h3>
                <p class="text-[11px] text-amber-600 font-medium mt-1">Dari 2 Tugas Harian</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Metric 4: Kehadiran Siswa Kelas Ajar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Presensi Mengajar</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">100%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Jadwal Minggu Ini Tuntas</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- 3. SHORTCUT AKSES CEPAT -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Akses Cepat Guru</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="#" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-indigo-50 hover:border-indigo-200 transition group flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-indigo-700">Input Nilai Siswa</span>
                <span class="text-[10px] text-slate-400 mt-0.5">UH, UTS, UAS</span>
            </a>

            <a href="#" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-emerald-50 hover:border-emerald-200 transition group flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-emerald-700">Absensi Kelas</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Catat Kehadiran</span>
            </a>

            <a href="#" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-blue-50 hover:border-blue-200 transition group flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Buat Tugas Baru</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Unggah Soal / PR</span>
            </a>

            <a href="#" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-purple-50 hover:border-purple-200 transition group flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-purple-700">Bank Soal & Materi</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Modul & PPT</span>
            </a>
        </div>
    </div>

    <!-- 4. KONTEN UTAMA (GRID 2 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI (2/3): JADWAL MENGAJAR & TUGAS PERLU DINILAI -->
        <div class="lg:col-span-2 space-y-8">

            <!-- SECTION: JADWAL MENGAJAR HARI INI -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Jadwal Mengajar Hari Ini</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar kelas yang harus diampu pada hari Jumat</p>
                    </div>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                        3 Sesi
                    </span>
                </div>

                <div class="divide-y divide-slate-100">

                    <!-- Sesi 1 (Berjalan/Aktif) -->
                    <div class="p-4 bg-indigo-50/40 hover:bg-indigo-50/70 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-l-4 border-indigo-600">
                        <div class="flex items-start gap-3">
                            <div class="px-2.5 py-1.5 bg-indigo-600 text-white rounded-lg text-center shrink-0">
                                <p class="text-xs font-bold">07:30</p>
                                <p class="text-[10px] opacity-80">09:00</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs font-bold text-slate-800">Matematika Wajib — VIII A</h3>
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded">Berlangsung</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">Materi: <span class="font-medium text-slate-700">Sistem Persamaan Linear Dua Variabel (SPLDV)</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Ruang: Kelas VIII-A (Lantai 2) • 32 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <a href="#" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">Isi Absensi</a>
                            <a href="#" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">Nilai</a>
                        </div>
                    </div>

                    <!-- Sesi 2 -->
                    <div class="p-4 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="px-2.5 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-center shrink-0">
                                <p class="text-xs font-bold">09:30</p>
                                <p class="text-[10px] text-slate-400">11:00</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Matematika Wajib — VIII C</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Materi: <span class="font-medium text-slate-700">Teorema Pythagoras</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Ruang: Kelas VIII-C (Lantai 2) • 32 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <a href="#" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">Absensi</a>
                            <a href="#" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">Nilai</a>
                        </div>
                    </div>

                    <!-- Sesi 3 -->
                    <div class="p-4 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="px-2.5 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-center shrink-0">
                                <p class="text-xs font-bold">13:00</p>
                                <p class="text-[10px] text-slate-400">14:30</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Matematika Peminatan — IX B</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Materi: <span class="font-medium text-slate-700">Persamaan & Fungsi Kuadrat</span></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Ruang: Lab Komputer 1 • 30 Siswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <a href="#" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">Absensi</a>
                            <a href="#" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">Nilai</a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SECTION: TUGAS MENUNGGU PERIKSA (PENDING GRADING) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Tugas & Ujian Perlu Periksa</h2>
                        <p class="text-xs text-slate-400">Daftar pengumpulan tugas siswa yang belum diberi nilai</p>
                    </div>
                    <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline">Kelola Semua Tugas</a>
                </div>

                <div class="space-y-3">
                    <!-- Item 1 -->
                    <div class="p-3.5 border border-slate-100 rounded-xl hover:bg-slate-50 transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">Latihan 2: Grafik Fungsi Kuadrat</h3>
                                <p class="text-[11px] text-slate-500">Kelas IX B • <span class="text-amber-600 font-semibold">18 / 30 Siswa Masuk</span></p>
                            </div>
                        </div>
                        <a href="#" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg transition">Koreksi</a>
                    </div>

                    <!-- Item 2 -->
                    <div class="p-3.5 border border-slate-100 rounded-xl hover:bg-slate-50 transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800">PR 1: Soal Cerita SPLDV</h3>
                                <p class="text-[11px] text-slate-500">Kelas VIII A • <span class="text-amber-600 font-semibold">10 / 32 Siswa Masuk</span></p>
                            </div>
                        </div>
                        <a href="#" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">Koreksi</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (1/3): PENGUMUMAN & KALENDER AKADEMIK -->
        <div class="space-y-8">

            <!-- Pengumuman Kurikulum / Kepala Sekolah -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800">Pengumuman Sekolah</h2>
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded">Terbaru</span>
                </div>

                <div class="space-y-4">
                    <!-- Info 1 -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-700 mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <span>Batas Input Nilai Formatif 1</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-snug">
                            Penginputan nilai formatif pertama wajib diselesaikan sebelum tanggal **10 Agustus 2026**.
                        </p>
                        <p class="text-[10px] text-slate-400 mt-2">— Waka Kurikulum</p>
                    </div>

                    <!-- Info 2 -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-700 mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Rapat MGMP Sekolah</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-snug">
                            Rapat Musyawarah Guru Mata Pelajaran (MGMP) diadakan hari **Rabu, 5 Agustus 2026** di Ruang Guru.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rekap Nilai Cepat -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Status e-Rapor</h3>
                    <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 text-[10px] font-semibold rounded border border-emerald-500/30">Aktif</span>
                </div>
                <p class="text-xs text-slate-300 mb-4">
                    Progres pengisian nilai rapor semester ganjil Anda saat ini:
                </p>

                <!-- Progress Bar -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold">
                        <span>Terisi</span>
                        <span class="text-indigo-300">45%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-400 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                <a href="#" class="mt-5 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl transition">
                    <span>Buka Lembar e-Rapor</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

        </div>

    </div>

</main>
@endsection