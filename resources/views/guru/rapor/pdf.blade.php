<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Siswa</title>
    <style>
        body {
            font-family: 'Helvetica, Arial, sans-serif';
            color: #333;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }

        .student-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 4px 0;
        }

        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-nilai th,
        .table-nilai td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }

        .table-nilai th {
            background-color: #f2f2f2;
            font-size: 11px;
        }

        .text-left {
            text-align: left !important;
        }

        .footer-sign {
            width: 100%;
            margin-top: 40px;
        }

        .footer-sign td {
            text-align: center;
            width: 50%;
        }

        .sign-space {
            height: 60px;
        }
    </style>
</head>

<body>

    @foreach($siswaList as $index => $siswa)
    <div class="header">
        <h2>Laporan Hasil Belajar Siswa (Rapor)</h2>
        <p>Tahun Ajaran Aktif | Semester: {{ ucfirst($semester ?? 'Lengkap') }}</p>
    </div>

    {{-- Informasi Siswa --}}
    <table class="student-info">
        <tr>
            <td style="width: 15%;"><strong>Nama Siswa</strong></td>
            <td style="width: 35%;">: {{ $siswa->nama_lengkap }}</td>
            <td style="width: 15%;"><strong>Kelas</strong></td>
            <td style="width: 35%;">: {{ $siswa->rombel->nama_rombel ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>NISN</strong></td>
            <td>: {{ $siswa->nisn ?? '-' }}</td>
            <td><strong>Wali Kelas</strong></td>
            <td>: {{ $siswa->rombel->waliKelas->nama_lengkap ?? '-' }}</td>
        </tr>
    </table>

    {{-- Tabel Nilai Mengikuti Kolom Database Anda --}}
    <table class="table-nilai">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No</th>
                <th rowspan="2" class="text-left" style="width: 25%;">Mata Pelajaran</th>
                <th colspan="6">Komponen Penilaian</th>
                <th rowspan="2" style="width: 8%;">Akhir</th>
                <th rowspan="2" style="width: 6%;">Predikat</th>
                <th rowspan="2" style="width: 15%;">Catatan Guru</th>
            </tr>
            <tr>
                <th>Harian</th>
                <th>Tugas</th>
                <th>Quiz</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Praktik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->penilaian as $nilai)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $nilai->mapel->nama_mapel ?? 'Mapel Dihapus' }}</td>
                <td>{{ $nilai->nilai_harian }}</td>
                <td>{{ $nilai->tugas }}</td>
                <td>{{ $nilai->quiz }}</td>
                <td>{{ $nilai->uts }}</td>
                <td>{{ $nilai->uas }}</td>
                <td>{{ $nilai->praktik }}</td>
                <td><strong>{{ $nilai->nilai_akhir }}</strong></td>
                <td><strong>{{ $nilai->predikat }}</strong></td>
                <td class="text-left" style="font-size: 10px;">{{ $nilai->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 15px; color: #666;">
                    Belum ada data penilaian untuk semester ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <table class="footer-sign">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p>Orang Tua / Wali Murid</p>
                <div class="sign-space"></div>
                <p><b>( .................................... )</b></p>
            </td>
            <td>
                <p>{{ $tanggalCetak }}</p>
                <p>Wali Kelas</p>
                <div class="sign-space"></div>
                <p><b>{{ $siswa->rombel->waliKelas->nama_lengkap ?? '____________________' }}</b></p>
            </td>
        </tr>
    </table>

    {{-- Page Break jika data lebih dari 1 siswa (untuk cetak massal rombel) --}}
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

</body>

</html>