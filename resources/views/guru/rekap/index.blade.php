@extends('layouts.app')

@section('title', 'Rekap Penilaian')
@section('page-title', 'Rekapitulasi Penilaian Siswa')

@section('content')
<div class="container-fluid px-0">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-filter me-2 text-primary"></i>Filter Rekapitulasi
            </h6>
            <form action="{{ route('guru.rekap.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="rombel_id" class="form-label small fw-semibold text-secondary">Rombongan Belajar</label>
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
                        <i class="bi bi-search me-1"></i> Tampilkan Rekap
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    @if($rombelId && $mapelId && $semesterId)
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 px-4 border-bottom-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">Data Rekapitulasi Nilai</h6>
                <small class="text-muted">Laporan hasil penilaian siswa per semester.</small>
            </div>
            <a href="{{ route('guru.rekap.cetak', ['rombel_id' => $rombelId, 'mapel_id' => $mapelId, 'semester_id' => $semesterId]) }}"
                target="_blank"
                class="btn btn-outline-danger rounded-3 fw-semibold">
                <i class="bi bi-printer me-1"></i> Cetak Laporan (PDF/Print)
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Harian</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">Quiz</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">Praktik</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Predikat</th>
                            <th class="pe-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                        @php $nilai = $siswa->penilaian->first(); @endphp
                        <tr>
                            <td class="ps-4 fw-semibold text-secondary">
                                {{ $siswaList->firstItem() ? $siswaList->firstItem() + $loop->index : $loop->iteration }}
                            </td>
                            <td>{{ $siswa->nisn ?? '-' }}</td>
                            <td class="fw-semibold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td class="text-center">{{ $nilai?->nilai_harian ?? '-' }}</td>
                            <td class="text-center">{{ $nilai?->tugas ?? '-' }}</td>
                            <td class="text-center">{{ $nilai?->quiz ?? '-' }}</td>
                            <td class="text-center">{{ $nilai?->uts ?? '-' }}</td>
                            <td class="text-center">{{ $nilai?->uas ?? '-' }}</td>
                            <td class="text-center">{{ $nilai?->praktik ?? '-' }}</td>
                            <td class="text-center fw-bold text-primary">
                                {{ $nilai?->nilai_akhir !== null ? number_format($nilai->nilai_akhir, 2) : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $nilai?->predikat ?? '-' }}</span>
                            </td>
                            <td class="pe-4 text-center">
                                @if($nilai)
                                <span @class([ 'badge' , 'bg-danger'=> $nilai->is_remedial,
                                    'bg-success' => !$nilai->is_remedial
                                    ])>
                                    {{ $nilai->is_remedial ? 'Remedial' : 'Tuntas' }}
                                </span>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-muted">
                                Tidak ada data siswa ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Pagination --}}
        @if($siswaList->hasPages())
        <div class="card-footer bg-white py-3 px-4 border-top-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan <b>{{ $siswaList->firstItem() }}</b> - <b>{{ $siswaList->lastItem() }}</b> dari <b>{{ $siswaList->total() }}</b> siswa
            </small>
            <div>
                {{ $siswaList->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection