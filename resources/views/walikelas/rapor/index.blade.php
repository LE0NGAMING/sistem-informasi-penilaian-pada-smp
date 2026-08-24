@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar & Status Rapor Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">
                Rombongan Belajar: <span class="font-semibold text-indigo-600">{{ $rombel->nama_rombel }}</span>
            </p>
        </div>

        @if($siswaList->isNotEmpty())
        <div>
            <a href="{{ route('walikelas.rapor.rombel', ['rombel' => $rombel->id, 'semester_id' => $semesterId]) }}"
                target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-medium text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Rapor Semua Siswa (Kolektif)
            </a>
        </div>
        @endif
    </div>

    {{-- Alert System --}}
    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6">
        <form action="{{ route('walikelas.rapor.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-5">
                <label for="tahun_ajaran_id" class="block text-xs font-medium text-slate-600 mb-1.5">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 py-2.5">
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>
                        {{ $ta->tahun }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5">
                <label for="semester" class="block text-xs font-medium text-slate-600 mb-1.5">Semester</label>
                <select name="semester" id="semester" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 py-2.5">
                    <option value="ganjil" @selected($semesterId=='ganjil' )>Ganjil</option>
                    <option value="genap" @selected($semesterId=='genap' )>Genap</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Monitoring & Akses Cetak Rapor --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-600 border-b border-slate-200/80 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">NISN / Nama Siswa</th>
                        <th class="py-3.5 px-4 text-center">Kelengkapan Nilai Mapel</th>
                        <th class="py-3.5 px-4 text-center">Nilai Ekskul</th>
                        <th class="py-3.5 px-4 text-center">Data Presensi</th>
                        <th class="py-3.5 px-4 text-center w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($siswaList as $siswa)
                    @php
                    $totalNilaiTerisi = $siswa->penilaian?->count() ?? 0;
                    $isNilaiComplete = $totalNilaiTerisi >= $totalMapel && $totalMapel > 0;
                    $hasEkskul = $siswa->nilaiEkskul?->isNotEmpty() ?? false;
                    $hasPresensi = $siswa->presensiHarian?->isNotEmpty() ?? false;
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-4 text-center font-semibold text-slate-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-slate-900">{{ $siswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</div>
                        </td>

                        {{-- Indicator Nilai Mapel --}}
                        <td class="py-4 px-4 text-center">
                            @if($isNilaiComplete)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Lengkap ({{ $totalNilaiTerisi }}/{{ $totalMapel }})
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Belum Lengkap ({{ $totalNilaiTerisi }}/{{ $totalMapel }})
                            </span>
                            @endif
                        </td>

                        {{-- Indicator Ekskul --}}
                        <td class="py-4 px-4 text-center">
                            @if($hasEkskul)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/80">
                                <svg class="w-3.5 h-3.5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Ada Data
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                Belum Ada
                            </span>
                            @endif
                        </td>

                        {{-- Indicator Presensi --}}
                        <td class="py-4 px-4 text-center">
                            @if($hasPresensi)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Terisi
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                Belum Ada
                            </span>
                            @endif
                        </td>

                        {{-- Action Button --}}
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('walikelas.rapor.siswa', ['siswa' => $siswa->id, 'semester_id' => $semesterId]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium text-xs rounded-lg transition border border-indigo-200/60 shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Rapor
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="font-medium text-slate-600">Belum ada siswa terdaftar di rombel ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection