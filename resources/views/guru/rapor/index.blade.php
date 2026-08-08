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
                        {{-- Sesuaikan $semester->value dan $semester->label() dengan struktur kode Anda --}}
                        <option value="{{ $semester->value }}" @selected($semesterId==$semester->value)>
                            {{ $semester->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">
                        <i class="bi bi-search me-1"></i> Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bagian 2: Hasil Filter (Daftar Siswa) --}}
    @if($rombelId && $semesterId)
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    Daftar Siswa Kelas {{ $rombelTerpilih->nama_rombel ?? '-' }}
                </h6>
                <small class="text-muted">Pilih siswa untuk mencetak rapor secara individu atau cetak seluruh kelas.</small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('guru.rapor.rombel', ['rombel' => $rombelId, 'semester_id' => $semesterId]) }}"
                    target="_blank"
                    class="btn btn-danger btn-sm rounded-3 fw-semibold px-3 py-2">
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
                            <td>{{ $siswa->nisn ?? '-' }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('guru.rapor.siswa', ['siswa' => $siswa->id, 'semester_id' => $semesterId]) }}"
                                    target="_blank"
                                    class="btn btn-outline-danger btn-sm rounded-3">
                                    <i class="bi bi-printer"></i> Cetak Rapor
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
                                <h6 class="fw-semibold text-dark">Belum ada data siswa</h6>
                                <p class="mb-0 small">Tidak ada data siswa yang ditemukan pada rombel ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    {{-- Empty State jika filter belum diisi --}}
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body">
            <i class="bi bi-printer text-muted" style="font-size: 3rem;"></i>
            <h6 class="fw-bold mt-3 text-dark">Silakan Filter Terlebih Dahulu</h6>
            <p class="text-muted small mb-0">Pilih Rombel dan Semester di atas untuk menampilkan daftar siswa yang siap dicetak rapornya.</p>
        </div>
    </div>
    @endif

</div>
@endsection