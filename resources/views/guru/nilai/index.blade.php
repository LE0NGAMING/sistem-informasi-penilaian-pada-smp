@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian Siswa')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush
@section('content')
<div class="space-y-6">

    {{-- System Flash & Validation Alerts --}}
    @session('error')
    <div class="flex items-center p-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
        <svg class="flex-shrink-0 w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
        </svg>
        <div class="font-medium">{{ $value }}</div>
    </div>
    @endsession

    @if($errors->any())
    <div class="p-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
        <div class="font-bold flex items-center gap-2 mb-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            Gagal menyimpan data:
        </div>
        <ul class="list-disc list-inside ps-2 text-xs space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filter Penilaian
        </h2>
        <form action="{{ route('guru.nilai.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
            <div class="lg:col-span-3">
                <label for="tahun_ajaran_id" class="block mb-1.5 text-xs font-semibold text-slate-600">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" required>
                    <option value="">-- Pilih TA --</option>
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>{{ $ta->tahun }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label for="rombel_id" class="block mb-1.5 text-xs font-semibold text-slate-600">Rombongan Belajar</label>
                <select name="rombel_id" id="rombel_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" required>
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombelList as $rombel)
                    <option value="{{ $rombel->id }}" @selected($rombelId==$rombel->id)>{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="mapel_id" class="block mb-1.5 text-xs font-semibold text-slate-600">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->id }}" @selected($mapelId==$mapel->id)>{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="semester_id" class="block mb-1.5 text-xs font-semibold text-slate-600">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" required>
                    <option value="">-- Pilih Semester --</option>
                    @foreach($semesterList as $semester)
                    <option value="{{ $semester->value }}" @selected($semesterId==$semester->value)>{{ $semester->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-sm transition duration-150 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Form Input Nilai --}}
    @if($rombelId && $mapelId && $semesterId)
    <form x-data="penilaianForm" @submit="handleSubmit($event)" @keydown="navigateTable($event)" action="{{ route('guru.nilai.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @csrf
        <input type="hidden" name="rombel_id" value="{{ $rombelId }}">
        <input type="hidden" name="mapel_id" value="{{ $mapelId }}">
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">

        {{-- Form Header --}}
        <div class="p-6 pb-0 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">Input Penilaian Siswa K13</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    KKM Kelulusan: <span class="font-bold text-slate-700">75</span> | Gunakan tombol <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-800 bg-slate-100 border border-slate-200 rounded-md">Enter</kbd> atau <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-800 bg-slate-100 border border-slate-200 rounded-md">↑</kbd> <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-800 bg-slate-100 border border-slate-200 rounded-md">↓</kbd> untuk navigasi.
                </p>
            </div>

            <a href="{{ route('guru.rekap.index', ['rombel_id' => $rombelId, 'mapel_id' => $mapelId, 'semester_id' => $semesterId]) }}"
                @click="confirmLeave($event)"
                class="inline-flex items-center gap-2 px-3.5 py-2 border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-semibold text-xs rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Lihat Rekap Rombel
            </a>
        </div>

        <div class="p-6">
            {{-- Tabs Component --}}
            <div class="flex p-1 space-x-1 bg-slate-100 rounded-xl mb-6" role="tablist">
                <button type="button" @click="activeTab = 'ki3'" :class="activeTab === 'ki3' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="w-full py-2.5 text-xs sm:text-sm font-bold rounded-lg transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Pengetahuan (KI-3)
                </button>
                <button type="button" @click="activeTab = 'ki4'" :class="activeTab === 'ki4' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="w-full py-2.5 text-xs sm:text-sm font-bold rounded-lg transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2V4zm6 7a2 2 0 100-4 2 2 0 000 4zm-8 0a2 2 0 100-4 2 2 0 000 4zm-4 7a2 2 0 100-4 2 2 0 000 4zm12 0a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    Keterampilan (KI-4)
                </button>
            </div>

            {{-- TAB 1: PENGETAHUAN (KI-3) --}}
            <div x-show="activeTab === 'ki3'" x-cloak>
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-sm text-left text-slate-600 min-w-[1000px]">
                        <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3 w-56">Nama Siswa</th>
                                <th class="px-2 py-3 text-center w-24">Harian<br><span class="text-[10px] lowercase font-normal text-slate-400">(20%)</span></th>
                                <th class="px-2 py-3 text-center w-24">Tugas<br><span class="text-[10px] lowercase font-normal text-slate-400">(20%)</span></th>
                                <th class="px-2 py-3 text-center w-24">Quiz<br><span class="text-[10px] lowercase font-normal text-slate-400">(10%)</span></th>
                                <th class="px-2 py-3 text-center w-24">UTS<br><span class="text-[10px] lowercase font-normal text-slate-400">(25%)</span></th>
                                <th class="px-2 py-3 text-center w-24">UAS<br><span class="text-[10px] lowercase font-normal text-slate-400">(25%)</span></th>
                                <th class="px-2 py-3 text-center w-24">Akhir KI-3</th>
                                <th class="px-2 py-3 text-center w-20">Predikat</th>
                                <th class="px-2 py-3 text-center w-24">Status</th>
                                <th class="px-4 py-3 w-40">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($siswaList as $siswa)
                            @php $nilai = $siswa->penilaian->first(); @endphp
                            <tr x-data="rowKI3(@js([
        'harian' => old(" nilai.{$siswa->id}.nilai_harian", $nilai?->nilai_harian ?? ''),
                                'tugas' => old("nilai.{$siswa->id}.tugas", $nilai?->tugas ?? ''),
                                'quiz' => old("nilai.{$siswa->id}.quiz", $nilai?->quiz ?? ''),
                                'uts' => old("nilai.{$siswa->id}.uts", $nilai?->uts ?? ''),
                                'uas' => old("nilai.{$siswa->id}.uas", $nilai?->uas ?? ''),
                                ]))"
                                :class="{ 'bg-amber-50/60': isRowDirty }"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3 text-center font-semibold text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400">NISN: {{ $siswa->nisn ?? '-' }}</div>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][nilai_harian]" x-model="harian" @input="validateInput('harian'); isDirty = true" @focus="$event.target.select()"
                                        class="w-20 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][tugas]" x-model="tugas" @input="validateInput('tugas'); isDirty = true" @focus="$event.target.select()"
                                        class="w-20 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][quiz]" x-model="quiz" @input="validateInput('quiz'); isDirty = true" @focus="$event.target.select()"
                                        class="w-20 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][uts]" x-model="uts" @input="validateInput('uts'); isDirty = true" @focus="$event.target.select()"
                                        class="w-20 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][uas]" x-model="uas" @input="validateInput('uas'); isDirty = true" @focus="$event.target.select()"
                                        class="w-20 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>

                                <td class="px-2 py-3 text-center font-bold text-indigo-600" x-text="scoreAkhir ?? '-'"></td>

                                <td class="px-2 py-3 text-center">
                                    <span :class="predikatBadge" x-text="predikat" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                                </td>

                                <td class="px-2 py-3 text-center">
                                    <span :class="statusBadge" x-text="statusLabel" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="text" name="nilai[{{ $siswa->id }}][catatan]" @input="isRowDirty = true; isDirty = true"
                                        value="{{ old("nilai.{$siswa->id}.catatan", $nilai?->catatan) }}"
                                        class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-catatan" placeholder="Catatan...">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-10 text-slate-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Tidak ada data siswa ditemukan untuk rombel ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: KETERAMPILAN (KI-4) --}}
            <div x-show="activeTab === 'ki4'" x-cloak>
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-sm text-left text-slate-600 min-w-[900px]">
                        <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3 w-64">Nama Siswa</th>
                                <th class="px-3 py-3 text-center w-28">Praktik<br><span class="text-[10px] lowercase font-normal text-slate-400">(40%)</span></th>
                                <th class="px-3 py-3 text-center w-28">Proyek<br><span class="text-[10px] lowercase font-normal text-slate-400">(30%)</span></th>
                                <th class="px-3 py-3 text-center w-28">Portofolio<br><span class="text-[10px] lowercase font-normal text-slate-400">(30%)</span></th>
                                <th class="px-3 py-3 text-center w-28">Akhir KI-4</th>
                                <th class="px-3 py-3 text-center w-24">Predikat</th>
                                <th class="px-4 py-3 text-center w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($siswaList as $siswa)
                            @php $nilai = $siswa->penilaian->first(); @endphp
                            <tr x-data="rowKI4(@js([
        'praktik'    => old(" nilai.{$siswa->id}.praktik", $nilai?->praktik ?? ''),
                                'proyek' => old("nilai.{$siswa->id}.proyek", $nilai?->proyek ?? ''),
                                'portofolio' => old("nilai.{$siswa->id}.portofolio", $nilai?->portofolio ?? ''),
                                ]))"
                                :class="{ 'bg-amber-50/60': isRowDirty }"
                                class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3 text-center font-semibold text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400">NISN: {{ $siswa->nisn ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][praktik]" x-model="praktik" @input="validateInput('praktik'); isDirty = true" @focus="$event.target.select()"
                                        class="w-24 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][proyek]" x-model="proyek" @input="validateInput('proyek'); isDirty = true" @focus="$event.target.select()"
                                        class="w-24 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                        name="nilai[{{ $siswa->id }}][portofolio]" x-model="portofolio" @input="validateInput('portofolio'); isDirty = true" @focus="$event.target.select()"
                                        class="w-24 px-2 py-1.5 text-center text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 input-score" placeholder="0">
                                </td>

                                <td class="px-3 py-3 text-center font-bold text-indigo-600" x-text="scoreAkhir ?? '-'"></td>

                                <td class="px-3 py-3 text-center">
                                    <span :class="predikatBadge" x-text="predikat" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span :class="statusBadge" x-text="statusLabel" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-slate-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Tidak ada data siswa ditemukan untuk rombel ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Form Footer --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs flex items-center gap-1.5">
                <template x-if="!isDirty">
                    <span class="text-slate-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Belum ada perubahan data.
                    </span>
                </template>
                <template x-if="isDirty">
                    <span class="text-amber-600 font-semibold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Ada perubahan yang belum disimpan!
                    </span>
                </template>
            </span>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm py-2.5 px-5 rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Semua Penilaian
            </button>
        </div>
    </form>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 text-center py-12 px-4">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-base font-bold text-slate-800">Silakan Filter Terlebih Dahulu</h3>
        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Pilih Rombel, Mata Pelajaran, dan Semester di atas untuk menampilkan daftar siswa.</p>
    </div>
    @endif
</div>
@endsection