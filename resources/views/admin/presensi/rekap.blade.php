@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rekapitulasi Presensi (Rapor)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ringkasan total kehadiran akumulatif seluruh siswa per kelas.</p>
        </div>
        <a href="{{ route('admin.presensi.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg shadow-sm transition">
            <i class="bi bi-arrow-left"></i> Kembali ke Presensi Harian
        </a>
    </div>

    {{-- Form Filter Kompak & Proporsional --}}
    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.presensi.rekap') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-96">
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
            <div class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto h-11 px-6 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm transition flex items-center justify-center gap-2">
                    <i class="bi bi-filter"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    @if($selectedRombel)
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-medium border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16">No</th>
                        <th scope="col" class="px-6 py-4 w-40">NISN</th>
                        <th scope="col" class="px-6 py-4">Nama Siswa</th>
                        <th scope="col" class="px-6 py-4 text-center w-28 text-emerald-600">Hadir (H)</th>
                        <th scope="col" class="px-6 py-4 text-center w-28 text-amber-600">Sakit (S)</th>
                        <th scope="col" class="px-6 py-4 text-center w-28 text-sky-600">Izin (I)</th>
                        <th scope="col" class="px-6 py-4 text-center w-28 text-rose-600">Alpa (A)</th>
                        <th scope="col" class="px-6 py-4 text-center w-32">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekaps as $index => $rekap)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $rekap['nisn'] ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $rekap['nama_lengkap'] }}</td>
                        <td class="px-6 py-4 text-center font-bold text-emerald-600">{{ $rekap['hadir'] }}</td>
                        <td class="px-6 py-4 text-center font-bold text-amber-500">{{ $rekap['sakit'] }}</td>
                        <td class="px-6 py-4 text-center font-bold text-sky-500">{{ $rekap['izin'] }}</td>
                        <td class="px-6 py-4 text-center font-bold text-rose-600">{{ $rekap['alpa'] }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($rekap['persentase'] >= 85)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $rekap['persentase'] }}%
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                {{ $rekap['persentase'] }}%
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                    <i class="bi bi-file-earmark-x text-2xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-medium mb-1">Belum ada data</h3>
                                <p class="text-slate-500 text-sm">Belum ada akumulasi presensi untuk kelas ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection