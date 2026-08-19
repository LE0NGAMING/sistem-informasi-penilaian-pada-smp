@extends('layouts.app')

@section('title', 'Dashboard Admin Sekolah')
@section('page-title', 'Dashboard Admin Sekolah')

@section('content')
<div class="space-y-6">

    <!-- Header Salam / Welcome Banner -->
    <div class="bg-blue-600 rounded-xl shadow-sm text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-white/10 to-transparent"></div>
        <div class="p-6 md:p-8 relative z-10">
            <h4 class="text-2xl font-bold mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h4>
            <p class="text-blue-100 text-sm md:text-base max-w-2xl">
                Kelola data master sekolah, pengguna, dan pemantauan sistem penilaian dari panel kontrol ini.
            </p>
        </div>
    </div>

    <!-- Cards Statistik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <x-stat-card label="Total Siswa" :value="number_format($totalSiswa)" icon="bi bi-people-fill" color="blue" />
        <x-stat-card label="Total Guru" :value="number_format($totalGuru)" icon="bi bi-person-badge-fill" color="emerald" />
        <x-stat-card label="Rombel / Kelas" :value="number_format($totalRombel)" icon="bi bi-door-open-fill" color="amber" />
        <x-stat-card label="Akun Pengguna" :value="number_format($totalUser)" icon="bi bi-shield-lock-fill" color="cyan" />
    </div>

    <!-- Layout Grid: Tabel (Kiri) & Sidebar (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- Tabel Akun Pengguna Terbaru (Span 2 kolom) -->
        <div class="lg:col-span-2">
            <x-card title="Pengguna Terbaru Terdaftar" icon="bi bi-person-plus-fill">
                <!-- Slot Action: Tombol di sudut kanan atas header -->
                <x-slot:action>
                    <a href="#" class="text-sm bg-white border border-slate-300 text-slate-600 px-3 py-1.5 rounded-lg hover:bg-slate-50 font-medium transition">
                        Lihat Semua
                    </a>
                </x-slot:action>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nama</th>
                                <th class="px-6 py-3 font-medium">Email</th>
                                <th class="px-6 py-3 font-medium">Role Access</th>
                                <th class="px-6 py-3 font-medium">Tanggal Buat</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse ($latestUsers as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-slate-500">{{ $user->email }}</td>
                                <td class="px-6 py-3">
                                    <!-- Logika warna badge dinamis menggunakan match() bawaan PHP 8 -->
                                    @php
                                    $roleValue = $user->role->value ?? $user->role;
                                    $badgeColor = match($roleValue) {
                                    'admin_sekolah' => 'red',
                                    'guru' => 'emerald',
                                    'siswa' => 'blue',
                                    'orang_tua' => 'amber',
                                    'kepala_sekolah' => 'cyan',
                                    default => 'slate',
                                    };
                                    @endphp

                                    <x-badge :color="$badgeColor">
                                        {{ ucfirst(str_replace('_', ' ', $roleValue)) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-3 text-slate-500">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <!-- Sidebar / Kolom Kanan -->
        <div class="space-y-6">

            <!-- Card Distribusi Role -->
            <x-card title="Distribusi Role Akun" icon="bi bi-pie-chart-fill">
                <div class="p-2">
                    <ul class="divide-y divide-slate-50">
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="bi bi-shield-check text-red-500"></i> Admin Sekolah
                            </span>
                            <span class="bg-red-50 text-red-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $roleCounts['admin_sekolah'] ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="bi bi-person-badge text-emerald-500"></i> Guru
                            </span>
                            <span class="bg-emerald-50 text-emerald-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $roleCounts['guru'] ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="bi bi-people text-blue-500"></i> Siswa
                            </span>
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $roleCounts['siswa'] ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="bi bi-heart text-amber-500"></i> Orang Tua
                            </span>
                            <span class="bg-amber-50 text-amber-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $roleCounts['orang_tua'] ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="bi bi-award text-cyan-500"></i> Kepala Sekolah
                            </span>
                            <span class="bg-cyan-50 text-cyan-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $roleCounts['kepala_sekolah'] ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </x-card>

            <!-- Card Pintasan Akses Cepat -->
            <x-card title="Akses Cepat" icon="bi bi-lightning-charge-fill">
                <div class="p-4 flex flex-col gap-3">
                    <x-quick-link :href="Route::has('admin.siswa.create') ? route('admin.siswa.create') : '#'" icon="bi bi-person-plus-fill" iconColor="text-blue-500">
                        Tambah Data Siswa
                    </x-quick-link>

                    <x-quick-link :href="Route::has('admin.guru.create') ? route('admin.guru.create') : '#'" icon="bi bi-person-badge-fill" iconColor="text-emerald-500">
                        Tambah Data Guru
                    </x-quick-link>

                    <x-quick-link :href="Route::has('admin.mapel.create') ? route('admin.mapel.create') : '#'" icon="bi bi-book-half" iconColor="text-cyan-500">
                        Tambah Data Mata Pelajaran
                    </x-quick-link>

                    <x-quick-link :href="Route::has('admin.kelas.create') ? route('admin.kelas.create') : '#'" icon="bi bi-house-add-fill" iconColor="text-slate-400">
                        Tambah Data Kelas
                    </x-quick-link>

                    <x-quick-link href="#" icon="bi bi-calendar3" iconColor="text-slate-400">
                        Kelola Tahun Akademik
                    </x-quick-link>
                </div>
            </x-card>

        </div>
    </div>

</div>
@endsection