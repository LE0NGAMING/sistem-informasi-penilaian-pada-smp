@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian Siswa')

@section('content')
<div class="container-fluid px-0">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-filter me-2 text-primary"></i>Filter Penilaian</h6>
            <form action="{{ route('guru.nilai.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Rombongan Belajar (Rombel)</label>
                    <select name="rombel_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($rombelList as $rombel)
                        <option value="{{ $rombel->id }}" {{ $rombelId == $rombel->id ? 'selected' : '' }}>
                            {{ $rombel->nama_rombel ?? $rombel->nama_kelas ?? 'Rombel '.$rombel->id }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Mata Pelajaran</label>
                    <select name="mapel_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Semester</label>
                    <select name="semester_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesterList as $sem)
                        <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->nama_semester ?? $sem->semester ?? 'Semester '.$sem->id }}
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
                <button type="submit" class="btn btn-success px-4 rounded-3 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Simpan Penilaian
                </button>
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
                            $dataNilai = $siswa->penilaian->first();
                            $harian = $dataNilai->nilai_harian ?? '';
                            $tugas = $dataNilai->tugas ?? '';
                            $quiz = $dataNilai->quiz ?? '';
                            $uts = $dataNilai->uts ?? '';
                            $uas = $dataNilai->uas ?? '';
                            $praktik = $dataNilai->praktik ?? '';
                            $akhir = $dataNilai->nilai_akhir ?? '-';
                            $predikat = $dataNilai->predikat ?? '-';
                            $isRemedial = $dataNilai->is_remedial ?? false;
                            $catatan = $dataNilai->catatan ?? '';
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
                                        value="{{ $harian }}"
                                        class="form-control form-control-sm input-harian" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][tugas]"
                                        value="{{ $tugas }}"
                                        class="form-control form-control-sm input-tugas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][quiz]"
                                        value="{{ $quiz }}"
                                        class="form-control form-control-sm input-quiz" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uts]"
                                        value="{{ $uts }}"
                                        class="form-control form-control-sm input-uts" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][uas]"
                                        value="{{ $uas }}"
                                        class="form-control form-control-sm input-uas" placeholder="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="100"
                                        name="nilai[{{ $siswa->id }}][praktik]"
                                        value="{{ $praktik }}"
                                        class="form-control form-control-sm input-praktik" placeholder="0">
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-primary score-akhir">{{ $akhir }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary badge-predikat">{{ $predikat }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-remedial {{ $isRemedial ? 'bg-danger' : 'bg-success' }}">
                                        {{ $isRemedial ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <input type="text"
                                        name="nilai[{{ $siswa->id }}][catatan]"
                                        value="{{ $catatan }}"
                                        class="form-control form-control-sm" placeholder="Catatan...">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
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
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.row-nilai');

        rows.forEach(row => {
            const inputHarian = row.querySelector('.input-harian');
            const inputTugas = row.querySelector('.input-tugas');
            const inputQuiz = row.querySelector('.input-quiz');
            const inputUts = row.querySelector('.input-uts');
            const inputUas = row.querySelector('.input-uas');
            const inputPraktik = row.querySelector('.input-praktik');

            const scoreAkhir = row.querySelector('.score-akhir');
            const badgePredikat = row.querySelector('.badge-predikat');
            const badgeRemedial = row.querySelector('.badge-remedial');

            function calculateScore() {
                const harian = parseFloat(inputHarian.value) || 0;
                const tugas = parseFloat(inputTugas.value) || 0;
                const quiz = parseFloat(inputQuiz.value) || 0;
                const uts = parseFloat(inputUts.value) || 0;
                const uas = parseFloat(inputUas.value) || 0;
                const praktik = parseFloat(inputPraktik.value) || 0;

                const hasInput = inputHarian.value !== '' || inputTugas.value !== '' || inputQuiz.value !== '' ||
                    inputUts.value !== '' || inputUas.value !== '' || inputPraktik.value !== '';

                if (!hasInput) {
                    scoreAkhir.textContent = '-';
                    badgePredikat.textContent = '-';
                    badgePredikat.className = 'badge bg-secondary badge-predikat';
                    badgeRemedial.textContent = 'Tidak';
                    badgeRemedial.className = 'badge bg-success badge-remedial';
                    return;
                }

                // Hitung Nilai Akhir (Sesuai Bobot Controller)
                const akhir = (harian * 0.15) + (tugas * 0.15) + (quiz * 0.10) +
                    (uts * 0.20) + (uas * 0.20) + (praktik * 0.20);

                scoreAkhir.textContent = akhir.toFixed(2);

                // Predikat
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
            }

            [inputHarian, inputTugas, inputQuiz, inputUts, inputUas, inputPraktik].forEach(input => {
                if (input) {
                    input.addEventListener('input', calculateScore);
                }
            });
        });
    });
</script>
@endpush