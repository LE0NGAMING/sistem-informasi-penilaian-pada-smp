@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Input Nilai Ekstrakurikuler</h1>
            <p class="text-sm text-slate-500 mt-1">
                Rombongan Belajar: <span class="font-semibold text-indigo-600">{{ $rombel->nama_rombel }}</span>
            </p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-4 text-sm font-semibold text-slate-700">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filter Penilaian
        </div>
        <form action="{{ route('walikelas.ekskul.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
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
                    <option value="ganjil" @selected($semester=='ganjil' )>Ganjil</option>
                    <option value="genap" @selected($semester=='genap' )>Genap</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Form Input Nilai Ekskul --}}
    <form action="{{ route('walikelas.ekskul.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 border-b border-slate-200/80 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4 text-center w-12">No</th>
                            <th class="py-3.5 px-4">Nama Siswa / NISN</th>
                            <th class="py-3.5 px-4 w-60">Kegiatan Ekstrakurikuler</th>
                            <th class="py-3.5 px-4 text-center w-28">Predikat</th>
                            <th class="py-3.5 px-4">Keterangan / Deskripsi Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @php $ekskulCount = count($ekskulList); @endphp
                        @forelse($siswaList as $siswa)
                        @foreach($ekskulList as $ekskulIndex => $ekskul)
                        @php
                        $nilaiExist = $siswa->nilaiEkskul->firstWhere('ekstrakurikuler_id', $ekskul->id);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            {{-- Nomor & Nama hanya tampil di baris pertama tiap siswa --}}
                            @if($ekskulIndex === 0)
                            <td class="py-4 px-4 text-center font-semibold text-slate-500 align-top border-r border-slate-100" rowspan="{{ $ekskulCount }}">
                                {{ $loop->parent->iteration }}
                            </td>
                            <td class="py-4 px-4 align-top border-r border-slate-100" rowspan="{{ $ekskulCount }}">
                                <div class="font-semibold text-slate-900">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            @endif

                            {{-- Nama Ekskul --}}
                            <td class="py-3 px-4 font-medium text-slate-700 border-r border-slate-100/60">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span>{{ $ekskul->nama_ekskul }}</span>
                                </div>
                            </td>

                            {{-- Input Predikat --}}
                            <td class="py-3 px-4 border-r border-slate-100/60">
                                <select name="nilai[{{ $siswa->id }}][{{ $ekskul->id }}][predikat]"
                                    class="w-full rounded-xl border-slate-200 text-sm font-bold text-center focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 py-1.5">
                                    <option value="">--</option>
                                    @foreach(['A', 'B', 'C', 'D'] as $p)
                                    <option value="{{ $p }}" @selected(old("nilai.{$siswa->id}.{$ekskul->id}.predikat", $nilaiExist?->predikat) == $p)>
                                        {{ $p }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Input Keterangan --}}
                            <td class="py-3 px-4">
                                <input type="text"
                                    name="nilai[{{ $siswa->id }}][{{ $ekskul->id }}][keterangan]"
                                    class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 py-1.5 px-3 placeholder:text-slate-300"
                                    placeholder="Contoh: Sangat aktif dalam kegiatan keanggotaan..."
                                    value="{{ old("nilai.{$siswa->id}.{$ekskul->id}.keterangan", $nilaiExist?->keterangan) }}">
                            </td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="font-medium text-slate-600">Belum ada siswa di rombel ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswaList->isNotEmpty())
            <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition shadow-sm shadow-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Nilai Ekstrakurikuler
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection