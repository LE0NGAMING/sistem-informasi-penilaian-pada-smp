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
                        @foreach(\App\Enums\SemesterEnum::cases() as $semester)
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
    <form action="{{ route('guru.nilai.store') }}" method="POST">
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
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th style="width: 200px;">Nama Siswa</th>
                                <th style="width: 90px;">Harian</th>
                                <th style="width: 90px;">Tugas</th>
                                <th style="width: 90px;">Quiz</th>
                                <th style="width: 90px;">UTS</th>
                                <th style="width: 90px;">UAS</th>
                                <th style="width: 90px;">Praktik</th>
                                <th style="width: 90px;">Akhir</th>
                                <th style="width: 80px;">Predikat</th>
                                <th style="width: 90px;">Remedial</th>
                                <th class="pe-4" style="width: 180px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $index => $siswa)
                            @php
                            $nilai = $siswa->penilaian->first();
                            @endphp
                            <tr class="row-nilai" data-siswa-id="{{ $siswa->id }}">
                                <td class="ps-4 fw-semibold text-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][nilai_harian]"
                                        value="{{ $nilai?->nilai_harian }}"
                                        class="form-control form-control-sm input-harian" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][tugas]"
                                        value="{{ $nilai?->tugas }}"
                                        class="form-control form-control-sm input-tugas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][quiz]"
                                        value="{{ $nilai?->quiz }}"
                                        class="form-control form-control-sm input-quiz" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uts]"
                                        value="{{ $nilai?->uts }}"
                                        class="form-control form-control-sm input-uts" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uas]"
                                        value="{{ $nilai?->uas }}"
                                        class="form-control form-control-sm input-uas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][praktik]"
                                        value="{{ $nilai?->praktik }}"
                                        class="form-control form-control-sm input-praktik" placeholder="0">
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-primary score-akhir">
                                        {{ $nilai?->nilai_akhir ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary badge-predikat">
                                        {{ $nilai?->predikat ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span @class([ 'badge' , 'badge-remedial' , 'bg-danger'=> $nilai?->is_remedial,
                                        'bg-success' => ! $nilai?->is_remedial,
                                        ])>
                                        {{ $nilai?->is_remedial ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <input type="text"
                                        name="nilai[{{ $siswa->id }}][catatan]"
                                        value="{{ $nilai?->catatan }}"
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
        const rows = document.querySelectorAll('.row-nilai');

        rows.forEach((row) => {
            const inputs = {
                harian: row.querySelector('.input-harian'),
                tugas: row.querySelector('.input-tugas'),
                quiz: row.querySelector('.input-quiz'),
                uts: row.querySelector('.input-uts'),
                uas: row.querySelector('.input-uas'),
                praktik: row.querySelector('.input-praktik'),
            };

            const scoreAkhir = row.querySelector('.score-akhir');
            const badgePredikat = row.querySelector('.badge-predikat');
            const badgeRemedial = row.querySelector('.badge-remedial');

            const calculateScore = () => {
                const values = {
                    harian: parseFloat(inputs.harian?.value) || 0,
                    tugas: parseFloat(inputs.tugas?.value) || 0,
                    quiz: parseFloat(inputs.quiz?.value) || 0,
                    uts: parseFloat(inputs.uts?.value) || 0,
                    uas: parseFloat(inputs.uas?.value) || 0,
                    praktik: parseFloat(inputs.praktik?.value) || 0,
                };

                const hasInput = Object.values(inputs).some((input) => input && input.value !== '');

                if (!hasInput) {
                    scoreAkhir.textContent = '-';
                    badgePredikat.textContent = '-';
                    badgePredikat.className = 'badge bg-secondary badge-predikat';
                    badgeRemedial.textContent = 'Tidak';
                    badgeRemedial.className = 'badge bg-success badge-remedial';
                    return;
                }

                // Hitung Nilai Akhir sesuai Bobot
                const akhir = (values.harian * 0.15) +
                    (values.tugas * 0.15) +
                    (values.quiz * 0.10) +
                    (values.uts * 0.20) +
                    (values.uas * 0.20) +
                    (values.praktik * 0.20);

                scoreAkhir.textContent = akhir.toFixed(2);

                // Tentukan Predikat
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

                // Status Remedial (KKM < 75)
                if (akhir < 75) {
                    badgeRemedial.textContent = 'Ya';
                    badgeRemedial.className = 'badge bg-danger badge-remedial';
                } else {
                    badgeRemedial.textContent = 'Tidak';
                    badgeRemedial.className = 'badge bg-success badge-remedial';
                }
            };

            Object.values(inputs).forEach((input) => {
                if (input) {
                    input.addEventListener('input', calculateScore);
                }
            });
        });
    });
</script>
@endpush