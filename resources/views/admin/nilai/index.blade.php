@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian Siswa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Filter Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filter Penilaian
        </h2>
        <form action="{{ route('admin.nilai.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
            <div class="lg:col-span-3">
                <label for="rombel_id" class="block text-xs font-semibold text-gray-600 mb-1">Rombel</label>
                <select name="rombel_id" id="rombel_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombelList as $rombel)
                    <option value="{{ $rombel->id }}" @selected($rombelId==$rombel->id)>
                        {{ $rombel->nama_rombel }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label for="mapel_id" class="block text-xs font-semibold text-gray-600 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->id }}" @selected($mapelId==$mapel->id)>
                        {{ $mapel->nama_mapel }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="semester_id" class="block text-xs font-semibold text-gray-600 mb-1">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                    <option value="">-- Semester --</option>
                    @foreach($semesterList as $semester)
                    <option value="{{ $semester->value }}" @selected($semesterId==$semester->value)>
                        {{ $semester->label() }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="tahun_ajaran_id" class="block text-xs font-semibold text-gray-600 mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                    <option value="">-- Tahun Ajaran --</option>
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>
                        {{ $ta->tahun }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full inline-flex justify-center items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Form Input Nilai --}}
    @if($rombelId && $mapelId && $semesterId && $tahunAjaranId)
    <form id="formPenilaian" action="{{ route('admin.nilai.store') }}" method="POST">
        @csrf
        <input type="hidden" name="rombel_id" value="{{ $rombelId }}">
        <input type="hidden" name="mapel_id" value="{{ $mapelId }}">
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Daftar Penilaian Siswa</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Bobot: Harian (15%), Tugas (15%), Quiz (10%), UTS (20%), UAS (20%), Praktik (20%) | KKM: 75</p>
                </div>

                <div>
                    <a href="{{ route('admin.rekap.index', ['rombel_id' => $rombelId, 'mapel_id' => $mapelId, 'semester_id' => $semesterId, 'tahun_ajaran_id' => $tahunAjaranId]) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-semibold text-xs rounded-lg transition btn-rekap-guard">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat Rekap Rombel
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" style="min-width: 1150px;">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-[10px] font-semibold tracking-wider border-b border-gray-100">
                            <th class="py-3 px-4 pl-6 w-12">No</th>
                            <th class="py-3 px-4 w-48">Nama Siswa</th>
                            <th class="py-3 px-2 w-20">Harian</th>
                            <th class="py-3 px-2 w-20">Tugas</th>
                            <th class="py-3 px-2 w-20">Quiz</th>
                            <th class="py-3 px-2 w-20">UTS</th>
                            <th class="py-3 px-2 w-20">UAS</th>
                            <th class="py-3 px-2 w-20">Praktik</th>
                            <th class="py-3 px-2 w-20">Akhir</th>
                            <th class="py-3 px-2 w-20">Predikat</th>
                            <th class="py-3 px-2 w-24 text-center">Status</th>
                            <th class="py-3 px-4 pr-6 w-40">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($siswaList as $siswa)
                        @php
                        $nilai = $siswa->penilaian->first();
                        $hasNilai = !is_null($nilai?->is_remedial);
                        @endphp
                        <tr class="row-nilai hover:bg-gray-50/50 transition" data-siswa-id="{{ $siswa->id }}">
                            <td class="py-3 px-4 pl-6 font-medium text-gray-500">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-800">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs text-gray-400">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][nilai_harian]"
                                    value="{{ old("nilai.{$siswa->id}.nilai_harian", $nilai?->nilai_harian) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-harian" placeholder="0">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][tugas]"
                                    value="{{ old("nilai.{$siswa->id}.tugas", $nilai?->tugas) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-tugas" placeholder="0">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][quiz]"
                                    value="{{ old("nilai.{$siswa->id}.quiz", $nilai?->quiz) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-quiz" placeholder="0">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][uts]"
                                    value="{{ old("nilai.{$siswa->id}.uts", $nilai?->uts) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-uts" placeholder="0">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][uas]"
                                    value="{{ old("nilai.{$siswa->id}.uas", $nilai?->uas) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-uas" placeholder="0">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][praktik]"
                                    value="{{ old("nilai.{$siswa->id}.praktik", $nilai?->praktik) }}"
                                    class="w-full text-center rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 input-score input-praktik" placeholder="0">
                            </td>
                            <td class="py-3 px-2 font-bold text-indigo-600 score-akhir">
                                {{ $nilai?->nilai_akhir ?? '-' }}
                            </td>
                            <td class="py-3 px-2">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-predikat
                                    @if($nilai?->predikat === 'A') bg-green-100 text-green-800
                                    @elseif($nilai?->predikat === 'B') bg-blue-100 text-blue-800
                                    @elseif($nilai?->predikat === 'C') bg-yellow-100 text-yellow-800
                                    @elseif($nilai?->predikat === 'D') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $nilai?->predikat ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-remedial
                                    @if($hasNilai && $nilai->is_remedial) bg-red-100 text-red-800
                                    @elseif($hasNilai && !$nilai->is_remedial) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $hasNilai ? ($nilai->is_remedial ? 'Remedial' : 'Tuntas') : '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 pr-6">
                                <input type="text"
                                    name="nilai[{{ $siswa->id }}][catatan]"
                                    value="{{ old("nilai.{$siswa->id}.catatan", $nilai?->catatan) }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Catatan...">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Tidak ada data siswa ditemukan untuk rombel ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Penilaian
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 text-center py-12 px-6">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-base font-bold text-gray-800">Silakan Filter Terlebih Dahulu</h3>
        <p class="text-xs text-gray-500 mt-1">Pilih Rombel, Mata Pelajaran, Semester, dan Tahun Ajaran di atas untuk menampilkan daftar siswa.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formPenilaian = document.getElementById('formPenilaian');
        if (!formPenilaian) return;

        let isFormDirty = false;

        formPenilaian.addEventListener('input', () => isFormDirty = true);
        formPenilaian.addEventListener('submit', () => isFormDirty = false);

        window.addEventListener('beforeunload', (e) => {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.querySelectorAll('.btn-rekap-guard').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (isFormDirty) {
                    const confirmLeave = confirm('Ada nilai yang belum disimpan! Perubahan akan hilang jika berpindah halaman. Lanjutkan?');
                    if (!confirmLeave) e.preventDefault();
                }
            });
        });

        const calculateRowScore = (row) => {
            const getVal = (selector) => {
                const input = row.querySelector(selector);
                if (!input || input.value === '') return null;

                let val = parseFloat(input.value);
                if (val > 100) val = 100;
                if (val < 0) val = 0;
                return val;
            };

            const values = {
                harian: getVal('.input-harian'),
                tugas: getVal('.input-tugas'),
                quiz: getVal('.input-quiz'),
                uts: getVal('.input-uts'),
                uas: getVal('.input-uas'),
                praktik: getVal('.input-praktik'),
            };

            const scoreAkhir = row.querySelector('.score-akhir');
            const badgePredikat = row.querySelector('.badge-predikat');
            const badgeRemedial = row.querySelector('.badge-remedial');

            const hasInput = Object.values(values).some(val => val !== null);

            if (!hasInput) {
                scoreAkhir.textContent = '-';
                badgePredikat.textContent = '-';
                badgePredikat.className = 'inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-predikat bg-gray-100 text-gray-800';
                badgeRemedial.textContent = '-';
                badgeRemedial.className = 'inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-remedial bg-gray-100 text-gray-800';
                return;
            }

            const akhir = ((values.harian || 0) * 0.15) +
                ((values.tugas || 0) * 0.15) +
                ((values.quiz || 0) * 0.10) +
                ((values.uts || 0) * 0.20) +
                ((values.uas || 0) * 0.20) +
                ((values.praktik || 0) * 0.20);

            scoreAkhir.textContent = akhir.toFixed(2);

            let predikat = 'D';
            let badgeClass = 'bg-red-100 text-red-800';

            if (akhir >= 90) {
                predikat = 'A';
                badgeClass = 'bg-green-100 text-green-800';
            } else if (akhir >= 80) {
                predikat = 'B';
                badgeClass = 'bg-blue-100 text-blue-800';
            } else if (akhir >= 75) {
                predikat = 'C';
                badgeClass = 'bg-yellow-100 text-yellow-800';
            }

            badgePredikat.textContent = predikat;
            badgePredikat.className = `inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-predikat ${badgeClass}`;

            if (akhir < 75) {
                badgeRemedial.textContent = 'Remedial';
                badgeRemedial.className = 'inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-remedial bg-red-100 text-red-800';
            } else {
                badgeRemedial.textContent = 'Tuntas';
                badgeRemedial.className = 'inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold badge-remedial bg-green-100 text-green-800';
            }
        };

        formPenilaian.addEventListener('input', (e) => {
            if (e.target.classList.contains('input-score')) {
                const row = e.target.closest('.row-nilai');
                if (row) calculateRowScore(row);
            }
        });
    });
</script>
@endpush