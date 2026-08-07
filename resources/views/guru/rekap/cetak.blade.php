<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Nilai - {{ $rombel->nama_rombel }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
        }

        .kop-surat {
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            padding: 6px 8px;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container-fluid p-4">
        {{-- Tombol Kembali & Print Manual --}}
        <div class="mb-4 no-print d-flex justify-content-between">
            <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
            <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
        </div>

        {{-- Kop Surat / Header Laporan --}}
        <div class="kop-surat text-center">
            <h4 class="fw-bold mb-1">LAPORAN REKAPITULASI PENILAIAN SISWA</h4>
            <h5 class="fw-normal mb-0">SMP NEGERI SISTEM INFORMASI</h5>
            <small class="text-muted">Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</small>
        </div>

        {{-- Meta Informasi --}}
        <table class="table table-borderless table-sm mb-3" style="width: 100%;">
            <tr>
                <td style="width: 15%;"><strong>Rombongan Belajar</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">{{ $rombel->nama_rombel }}</td>
                <td style="width: 15%;"><strong>Mata Pelajaran</strong></td>
                <td style="width: 2%;">:</td>
                <td>{{ $mapel->nama_mapel }}</td>
            </tr>
            <tr>
                <td><strong>Semester</strong></td>
                <td>:</td>
                <td>{{ $semester?->label() ?? '-' }}</td>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td>{{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        {{-- Tabel Data --}}
        <table class="table table-bordered align-middle text-center" style="font-size: 13px;">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 120px;">NISN</th>
                    <th class="text-start">Nama Siswa</th>
                    <th style="width: 65px;">Harian</th>
                    <th style="width: 65px;">Tugas</th>
                    <th style="width: 65px;">Quiz</th>
                    <th style="width: 65px;">UTS</th>
                    <th style="width: 65px;">UAS</th>
                    <th style="width: 65px;">Praktik</th>
                    <th style="width: 75px;">Akhir</th>
                    <th style="width: 65px;">Predikat</th>
                    <th style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $siswa)
                @php $nilai = $siswa->penilaian->first(); @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $siswa->nisn ?? '-' }}</td>
                    <td class="text-start">{{ $siswa->nama_lengkap }}</td>
                    <td>{{ $nilai?->nilai_harian ?? '-' }}</td>
                    <td>{{ $nilai?->tugas ?? '-' }}</td>
                    <td>{{ $nilai?->quiz ?? '-' }}</td>
                    <td>{{ $nilai?->uts ?? '-' }}</td>
                    <td>{{ $nilai?->uas ?? '-' }}</td>
                    <td>{{ $nilai?->praktik ?? '-' }}</td>
                    <td class="fw-bold">{{ $nilai?->nilai_akhir ?? '-' }}</td>
                    <td>{{ $nilai?->predikat ?? '-' }}</td>
                    <td>{{ $nilai?->is_remedial ? 'Remedial' : 'Tuntas' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center py-3">Tidak ada data penilaian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Tanda Tangan --}}
        <table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
            <tr>
                {{-- Kolom Kepala Sekolah --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0; font-weight: bold;">Kepala Sekolah</p>

                    {{-- Jarak untuk Tanda Tangan --}}
                    <div style="height: 80px;"></div>

                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        ( {{ $kepalaSekolah->nama ?? '.........................................' }} )
                    </p>
                    <p style="margin: 0;">
                        NIP. {{ $kepalaSekolah->nip ?? '.........................................' }}
                    </p>
                </td>

                {{-- Kolom Guru Mata Pelajaran --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    {{-- Spasi penyama tinggi baris dengan "Mengetahui," --}}
                    <p style="margin: 0;">&nbsp;</p>
                    <p style="margin: 0; font-weight: bold;">Guru Mata Pelajaran</p>

                    {{-- Jarak untuk Tanda Tangan --}}
                    <div style="height: 80px;"></div>

                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        ( {{ $guru->nama ?? 'Kamila Palastri, S.Kom.' }} )
                    </p>
                    <p style="margin: 0;">
                        NIP. {{ $guru->nip ?? '.........................................' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>