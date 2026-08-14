@extends('layouts.app')

@section('title', 'Cetak Rapor')
@section('page-title', 'Cetak Rapor Siswa')

@section('content')
<div class="container-fluid px-0">

    {{-- Bagian 1: Card Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-printer me-2 text-primary"></i>Filter Rapor
            </h6>

            <form action="{{ route('guru.rapor.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
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

                <div class="col-md-4">
                    <label for="semester_id" class="form-label small fw-semibold text-secondary">Semester</label>
                    <select name="semester_id" id="semester_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesterList as $semester)
                        @php
                        $semVal = is_object($semester) && property_exists($semester, 'value') ? $semester->value : $semester;
                        $semLabel = method_exists($semester, 'label') ? $semester->label() : (is_object($semester) ? $semester->name : $semester);
                        @endphp
                        <option value="{{ $semVal }}" @selected($semesterId==$semVal)>
                            {{ $semLabel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">
                        <i class="bi bi-search me-1"></i> Tampilkan Data
                    </button>

                    @if($rombelId || $semesterId)
                    <a href="{{ route('guru.rapor.index') }}" class="btn btn-light rounded-3 fw-semibold" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Bagian 2: Hasil Filter (Daftar Siswa) --}}
    @if($rombelId && $semesterId)
    @php
    $namaRombelSelected = $rombelList->firstWhere('id', $rombelId)?->nama_rombel ?? '-';
    @endphp

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                    Daftar Siswa Kelas {{ $namaRombelSelected }}
                    <span class="badge bg-primary-subtle text-primary rounded-pill fs-7 fw-normal">
                        {{ count($siswaList) }} Siswa
                    </span>
                </h6>
                <small class="text-muted">Pilih siswa untuk mencetak rapor secara individu atau cetak seluruh kelas sekaligus.</small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('guru.rapor.rombel', ['rombel' => $rombelId, 'semester_id' => $semesterId]) }}"
                    target="_blank"
                    class="btn btn-danger btn-sm rounded-3 fw-semibold px-3 py-2 shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Semua (1 Rombel)
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 5%;">No</th>
                            <th style="width: 20%;">NISN</th>
                            <th style="width: 55%;">Nama Lengkap</th>
                            <th class="pe-4 text-end" style="width: 20%;">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace fw-normal border">
                                    {{ $siswa->nisn ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('guru.rapor.siswa', ['siswa' => $siswa->id, 'semester_id' => $semesterId]) }}"
                                    target="_blank"
                                    class="btn btn-outline-danger btn-sm rounded-3 fw-medium">
                                    <i class="bi bi-printer me-1"></i> Cetak Rapor
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                <h6 class="fw-semibold text-dark">Belum Ada Data Siswa</h6>
                                <p class="mb-0 small">Tidak ada data siswa yang terdaftar pada rombel ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    {{-- Empty State jika filter belum dipilih --}}
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body py-4">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-printer fs-2"></i>
            </div>
            <h6 class="fw-bold text-dark">Silakan Filter Terlebih Dahulu</h6>
            <p class="text-muted small mb-0" style="max-width: 450px; margin: 0 auto;">
                Pilih Rombongan Belajar (Rombel) dan Semester pada form di atas untuk menampilkan daftar siswa yang siap dicetak rapornya.
            </p>
        </div>
    </div>
    @endif

</div>
@endsection