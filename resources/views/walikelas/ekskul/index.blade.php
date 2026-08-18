@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Input Nilai Ekstrakurikuler</h4>
            <p class="text-muted small mb-0">Rombongan Belajar: <span class="fw-bold text-primary">{{ $rombel->nama_rombel }}</span></p>
        </div>
    </div>

    {{-- Alert Success / Error --}}
    <!-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif -->

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('walikelas.ekskul.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="tahun_ajaran_id" class="form-label small fw-semibold text-secondary">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select rounded-3">
                        @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id }}" @selected($tahunAjaranId==$ta->id)>
                            {{ $ta->tahun }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="semester" class="form-label small fw-semibold text-secondary">Semester</label>
                    <select name="semester" id="semester" class="form-select rounded-3">
                        <option value="ganjil" @selected($semester=='ganjil' )>Ganjil</option>
                        <option value="genap" @selected($semester=='genap' )>Genap</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Input Nilai Ekskul --}}
    <form action="{{ route('walikelas.ekskul.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
        <input type="hidden" name="semester" value="{{ $semester }}">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center px-3" style="width: 50px;">No</th>
                                <th>Nama Siswa / NISN</th>
                                <th style="width: 250px;">Kegiatan Ekstrakurikuler</th>
                                <th style="width: 120px;">Predikat</th>
                                <th>Keterangan / Deskripsi Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $index => $siswa)
                            @foreach($ekskulList as $ekskulIndex => $ekskul)
                            @php
                            // Cari nilai ekskul siswa yang sudah tersimpan sebelumnya
                            $nilaiExist = $siswa->nilaiEkskul->firstWhere('ekstrakurikuler_id', $ekskul->id);
                            @endphp
                            <tr>
                                {{-- Nomor & Nama hanya tampil di baris pertama tiap siswa --}}
                                @if($ekskulIndex === 0)
                                <td class="text-center fw-bold align-top pt-3" rowspan="{{ count($ekskulList) }}">
                                    {{ $loop->parent->iteration }}
                                </td>
                                <td class="align-top pt-3" rowspan="{{ count($ekskulList) }}">
                                    <div class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <small class="text-muted">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                </td>
                                @endif

                                {{-- Nama Ekskul --}}
                                <td class="fw-semibold text-secondary">
                                    <i class="bi bi-star-fill text-warning me-1 small"></i>
                                    {{ $ekskul->nama_ekskul }}
                                </td>

                                {{-- Input Predikat --}}
                                <td>
                                    <select name="nilai[{{ $siswa->id }}][{{ $ekskul->id }}][predikat]"
                                        class="form-select form-select-sm rounded-2 text-center fw-bold">
                                        <option value="">--</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $p)
                                        <option value="{{ $p }}" @selected(old("nilai.{$siswa->id}.{$ekskul->id}.predikat", $nilaiExist?->predikat) == $p)>
                                            {{ $p }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- Input Keterangan --}}
                                <td>
                                    <input type="text"
                                        name="nilai[{{ $siswa->id }}][{{ $ekskul->id }}][keterangan]"
                                        class="form-control form-control-sm rounded-2"
                                        placeholder="Contoh: Sangat aktif dalam kegiatan keanggotaan..."
                                        value="{{ old("nilai.{$siswa->id}.{$ekskul->id}.keterangan", $nilaiExist?->keterangan) }}">
                                </td>
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people display-6 d-block mb-2"></i>
                                    Belum ada siswa di rombel ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($siswaList->isNotEmpty())
            <div class="card-footer bg-white p-3 border-0 d-flex justify-content-end rounded-bottom-4">
                <button type="submit" class="btn btn-success px-4 rounded-3 shadow-sm">
                    <i class="bi bi-save me-2"></i> Simpan Nilai Ekstrakurikuler
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection