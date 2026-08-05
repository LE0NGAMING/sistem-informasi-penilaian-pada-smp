@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Presensi Siswa</h3>
            <p class="text-muted small mb-0">Pemantauan presensi otomatis (Mesin/TAP) dan koreksi manual.</p>
        </div>
        <a href="{{ route('admin.presensi.rekap') }}" class="btn btn-outline-primary px-3">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Rekapitulasi Rapor
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.presensi.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="kelas_id" class="form-label fw-semibold">Pilih Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" id="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="tanggal" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $selectedTanggal }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedKelas)
    <form action="{{ route('admin.presensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $selectedKelas }}">
        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Siswa</th>
                                <th>Waktu Tap</th>
                                <th class="text-center">Metode</th>
                                <th class="text-center" style="width: 320px;">Status Kehadiran</th>
                                <th class="pe-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            @php
                            $p = $presensis[$siswa->id] ?? null;
                            $status = $p->status ?? 'hadir';
                            $metode = $p->metode ?? 'manual';
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <small class="text-muted font-monospace">NISN: {{ $siswa->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="bi bi-box-arrow-in-right text-success me-1"></i> {{ $p->jam_masuk ?? '--:--' }}
                                        <br>
                                        <i class="bi bi-box-arrow-right text-danger me-1"></i> {{ $p->jam_pulang ?? '--:--' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($metode === 'mesin_rfid')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-8">
                                        <i class="bi bi-card-heading me-1"></i> RFID
                                    </span>
                                    @elseif($metode === 'face_recognition')
                                    <span class="badge bg-purple-subtle text-purple border px-2 py-1 fs-8">
                                        <i class="bi bi-person-bounding-box me-1"></i> Face Scan
                                    </span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-8">
                                        <i class="bi bi-pencil-square me-1"></i> Manual
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="presensi[{{ $siswa->id }}][status]" id="h_{{ $siswa->id }}" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm" for="h_{{ $siswa->id }}">Hadir</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $siswa->id }}][status]" id="s_{{ $siswa->id }}" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning btn-sm" for="s_{{ $siswa->id }}">Sakit</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $siswa->id }}][status]" id="i_{{ $siswa->id }}" value="izin" {{ $status == 'izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info btn-sm" for="i_{{ $siswa->id }}">Izin</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $siswa->id }}][status]" id="a_{{ $siswa->id }}" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm" for="a_{{ $siswa->id }}">Alpa</label>
                                    </div>
                                </td>
                                <td class="pe-4">
                                    <input type="text" name="presensi[{{ $siswa->id }}][keterangan]" class="form-control form-control-sm" placeholder="Catatan (misal: Izin Lomba)" value="{{ $p->keterangan ?? '' }}">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Belum ada data siswa di kelas ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($siswas->count() > 0)
            <div class="card-footer bg-white border-0 py-3 text-end pe-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan / Perbarui Presensi
                </button>
            </div>
            @endif
        </div>
    </form>
    @else
    <div class="alert alert-info border-0 shadow-sm rounded-3">
        <i class="bi bi-info-circle me-2"></i> Silakan pilih **Kelas** dan **Tanggal** untuk menampilkan data presensi.
    </div>
    @endif
</div>
@endsection