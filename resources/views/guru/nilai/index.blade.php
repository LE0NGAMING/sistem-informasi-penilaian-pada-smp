@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian Siswa')

@section('content')
<div class="container-fluid px-0">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-filter me-2 text-primary"></i>Filter Penilaian
            </h6>
            <form action="{{ route('guru.nilai.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="rombel_id" class="form-label small fw-semibold text-secondary">Rombongan Belajar (Rombel)</label>
                    <select name="rombel_id" id="rombel_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($rombelList as $rombel)
                        <option value="{{ $rombel->id }}" @selected($rombelId==$rombel->id)>
                            {{ $rombel->nama_rombel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="mapel_id" class="form-label small fw-semibold text-secondary">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" @selected($mapelId==$mapel->id)>
                            {{ $mapel->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="semester_id" class="form-label small fw-semibold text-secondary">Semester</label>
                    <select name="semester_id" id="semester_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesterList as $semester)
                        <option value="{{ $semester->value }}" @selected($semesterId==$semester->value)>
                            {{ $semester->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Input Nilai --}}
    @if($rombelId && $mapelId && $semesterId)
    <form id="formPenilaian" action="{{ route('guru.nilai.store') }}" method="POST">
        @csrf
        <input type="hidden" name="rombel_id" value="{{ $rombelId }}">
        <input type="hidden" name="mapel_id" value="{{ $mapelId }}">
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Penilaian Siswa</h6>
                    <small class="text-muted">Bobot: Harian (15%), Tugas (15%), Quiz (10%), UTS (20%), UAS (20%), Praktik (20%) | KKM: 75</small>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('guru.rekap.index', ['rombel_id' => $rombelId, 'mapel_id' => $mapelId, 'semester_id' => $semesterId]) }}"
                        class="btn btn-outline-primary btn-sm rounded-3 fw-semibold btn-rekap-guard">
                        <i class="bi bi-file-earmark-text me-1"></i> Lihat Rekap Rombel
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 1150px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th style="width: 200px;">Nama Siswa</th>
                                <th style="width: 80px;">Harian</th>
                                <th style="width: 80px;">Tugas</th>
                                <th style="width: 80px;">Quiz</th>
                                <th style="width: 80px;">UTS</th>
                                <th style="width: 80px;">UAS</th>
                                <th style="width: 80px;">Praktik</th>
                                <th style="width: 80px;">Akhir</th>
                                <th style="width: 80px;">Predikat</th>
                                <th style="width: 90px;" class="text-center">Status</th>
                                <th class="pe-4" style="width: 160px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $siswa)
                            @php
                            $nilai = $siswa->penilaian->first();
                            $hasNilai = !is_null($nilai?->is_remedial);
                            @endphp
                            <tr class="row-nilai" data-siswa-id="{{ $siswa->id }}">
                                <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][nilai_harian]"
                                        value="{{ old("nilai.{$siswa->id}.nilai_harian", $nilai?->nilai_harian) }}"
                                        class="form-control form-control-sm input-score input-harian" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][tugas]"
                                        value="{{ old("nilai.{$siswa->id}.tugas", $nilai?->tugas) }}"
                                        class="form-control form-control-sm input-score input-tugas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][quiz]"
                                        value="{{ old("nilai.{$siswa->id}.quiz", $nilai?->quiz) }}"
                                        class="form-control form-control-sm input-score input-quiz" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uts]"
                                        value="{{ old("nilai.{$siswa->id}.uts", $nilai?->uts) }}"
                                        class="form-control form-control-sm input-score input-uts" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uas]"
                                        value="{{ old("nilai.{$siswa->id}.uas", $nilai?->uas) }}"
                                        class="form-control form-control-sm input-score input-uas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][praktik]"
                                        value="{{ old("nilai.{$siswa->id}.praktik", $nilai?->praktik) }}"
                                        class="form-control form-control-sm input-score input-praktik" placeholder="0">
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-primary score-akhir">
                                        {{ $nilai?->nilai_akhir ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span @class([ 'badge badge-predikat' , 'bg-success'=> $nilai?->predikat === 'A',
                                        'bg-info text-dark' => $nilai?->predikat === 'B',
                                        'bg-warning text-dark' => $nilai?->predikat === 'C',
                                        'bg-danger' => $nilai?->predikat === 'D',
                                        'bg-secondary' => !$nilai?->predikat,
                                        ])>
                                        {{ $nilai?->predikat ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span @class([ 'badge badge-remedial' , 'bg-danger'=> $hasNilai && $nilai->is_remedial,
                                        'bg-success' => $hasNilai && !$nilai->is_remedial,
                                        'bg-secondary' => !$hasNilai,
                                        ])>
                                        {{ $hasNilai ? ($nilai->is_remedial ? 'Remedial' : 'Tuntas') : '-' }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <input type="text"
                                        name="nilai[{{ $siswa->id }}][catatan]"
                                        value="{{ old("nilai.{$siswa->id}.catatan", $nilai?->catatan) }}"
                                        class="form-control form-control-sm" placeholder="Catatan...">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
                                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                    Tidak ada data siswa ditemukan untuk rombel ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3 px-4 text-end border-top-0">
                <button type="submit" class="btn btn-success px-4 rounded-3 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Simpan Penilaian
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body">
            <i class="bi bi-file-earmark-text text-muted display-4"></i>
            <h6 class="fw-bold mt-3 text-dark">Silakan Filter Terlebih Dahulu</h6>
            <p class="text-muted small mb-0">Pilih Rombel, Mata Pelajaran, dan Semester di atas untuk menampilkan daftar siswa.</p>
        </div>
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

        // 1. Unsaved Changes Guards
        formPenilaian.addEventListener('input', () => isFormDirty = true);
        formPenilaian.addEventListener('submit', () => isFormDirty = false);

        // Peringatan jika merefresh atau menutup tab saat ada perubahan belum disimpan
        window.addEventListener('beforeunload', (e) => {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Peringatan khusus saat mengklik tombol navigasi internal (misal Rekap)
        document.querySelectorAll('.btn-rekap-guard').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (isFormDirty) {
                    const confirmLeave = confirm('Ada nilai yang belum disimpan! Perubahan akan hilang jika berpindah halaman. Lanjutkan?');
                    if (!confirmLeave) e.preventDefault();
                }
            });
        });

        // 2. Calculation logic function
        const calculateRowScore = (row) => {
            const getVal = (selector) => {
                const input = row.querySelector(selector);
                if (!input || input.value === '') return null;

                let val = parseFloat(input.value);
                // Clamp value between 0 and 100 instantly for live calculation safety
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
                badgePredikat.className = 'badge bg-secondary badge-predikat';
                badgeRemedial.textContent = '-';
                badgeRemedial.className = 'badge bg-secondary badge-remedial';
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
            let badgeClass = 'bg-danger';

            if (akhir >= 90) {
                predikat = 'A';
                badgeClass = 'bg-success';
            } else if (akhir >= 80) {
                predikat = 'B';
                badgeClass = 'bg-info text-dark';
            } else if (akhir >= 75) {
                predikat = 'C';
                badgeClass = 'bg-warning text-dark';
            }

            badgePredikat.textContent = predikat;
            badgePredikat.className = `badge ${badgeClass} badge-predikat`;

            if (akhir < 75) {
                badgeRemedial.textContent = 'Remedial';
                badgeRemedial.className = 'badge bg-danger badge-remedial';
            } else {
                badgeRemedial.textContent = 'Tuntas';
                badgeRemedial.className = 'badge bg-success badge-remedial';
            }
        };

        // 3. Event Delegation for fast performance
        formPenilaian.addEventListener('input', (e) => {
            if (e.target.classList.contains('input-score')) {
                const row = e.target.closest('.row-nilai');
                if (row) calculateRowScore(row);
            }
        });
    });
</script>
@endpush