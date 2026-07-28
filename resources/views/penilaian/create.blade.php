@extends('layouts.app')

@section('title', 'Input Penilaian Siswa')

@section('content')
<div class="mb-4">
    <a href="{{ route('penilaian.index') }}" class="text-decoration-none text-muted fs-7">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penilaian
    </a>
    <h4 class="fw-bold mt-2">Form Input Nilai Siswa</h4>
</div>

<form action="{{ route('penilaian.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Form Left: Informasi Master & Komponen Nilai -->
        <div class="col-12 col-lg-8">
            <div class="card-premium p-4 mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">1. Data Akademik</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-7 fw-semibold">Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-select fs-7 @error('siswa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            <!-- Loop options dari controller -->
                        </select>
                        @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-7 fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" class="form-select fs-7 @error('mapel_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                        @error('mapel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <input type="hidden" name="semester_id" value="1"> <!-- Dynamic based on active semester -->
                    <input type="hidden" name="rombel_id" value="1">
                    <input type="hidden" name="guru_id" value="{{ auth()->user()->guru->id ?? 1 }}">
                </div>
            </div>

            <div class="card-premium p-4 mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">2. Komponen Nilai (Skala 0 - 100)</h6>
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">Nilai Harian (15%)</label>
                        <input type="number" step="0.01" name="nilai_harian" class="form-control score-input" min="0" max="100" value="{{ old('nilai_harian', 0) }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">Tugas (15%)</label>
                        <input type="number" step="0.01" name="tugas" class="form-control score-input" min="0" max="100" value="{{ old('tugas', 0) }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">Quiz (10%)</label>
                        <input type="number" step="0.01" name="quiz" class="form-control score-input" min="0" max="100" value="{{ old('quiz', 0) }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">UTS (25%)</label>
                        <input type="number" step="0.01" name="uts" class="form-control score-input" min="0" max="100" value="{{ old('uts', 0) }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">UAS (25%)</label>
                        <input type="number" step="0.01" name="uas" class="form-control score-input" min="0" max="100" value="{{ old('uas', 0) }}">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label fs-7">Praktik (10%)</label>
                        <input type="number" step="0.01" name="praktik" class="form-control score-input" min="0" max="100" value="{{ old('praktik', 0) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="form-label fs-7 fw-semibold">Deskripsi Capaian Pembelajaran</label>
                    <textarea name="deskripsi" class="form-control fs-7" rows="3" placeholder="Otomatis terisi jika dikosongkan..."></textarea>
                </div>
            </div>
        </div>

        <!-- Form Right: Live Calculation Preview Box -->
        <div class="col-12 col-lg-4">
            <div class="card-premium p-4 sticky-top" style="top: 90px;">
                <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-2 text-primary"></i>Kalkulasi Otomatis (Live)</h6>
                <div class="p-3 mb-3 text-center rounded-3" style="background-color: var(--bs-primary-light);">
                    <div class="text-muted fs-8 text-uppercase fw-bold">Nilai Akhir Terkalkulasi</div>
                    <div class="display-5 fw-bold text-dark my-1" id="previewNilaiAkhir">0.00</div>
                    <div class="fs-7">Predikat: <span class="fw-bold" id="previewPredikat">-</span></div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 mb-2 border-bottom">
                    <span class="fs-7 text-muted">Batas KKM Standard:</span>
                    <span class="fw-bold fs-7">75</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 mb-4 border-bottom">
                    <span class="fs-7 text-muted">Status Kelulusan Mapel:</span>
                    <span id="previewStatus" class="badge bg-secondary">Memuat...</span>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.score-input');
        const previewNilaiAkhir = document.getElementById('previewNilaiAkhir');
        const previewPredikat = document.getElementById('previewPredikat');
        const previewStatus = document.getElementById('previewStatus');

        function calculateScore() {
            let values = {};
            inputs.forEach(input => {
                values[input.name] = parseFloat(input.value) || 0;
            });

            // Formula Bobot
            let nilaiAkhir = (values.nilai_harian * 0.15) +
                (values.tugas * 0.15) +
                (values.quiz * 0.10) +
                (values.uts * 0.25) +
                (values.uas * 0.25) +
                (values.praktik * 0.10);

            nilaiAkhir = Math.round(nilaiAkhir * 100) / 100;

            let predikat = 'D';
            if (nilaiAkhir >= 90) predikat = 'A';
            else if (nilaiAkhir >= 80) predikat = 'B';
            else if (nilaiAkhir >= 70) predikat = 'C';

            previewNilaiAkhir.textContent = nilaiAkhir.toFixed(2);
            previewPredikat.textContent = predikat;

            const kkm = 75;
            if (nilaiAkhir < kkm) {
                previewStatus.className = 'badge bg-danger';
                previewStatus.textContent = 'Perlu Remedial';
            } else {
                previewStatus.className = 'badge bg-success';
                previewStatus.textContent = 'Tuntas (Lulus KKM)';
            }
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateScore);
        });

        calculateScore(); // Trigger pertama saat page load
    });
</script>
@endpush