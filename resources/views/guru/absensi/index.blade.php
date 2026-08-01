@extends('layouts.app')

@section('content')
<main class="p-6 md:p-8 space-y-6 bg-slate-50 min-h-screen">

    <!-- HEADER & ACTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Presensi & Kehadiran Siswa</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih kelas dan tanggal untuk melakukan pengisian atau pembaharuan absensi.</p>
        </div>
        <a href="{{ route('guru.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition inline-flex items-center gap-2 self-start md:self-auto">
            <svg class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">&times;</button>
    </div>
    @endif

    <!-- FILTER KELAS & TANGGAL -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('guru.absensi.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
            <!-- Filter Kelas -->
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Rombel / Kelas</label>
                <select name="kelas_id" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:border-indigo-500">
                    @foreach($daftarKelas as $kelas)
                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                        Kelas {{ $kelas->nama_kelas }} ({{ $kelas->tingkat }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Tombol Tampilkan -->
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Tampilkan Siswa
            </button>
        </form>
    </div>

    <!-- TABEL CHECKLIST ABSENSI -->
    <form action="{{ route('guru.absensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Daftar Presensi Siswa</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tanggal: <span class="font-semibold text-slate-600">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span></p>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    Simpan Presensi
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4">NISN / Nama Siswa</th>
                            <th class="p-4 text-center">Status Kehadiran</th>
                            <th class="p-4">Catatan (Keterangan)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswas as $index => $siswa)
                        @php
                        $statusSebelumnya = $presensis[$siswa->id]->status ?? 'hadir';
                        $catatanSebelumnya = $presensis[$siswa->id]->catatan ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $siswa->nama }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">NISN: {{ $siswa->nisn ?? '-' }}</p>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Hadir -->
                                    <label class="cursor-pointer px-3 py-1.5 rounded-lg border flex items-center gap-1.5 text-xs font-medium transition has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300 has-[:checked]:text-emerald-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="hadir" class="hidden" {{ $statusSebelumnya == 'hadir' ? 'checked' : '' }}>
                                        <span>Hadir</span>
                                    </label>

                                    <!-- Sakit -->
                                    <label class="cursor-pointer px-3 py-1.5 rounded-lg border flex items-center gap-1.5 text-xs font-medium transition has-[:checked]:bg-amber-50 has-[:checked]:border-amber-300 has-[:checked]:text-amber-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="sakit" class="hidden" {{ $statusSebelumnya == 'sakit' ? 'checked' : '' }}>
                                        <span>Sakit</span>
                                    </label>

                                    <!-- Izin -->
                                    <label class="cursor-pointer px-3 py-1.5 rounded-lg border flex items-center gap-1.5 text-xs font-medium transition has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 has-[:checked]:text-blue-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="izin" class="hidden" {{ $statusSebelumnya == 'izin' ? 'checked' : '' }}>
                                        <span>Izin</span>
                                    </label>

                                    <!-- Alpa -->
                                    <label class="cursor-pointer px-3 py-1.5 rounded-lg border flex items-center gap-1.5 text-xs font-medium transition has-[:checked]:bg-rose-50 has-[:checked]:border-rose-300 has-[:checked]:text-rose-700 border-slate-200 text-slate-600">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="alpa" class="hidden" {{ $statusSebelumnya == 'alpa' ? 'checked' : '' }}>
                                        <span>Alpa</span>
                                    </label>
                                </div>
                            </td>
                            <td class="p-4">
                                <input type="text" name="presensi[{{ $siswa->id }}][catatan]" value="{{ $catatanSebelumnya }}" placeholder="Contoh: Demam, Surat terlampir" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:border-indigo-500">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">
                                Belum ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($siswas) > 0)
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition">
                    Simpan Presensi
                </button>
            </div>
            @endif
        </div>
    </form>
</main>
@endsection