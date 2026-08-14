@extends('layouts.app')

@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian Siswa')

@push('styles')
<style>
    /* Menghilangkan panah spinner pada input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    .input-score:focus {
        background-color: #eef6ff !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
    }

    .fs-7 {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Alert Notifikasi System --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3 shadow-sm border-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3 shadow-sm border-0" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-2"></i>Gagal menyimpan data:</div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-filter me-2 text-primary"></i>Filter Penilaian
            </h6>
            <form action="{{ route('guru.nilai.index') }}" method="GET" class="row g-3 align-items-end">
                {{-- Dropdown Tahun Ajaran --}}
                <div class="col-md-3">
                    <label for="tahun_ajaran_id" class="form-label small fw-semibold text-secondary">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih TA --</option>
                        @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>
                            {{ $ta->tahun }}
                        </option>
                        @endforeach
                    </select>
                </div>

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

                <div class="col-md-2">
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

                <div class="col-md-2">
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

                <div class="col-md-2">
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
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Input Penilaian Siswa K13</h6>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>KKM Kelulusan: <span class="fw-bold text-dark">75</span> | Gunakan tombol <kbd>Enter</kbd> atau Panah <kbd>↑</kbd> <kbd>↓</kbd> untuk navigasi cepat.
                    </small>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('guru.rekap.index', ['rombel_id' => $rombelId, 'mapel_id' => $mapelId, 'semester_id' => $semesterId]) }}"
                        class="btn btn-outline-primary btn-sm rounded-3 fw-semibold btn-rekap-guard">
                        <i class="bi bi-file-earmark-text me-1"></i> Lihat Rekap Rombel
                    </a>
                </div>
            </div>

            <div class="card-body p-4 pt-0">
                <!-- Tab Header K13 -->
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3 mb-4" id="penilaianTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-2 rounded-3" id="ki3-tab" data-bs-toggle="tab" data-bs-target="#ki3-pane" type="button" role="tab" aria-controls="ki3-pane" aria-selected="true">
                            <i class="bi bi-journal-text me-2"></i>Pengetahuan (KI-3)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-2 rounded-3" id="ki4-tab" data-bs-toggle="tab" data-bs-target="#ki4-pane" type="button" role="tab" aria-controls="ki4-pane" aria-selected="false">
                            <i class="bi bi-tools me-2"></i>Keterampilan (KI-4)
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="penilaianTabContent">

                    <!-- TAB 1: PENGETAHUAN (KI-3) -->
                    <div class="tab-pane fade show active" id="ki3-pane" role="tabpanel" aria-labelledby="ki3-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1000px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 text-center" style="width: 50px;">No</th>
                                        <th style="width: 220px;">Nama Siswa</th>
                                        <th class="text-center" style="width: 90px;">Harian<br><span class="fw-normal text-muted fs-7">(20%)</span></th>
                                        <th class="text-center" style="width: 90px;">Tugas<br><span class="fw-normal text-muted fs-7">(20%)</span></th>
                                        <th class="text-center" style="width: 90px;">Quiz<br><span class="fw-normal text-muted fs-7">(10%)</span></th>
                                        <th class="text-center" style="width: 90px;">UTS<br><span class="fw-normal text-muted fs-7">(25%)</span></th>
                                        <th class="text-center" style="width: 90px;">UAS<br><span class="fw-normal text-muted fs-7">(25%)</span></th>
                                        <th class="text-center" style="width: 90px;">Akhir KI-3</th>
                                        <th class="text-center" style="width: 80px;">Predikat</th>
                                        <th class="text-center" style="width: 90px;">Status</th>
                                        <th class="pe-3" style="width: 160px;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswaList as $siswa)
                                    @php
                                    $nilai = $siswa->penilaian->first();
                                    $hasNilaiKI3 = !is_null($nilai?->nilai_pengetahuan);
                                    @endphp
                                    <tr class="row-ki3" data-siswa-id="{{ $siswa->id }}">
                                        <td class="ps-3 text-center fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                            <small class="text-muted fs-7">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][nilai_harian]"
                                                value="{{ old("nilai.{$siswa->id}.nilai_harian", $nilai?->nilai_harian) }}"
                                                class="form-control form-control-sm text-center input-score input-harian" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][tugas]"
                                                value="{{ old("nilai.{$siswa->id}.tugas", $nilai?->tugas) }}"
                                                class="form-control form-control-sm text-center input-score input-tugas" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][quiz]"
                                                value="{{ old("nilai.{$siswa->id}.quiz", $nilai?->quiz) }}"
                                                class="form-control form-control-sm text-center input-score input-quiz" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][uts]"
                                                value="{{ old("nilai.{$siswa->id}.uts", $nilai?->uts) }}"
                                                class="form-control form-control-sm text-center input-score input-uts" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][uas]"
                                                value="{{ old("nilai.{$siswa->id}.uas", $nilai?->uas) }}"
                                                class="form-control form-control-sm text-center input-score input-uas" placeholder="0">
                                        </td>

                                        <td class="text-center fw-bold fs-6 text-primary score-akhir-ki3">
                                            {{ $nilai?->nilai_pengetahuan ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            <span @class([ 'badge badge-predikat-ki3' , 'bg-success'=> $nilai?->predikat_pengetahuan === 'A',
                                                'bg-info text-dark' => $nilai?->predikat_pengetahuan === 'B',
                                                'bg-warning text-dark' => $nilai?->predikat_pengetahuan === 'C',
                                                'bg-danger' => $nilai?->predikat_pengetahuan === 'D',
                                                'bg-secondary' => !$nilai?->predikat_pengetahuan,
                                                ])>
                                                {{ $nilai?->predikat_pengetahuan ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span @class([ 'badge badge-status-ki3' , 'bg-danger'=> $hasNilaiKI3 && $nilai->nilai_pengetahuan < 75, 'bg-success'=> $hasNilaiKI3 && $nilai->nilai_pengetahuan >= 75,
                                                    'bg-secondary' => !$hasNilaiKI3,
                                                    ])>
                                                    {{ $hasNilaiKI3 ? ($nilai->nilai_pengetahuan < 75 ? 'Remedial' : 'Tuntas') : '-' }}
                                            </span>
                                        </td>

                                        <td class="pe-3">
                                            <input type="text"
                                                name="nilai[{{ $siswa->id }}][catatan]"
                                                value="{{ old("nilai.{$siswa->id}.catatan", $nilai?->catatan) }}"
                                                class="form-control form-control-sm input-catatan" placeholder="Catatan...">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="bi bi-person-x fs-2 d-block mb-2 text-secondary"></i>
                                            Tidak ada data siswa ditemukan untuk rombel ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: KETERAMPILAN (KI-4) -->
                    <div class="tab-pane fade" id="ki4-pane" role="tabpanel" aria-labelledby="ki4-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 text-center" style="width: 50px;">No</th>
                                        <th style="width: 250px;">Nama Siswa</th>
                                        <th class="text-center" style="width: 110px;">Praktik<br><span class="fw-normal text-muted fs-7">(40%)</span></th>
                                        <th class="text-center" style="width: 110px;">Proyek<br><span class="fw-normal text-muted fs-7">(30%)</span></th>
                                        <th class="text-center" style="width: 110px;">Portofolio<br><span class="fw-normal text-muted fs-7">(30%)</span></th>
                                        <th class="text-center" style="width: 100px;">Akhir KI-4</th>
                                        <th class="text-center" style="width: 90px;">Predikat</th>
                                        <th class="text-center pe-3" style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswaList as $siswa)
                                    @php
                                    $nilai = $siswa->penilaian->first();
                                    $hasNilaiKI4 = !is_null($nilai?->nilai_keterampilan);
                                    @endphp
                                    <tr class="row-ki4" data-siswa-id="{{ $siswa->id }}">
                                        <td class="ps-3 text-center fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</div>
                                            <small class="text-muted fs-7">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][praktik]"
                                                value="{{ old("nilai.{$siswa->id}.praktik", $nilai?->praktik) }}"
                                                class="form-control form-control-sm text-center input-score input-praktik" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][proyek]"
                                                value="{{ old("nilai.{$siswa->id}.proyek", $nilai?->proyek) }}"
                                                class="form-control form-control-sm text-center input-score input-proyek" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" inputmode="decimal" autocomplete="off"
                                                name="nilai[{{ $siswa->id }}][portofolio]"
                                                value="{{ old("nilai.{$siswa->id}.portofolio", $nilai?->portofolio) }}"
                                                class="form-control form-control-sm text-center input-score input-portofolio" placeholder="0">
                                        </td>

                                        <td class="text-center fw-bold fs-6 text-primary score-akhir-ki4">
                                            {{ $nilai?->nilai_keterampilan ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            <span @class([ 'badge badge-predikat-ki4' , 'bg-success'=> $nilai?->predikat_keterampilan === 'A',
                                                'bg-info text-dark' => $nilai?->predikat_keterampilan === 'B',
                                                'bg-warning text-dark' => $nilai?->predikat_keterampilan === 'C',
                                                'bg-danger' => $nilai?->predikat_keterampilan === 'D',
                                                'bg-secondary' => !$nilai?->predikat_keterampilan,
                                                ])>
                                                {{ $nilai?->predikat_keterampilan ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="text-center pe-3">
                                            <span @class([ 'badge badge-status-ki4' , 'bg-danger'=> $hasNilaiKI4 && $nilai->nilai_keterampilan < 75, 'bg-success'=> $hasNilaiKI4 && $nilai->nilai_keterampilan >= 75,
                                                    'bg-secondary' => !$hasNilaiKI4,
                                                    ])>
                                                    {{ $hasNilaiKI4 ? ($nilai->nilai_keterampilan < 75 ? 'Remedial' : 'Tuntas') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="bi bi-person-x fs-2 d-block mb-2 text-secondary"></i>
                                            Tidak ada data siswa ditemukan untuk rombel ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-white py-3 px-4 text-end border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="statusPerubahan">
                    <i class="bi bi-info-circle me-1"></i>Belum ada perubahan data.
                </span>
                <button type="submit" class="btn btn-success px-4 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Simpan Semua Penilaian
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

        // --- KONFIGURASI BOBOT & KKM ---
        const CONFIG = {
            KKM: 75,
            WEIGHTS_KI3: {
                harian: 0.20,
                tugas: 0.20,
                quiz: 0.10,
                uts: 0.25,
                uas: 0.25
            },
            WEIGHTS_KI4: {
                praktik: 0.40,
                proyek: 0.30,
                portofolio: 0.30
            }
        };

        let isFormDirty = false;
        const statusPerubahan = document.getElementById('statusPerubahan');

        // 1. Unsaved Changes Flag
        const markAsDirty = () => {
            if (!isFormDirty) {
                isFormDirty = true;
                if (statusPerubahan) {
                    statusPerubahan.innerHTML = `<i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Ada perubahan yang belum disimpan!`;
                    statusPerubahan.classList.add('text-warning', 'fw-semibold');
                }
            }
        };

        // 2. Helper Functions
        const getInputValue = (row, selector) => {
            const input = row.querySelector(selector);
            if (!input || input.value.trim() === '') return null;

            let val = parseFloat(input.value);
            if (isNaN(val)) return null;

            if (val > 100) {
                val = 100;
                input.value = 100;
            }
            if (val < 0) {
                val = 0;
                input.value = 0;
            }
            return val;
        };

        const getPredikat = (nilai) => {
            if (nilai >= 90) return {
                letter: 'A',
                class: 'bg-success'
            };
            if (nilai >= 80) return {
                letter: 'B',
                class: 'bg-info text-dark'
            };
            if (nilai >= 75) return {
                letter: 'C',
                class: 'bg-warning text-dark'
            };
            return {
                letter: 'D',
                class: 'bg-danger'
            };
        };

        // 3. Kalkulasi KI-3 (Pengetahuan)
        const calculateKI3 = (row) => {
            const harian = getInputValue(row, '.input-harian');
            const tugas = getInputValue(row, '.input-tugas');
            const quiz = getInputValue(row, '.input-quiz');
            const uts = getInputValue(row, '.input-uts');
            const uas = getInputValue(row, '.input-uas');

            const scoreAkhir = row.querySelector('.score-akhir-ki3');
            const badgePredikat = row.querySelector('.badge-predikat-ki3');
            const badgeStatus = row.querySelector('.badge-status-ki3');

            // Harus terisi SEMUA 5 komponen agar nilai akhir dihitung (Sinkron dengan Controller)
            if (harian === null || tugas === null || quiz === null || uts === null || uas === null) {
                if (scoreAkhir) scoreAkhir.textContent = '-';
                if (badgePredikat) {
                    badgePredikat.textContent = '-';
                    badgePredikat.className = 'badge bg-secondary badge-predikat-ki3';
                }
                if (badgeStatus) {
                    badgeStatus.textContent = '-';
                    badgeStatus.className = 'badge bg-secondary badge-status-ki3';
                }
                return;
            }

            const akhir = (harian * CONFIG.WEIGHTS_KI3.harian) +
                (tugas * CONFIG.WEIGHTS_KI3.tugas) +
                (quiz * CONFIG.WEIGHTS_KI3.quiz) +
                (uts * CONFIG.WEIGHTS_KI3.uts) +
                (uas * CONFIG.WEIGHTS_KI3.uas);

            if (scoreAkhir) scoreAkhir.textContent = akhir.toFixed(2);

            const predikat = getPredikat(akhir);
            if (badgePredikat) {
                badgePredikat.textContent = predikat.letter;
                badgePredikat.className = `badge ${predikat.class} badge-predikat-ki3`;
            }

            if (badgeStatus) {
                if (akhir < CONFIG.KKM) {
                    badgeStatus.textContent = 'Remedial';
                    badgeStatus.className = 'badge bg-danger badge-status-ki3';
                } else {
                    badgeStatus.textContent = 'Tuntas';
                    badgeStatus.className = 'badge bg-success badge-status-ki3';
                }
            }
        };

        // 4. Kalkulasi KI-4 (Keterampilan)
        const calculateKI4 = (row) => {
            const praktik = getInputValue(row, '.input-praktik');
            const proyek = getInputValue(row, '.input-proyek');
            const portofolio = getInputValue(row, '.input-portofolio');

            const scoreAkhir = row.querySelector('.score-akhir-ki4');
            const badgePredikat = row.querySelector('.badge-predikat-ki4');
            const badgeStatus = row.querySelector('.badge-status-ki4');

            // Harus terisi SEMUA 3 komponen agar nilai akhir dihitung
            if (praktik === null || proyek === null || portofolio === null) {
                if (scoreAkhir) scoreAkhir.textContent = '-';
                if (badgePredikat) {
                    badgePredikat.textContent = '-';
                    badgePredikat.className = 'badge bg-secondary badge-predikat-ki4';
                }
                if (badgeStatus) {
                    badgeStatus.textContent = '-';
                    badgeStatus.className = 'badge bg-secondary badge-status-ki4';
                }
                return;
            }

            const akhir = (praktik * CONFIG.WEIGHTS_KI4.praktik) +
                (proyek * CONFIG.WEIGHTS_KI4.proyek) +
                (portofolio * CONFIG.WEIGHTS_KI4.portofolio);

            if (scoreAkhir) scoreAkhir.textContent = akhir.toFixed(2);

            const predikat = getPredikat(akhir);
            if (badgePredikat) {
                badgePredikat.textContent = predikat.letter;
                badgePredikat.className = `badge ${predikat.class} badge-predikat-ki4`;
            }

            if (badgeStatus) {
                if (akhir < CONFIG.KKM) {
                    badgeStatus.textContent = 'Remedial';
                    badgeStatus.className = 'badge bg-danger badge-status-ki4';
                } else {
                    badgeStatus.textContent = 'Tuntas';
                    badgeStatus.className = 'badge bg-success badge-status-ki4';
                }
            }
        };

        // 5. Inisialisasi Kalkulasi Saat Pertama Muat
        document.querySelectorAll('.row-ki3').forEach(calculateKI3);
        document.querySelectorAll('.row-ki4').forEach(calculateKI4);

        // 6. Event Listeners Input & Highlight Baris
        formPenilaian.addEventListener('focusin', (e) => {
            if (e.target.classList.contains('input-score')) {
                e.target.select(); // Auto-select text seperti Excel
            }
        });

        formPenilaian.addEventListener('input', (e) => {
            if (e.target.classList.contains('input-score') || e.target.classList.contains('input-catatan')) {
                markAsDirty();

                const rowKI3 = e.target.closest('.row-ki3');
                const rowKI4 = e.target.closest('.row-ki4');

                if (rowKI3) {
                    rowKI3.classList.add('table-warning');
                    if (e.target.classList.contains('input-score')) calculateKI3(rowKI3);
                }

                if (rowKI4) {
                    rowKI4.classList.add('table-warning');
                    if (e.target.classList.contains('input-score')) calculateKI4(rowKI4);
                }
            }
        });

        // 7. Navigasi Keyboard (Enter, Up, Down)
        formPenilaian.addEventListener('keydown', (e) => {
            if (!e.target.classList.contains('input-score') && !e.target.classList.contains('input-catatan')) return;

            const currentInput = e.target;
            const currentTd = currentInput.closest('td');
            const currentRow = currentInput.closest('tr');
            if (!currentTd || !currentRow) return;

            const colIndex = Array.from(currentRow.children).indexOf(currentTd);

            if (e.key === 'Enter' || e.key === 'ArrowDown') {
                e.preventDefault();
                const nextRow = currentRow.nextElementSibling;
                if (nextRow) {
                    const targetInput = nextRow.children[colIndex]?.querySelector('input');
                    if (targetInput) targetInput.focus();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevRow = currentRow.previousElementSibling;
                if (prevRow) {
                    const targetInput = prevRow.children[colIndex]?.querySelector('input');
                    if (targetInput) targetInput.focus();
                }
            }
        });

        // 8. Proteksi Berpindah Halaman Saat Form Belum Disimpan
        window.addEventListener('beforeunload', (e) => {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.querySelectorAll('.btn-rekap-guard').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (isFormDirty && !confirm('Ada nilai yang belum disimpan! Perubahan akan hilang jika berpindah halaman. Lanjutkan?')) {
                    e.preventDefault();
                }
            });
        });

        // 9. Validasi Submit Form (Warning Input Kosong & Reset Flag)
        formPenilaian.addEventListener('submit', (e) => {
            const emptyInputs = Array.from(formPenilaian.querySelectorAll('.input-score'))
                .filter(input => input.value.trim() === '');

            if (emptyInputs.length > 0) {
                const confirmSave = confirm(
                    `Terdapat ${emptyInputs.length} kolom nilai yang belum diisi.\n\nYakin ingin menyimpan data yang sudah ada?`
                );

                if (!confirmSave) {
                    e.preventDefault(); // Batalkan pengiriman form
                    return;
                }
            }

            isFormDirty = false; // Matikan flag unsaved agar warning window sebelum refresh tidak muncul
        });
    });
</script>
@endpush