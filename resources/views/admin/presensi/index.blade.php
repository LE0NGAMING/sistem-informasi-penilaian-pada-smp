@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Presensi Siswa</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pemantauan presensi otomatis (Mesin/TAP) dan koreksi manual.</p>
        </div>
        <a href="{{ route('admin.presensi.rekap') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-file-earmark-bar-graph text-indigo-600"></i> Rekapitulasi Rapor
        </a>
    </div>

    {{-- Alert Notifikasi --}}
    @session('success')
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex gap-3 items-center">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
            <p class="text-sm font-medium text-emerald-800">{{ $value }}</p>
        </div>
        <button type="button" class="text-emerald-600 hover:text-emerald-800 transition" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endsession

    {{-- Form Filter Kompak & Proporsional --}}
    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.presensi.index') }}" method="GET" class="flex flex-wrap items-end gap-4">

            {{-- Pilih Kelas --}}
            <div class="w-full sm:w-80">
                <label for="rombel_id" class="block text-sm font-medium text-slate-700 mb-1.5">Pilih Kelas <span class="text-red-500">*</span></label>
                <select name="rombel_id" id="rombel_id" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition shadow-sm" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}" {{ $selectedRombel == $rombel->id ? 'selected' : '' }}>
                        {{ $rombel->nama_rombel }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            <div class="w-full sm:w-64">
                <label for="tanggal" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition shadow-sm" value="{{ $selectedTanggal }}" required>
            </div>

            {{-- Tombol Tampilkan --}}
            <div class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto h-11 px-6 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm transition flex items-center justify-center gap-2">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>

        </form>
    </div>

    @if($selectedRombel)
    <form action="{{ route('admin.presensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="rombel_id" value="{{ $selectedRombel }}">
        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-medium border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-16">No</th>
                            <th scope="col" class="px-6 py-4">Siswa</th>
                            <th scope="col" class="px-6 py-4 w-36">Waktu Tap</th>
                            <th scope="col" class="px-6 py-4 text-center w-32">Metode</th>
                            <th scope="col" class="px-6 py-4 text-center w-80">Status Kehadiran</th>
                            <th scope="col" class="px-6 py-4 w-64">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas as $index => $siswa)
                        @php
                        $p = $presensis[$siswa->id] ?? null;
                        $status = $p->status ?? 'hadir';
                        $metode = $p->metode ?? 'manual';
                        @endphp
                        <tr class="hover:bg-slate-50 transition duration-150">
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs font-mono text-slate-400 mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                <div class="flex items-center gap-1.5 text-emerald-600 mb-1">
                                    <i class="bi bi-box-arrow-in-right"></i> {{ $p->jam_masuk ?? '--:--' }}
                                </div>
                                <div class="flex items-center gap-1.5 text-rose-500">
                                    <i class="bi bi-box-arrow-right"></i> {{ $p->jam_pulang ?? '--:--' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($metode === 'mesin_rfid')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-100">
                                    <i class="bi bi-card-heading"></i> RFID
                                </span>
                                @elseif($metode === 'face_recognition')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                    <i class="bi bi-person-bounding-box"></i> Face Scan
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="bi bi-pencil-square"></i> Manual
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex p-1 bg-slate-100 rounded-lg text-xs font-medium border border-slate-200/80 w-full justify-between">
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} class="peer sr-only">
                                        <span class="block py-1.5 rounded-md text-slate-600 transition peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm hover:text-slate-900">
                                            Hadir
                                        </span>
                                    </label>
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }} class="peer sr-only">
                                        <span class="block py-1.5 rounded-md text-slate-600 transition peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-sm hover:text-slate-900">
                                            Sakit
                                        </span>
                                    </label>
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} class="peer sr-only">
                                        <span class="block py-1.5 rounded-md text-slate-600 transition peer-checked:bg-sky-500 peer-checked:text-white peer-checked:shadow-sm hover:text-slate-900">
                                            Izin
                                        </span>
                                    </label>
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }} class="peer sr-only">
                                        <span class="block py-1.5 rounded-md text-slate-600 transition peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm hover:text-slate-900">
                                            Alpa
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" name="presensi[{{ $siswa->id }}][keterangan]"
                                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                    placeholder="Catatan (misal: Izin Lomba)"
                                    value="{{ $p->keterangan ?? '' }}">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <i class="bi bi-people text-2xl"></i>
                                    </div>
                                    <h3 class="text-slate-800 font-medium mb-1">Belum ada siswa</h3>
                                    <p class="text-slate-500 text-sm">Belum ada siswa yang terdaftar di kelas ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($siswas) > 0)
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition flex items-center gap-2">
                    <i class="bi bi-save"></i> Simpan / Perbarui Presensi
                </button>
            </div>
            @endif
        </div>
    </form>
    @else
    <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl shadow-sm flex items-center gap-3 text-sky-800 text-sm">
        <i class="bi bi-info-circle-fill text-sky-500 text-lg flex-shrink-0"></i>
        <span>Silakan pilih <strong class="font-semibold text-sky-900">Kelas</strong> dan <strong class="font-semibold text-sky-900">Tanggal</strong> untuk menampilkan data presensi.</span>
    </div>
    @endif
</div>
@endsection