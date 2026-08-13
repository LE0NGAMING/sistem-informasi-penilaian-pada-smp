@extends('layouts.app')

@section('content')
<main class="p-4 md:p-5 space-y-4 bg-slate-50 min-h-screen">

    <!-- HEADER & ACTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white px-5 py-4 rounded-xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-base font-bold text-slate-800">Presensi & Kehadiran Siswa</h1>
            <p class="text-[11px] text-slate-500 mt-0.5">Pilih kelas dan tanggal untuk melakukan pengisian atau pembaharuan absensi.</p>
        </div>
        <a href="{{ route('guru.dashboard') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 self-start md:self-auto">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- FILTER KELAS & TANGGAL -->
    <div class="bg-white px-5 py-4 rounded-xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('walikelas.presensi.index') }}" class="flex flex-col md:flex-row md:items-end gap-3">

            <!-- Filter Kelas -->
            <div class="flex-1">
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Pilih Rombel / Kelas</label>
                <select name="kelas_id" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-indigo-500" disabled>
                    @foreach($daftarKelas as $kelas)
                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                        Kelas {{ $kelas->nama_rombel ?? $kelas->nama_kelas }}
                    </option>
                    @endforeach
                </select>
                <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            </div>

            <!-- Filter Tanggal -->
            <div class="flex-1">
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Tombol Tampilkan -->
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Tampilkan Siswa</span>
            </button>
        </form>
    </div>

    <!-- TABEL CHECKLIST ABSENSI -->
    <form action="{{ route('walikelas.presensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="rombel_id" value="{{ $rombelBinaan->id }}">

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">

            <!-- Card Header Tabel -->
            <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <!-- <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Daftar Presensi Siswa</h2> -->
                    <!-- <span class="text-xs text-slate-300">|</span> -->
                    <span class="text-xs text-slate-500">
                        Tanggal: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong>
                    </span>
                </div>

                <!-- Action Button / Lock Status Badge -->
                <div class="flex items-center gap-2">
                    <!-- Tombol Ekspor PDF & Excel -->
                    @php
                    $bulanAktif = \Carbon\Carbon::parse($tanggal)->month;
                    $tahunAktif = \Carbon\Carbon::parse($tanggal)->year;
                    @endphp

                    <a href="{{ route('walikelas.presensi.exportExcel', ['bulan' => $bulanAktif, 'tahun' => $tahunAktif]) }}" target="_blank" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h55.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </a>

                    <a href="{{ route('walikelas.presensi.exportPdf', ['bulan' => $bulanAktif, 'tahun' => $tahunAktif]) }}" target="_blank" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>PDF / Cetak</span>
                    </a>
                    @if($isLocked ?? false)
                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[11px] font-semibold inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Terkunci (Read Only)
                    </span>
                    @elseif(count($siswas) > 0)
                    <button type="button" id="btn-semua-hadir" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tandai Semua Hadir</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                            <th class="py-2.5 px-3 w-12 text-center">No</th>
                            <th class="py-2.5 px-3 w-72">NISN / Nama Siswa</th>
                            <th class="py-2.5 px-3 w-80 text-center">Status Kehadiran</th>
                            <th class="py-2.5 px-3">Catatan (Keterangan)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas as $index => $siswa)
                        @php
                        $presensiSebelumnya = $presensis[$siswa->id] ?? null;
                        $statusSebelumnya = old("presensi.{$siswa->id}.status", $presensiSebelumnya?->status ?? 'hadir');
                        $catatanSebelumnya = old("presensi.{$siswa->id}.catatan", $presensiSebelumnya?->keterangan ?? $presensiSebelumnya?->catatan ?? '');
                        $disabledAttr = ($isLocked ?? false) ? 'disabled' : '';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- No -->
                            <td class="py-2 px-3 text-center font-medium text-slate-400">{{ $index + 1 }}</td>

                            <!-- Nama & NISN -->
                            <td class="py-2 px-3">
                                <p class="font-bold text-slate-800 leading-snug">{{ $siswa->nama_lengkap ?? $siswa->nama }}</p>
                                <p class="text-[10px] text-slate-400">NISN: {{ $siswa->nisn ?? '-' }}</p>
                            </td>

                            <!-- Status Kehadiran (Radio Group) -->
                            <td class="py-2 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Hadir -->
                                    <label class="px-2 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300 has-[:checked]:text-emerald-700 border-slate-200 text-slate-600 {{ ($isLocked ?? false) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50' }}">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="hadir" class="hidden" {{ strtolower($statusSebelumnya) == 'hadir' ? 'checked' : '' }} {{ $disabledAttr }}>
                                        <span>Hadir</span>
                                    </label>

                                    <!-- Sakit -->
                                    <label class="px-2 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-amber-50 has-[:checked]:border-amber-300 has-[:checked]:text-amber-700 border-slate-200 text-slate-600 {{ ($isLocked ?? false) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50' }}">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="sakit" class="hidden" {{ strtolower($statusSebelumnya) == 'sakit' ? 'checked' : '' }} {{ $disabledAttr }}>
                                        <span>Sakit</span>
                                    </label>

                                    <!-- Izin -->
                                    <label class="px-2 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 has-[:checked]:text-blue-700 border-slate-200 text-slate-600 {{ ($isLocked ?? false) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50' }}">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="izin" class="hidden" {{ strtolower($statusSebelumnya) == 'izin' ? 'checked' : '' }} {{ $disabledAttr }}>
                                        <span>Izin</span>
                                    </label>

                                    <!-- Alpa -->
                                    <label class="px-2 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-rose-50 has-[:checked]:border-rose-300 has-[:checked]:text-rose-700 border-slate-200 text-slate-600 {{ ($isLocked ?? false) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-50' }}">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="alpa" class="hidden" {{ strtolower($statusSebelumnya) == 'alpa' ? 'checked' : '' }} {{ $disabledAttr }}>
                                        <span>Alpa</span>
                                    </label>
                                </div>
                            </td>

                            <!-- Catatan -->
                            <td class="py-2 px-3">
                                <input type="text" name="presensi[{{ $siswa->id }}][catatan]" value="{{ $catatanSebelumnya }}" placeholder="Contoh: Demam, Surat terlampir" {{ $disabledAttr }} class="w-full text-xs bg-slate-50/50 border border-slate-200 rounded-lg px-2.5 py-1 text-slate-700 focus:outline-none focus:border-indigo-500 focus:bg-white disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed transition">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">
                                Belum ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan Bawah -->
            @if(count($siswas) > 0 && !($isLocked ?? false))
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Presensi</span>
                </button>
            </div>
            @endif
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSemuaHadir = document.getElementById('btn-semua-hadir');
        if (btnSemuaHadir) {
            btnSemuaHadir.addEventListener('click', function() {
                const hadirRadios = document.querySelectorAll('input[type="radio"][value="hadir"]:not([disabled])');
                hadirRadios.forEach(radio => {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                });
            });
        }
    });
</script>
@endsection