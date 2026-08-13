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
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Tampilkan Siswa
            </button>
        </form>
    </div>

    <!-- TABEL CHECKLIST ABSENSI -->
    <form action="{{ route('walikelas.presensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="rombel_id" value="{{ $rombelBinaan->id }}">

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">

            <!-- Card Header Tabel (Judul di Kiri, Tanggal di Kanan) -->
            <!-- <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Daftar Presensi Siswa</h2>
                </div>
                <div>
                    <span class="text-xs text-slate-500">Tanggal: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong></span>
                </div>
            </div> -->

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <div class="flex justify-end mb-2">
                    <button type="button" id="btn-semua-hadir" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Tandai Semua Hadir
                    </button>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-500 font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">NISN / Nama Siswa</th>
                            <th class="py-3 px-4 text-center">Status Kehadiran</th>
                            <th class="py-3 px-4">Catatan (Keterangan)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas as $index => $siswa)
                        @php
                        $presensiSebelumnya = $presensis[$siswa->id] ?? null;
                        $statusSebelumnya = old("presensi.{$siswa->id}.status", $presensiSebelumnya?->status ?? 'hadir');
                        $catatanSebelumnya = old("presensi.{$siswa->id}.catatan", $presensiSebelumnya?->keterangan ?? $presensiSebelumnya?->catatan ?? '');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800">{{ $siswa->nama_lengkap ?? $siswa->nama }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Hadir -->
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300 has-[:checked]:text-emerald-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="hadir" class="hidden" {{ strtolower($statusSebelumnya) == 'hadir' ? 'checked' : '' }}>
                                        <span>Hadir</span>
                                    </label>

                                    <!-- Sakit -->
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-amber-50 has-[:checked]:border-amber-300 has-[:checked]:text-amber-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="sakit" class="hidden" {{ strtolower($statusSebelumnya) == 'sakit' ? 'checked' : '' }}>
                                        <span>Sakit</span>
                                    </label>

                                    <!-- Izin -->
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 has-[:checked]:text-blue-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="izin" class="hidden" {{ strtolower($statusSebelumnya) == 'izin' ? 'checked' : '' }}>
                                        <span>Izin</span>
                                    </label>

                                    <!-- Alpa -->
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md border flex items-center gap-1 text-[11px] font-medium transition has-[:checked]:bg-rose-50 has-[:checked]:border-rose-300 has-[:checked]:text-rose-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="alpa" class="hidden" {{ strtolower($statusSebelumnya) == 'alpa' ? 'checked' : '' }}>
                                        <span>Alpa</span>
                                    </label>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="presensi[{{ $siswa->id }}][catatan]" value="{{ $catatanSebelumnya }}" placeholder="Contoh: Demam, Surat terlampir" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:border-indigo-500">
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
            @if(count($siswas) > 0)
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Presensi
                </button>
            </div>
            @endif
        </div>
    </form>
</main>
<script>
    document.getElementById('btn-semua-hadir')?.addEventListener('click', function() {
        // Ambil semua input radio yang bernilai 'hadir'
        const hadirRadios = document.querySelectorAll('input[type="radio"][value="hadir"]');

        hadirRadios.forEach(radio => {
            radio.checked = true;
            // Trigger event change agar style CSS Tailwind (has-[:checked]) langsung terupdate
            radio.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        });
    });
</script>
@endsection