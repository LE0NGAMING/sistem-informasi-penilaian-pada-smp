<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rapor - {{ $siswa->nama_lengkap ?? 'Siswa' }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 9pt;
        }

        .table-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 10pt;
        }

        .text-center {
            text-align: center;
        }

        .footer-ttd {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }

        .footer-ttd td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .space-ttd {
            height: 60px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN HASIL BELAJAR SISWA</h2>
        <p>SEKOLAH MENENGAH PERTAMA / KEJURUAN</p>
    </div>

    <table class="table-info">
        <tr>
            <td width="15%"><strong>Nama Siswa</strong></td>
            <td width="2%">:</td>
            <td width="40%">{{ $siswa->nama_lengkap ?? '-' }}</td>
            <td width="15%"><strong>Kelas/Rombel</strong></td>
            <td width="2%">:</td>
            <td width="26%">{{ $siswa->rombel->nama_rombel ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>NISN</strong></td>
            <td>:</td>
            <td>{{ $siswa->nisn ?? '-' }}</td>
            <td><strong>Semester</strong></td>
            <td>:</td>
            <td>{{ $semester ?? '-' }}</td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th width="12%">Nilai Akhir</th>
                <th width="12%">Predikat</th>
                <th width="15%">Status</th>
                <th width="25%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penilaianList as $penilaian)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $penilaian->mapel->nama_mapel ?? '-' }}</td>
                <td class="text-center">
                    {{ $penilaian->nilai_akhir !== null ? number_format($penilaian->nilai_akhir, 2) : '-' }}
                </td>
                <td class="text-center"><strong>{{ $penilaian->predikat ?? '-' }}</strong></td>
                <td class="text-center">
                    @if(!is_null($penilaian->is_remedial))
                    {{ $penilaian->is_remedial ? 'Remedial' : 'Tuntas' }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $penilaian->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data penilaian untuk semester ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-ttd">
        <tr>
            <td>
                <p>Mengetahui,<br>Orang Tua/Wali Siswa</p>
                <div class="space-ttd"></div>
                <p>______________________</p>
            </td>
            <td>
                <p>Jakarta, {{ $tanggalCetak ?? now()->format('d-m-Y') }}<br>Wali Kelas</p>
                <div class="space-ttd"></div>
                <p><strong>{{ $siswa->rombel->waliKelas->name ?? '........................' }}</strong></p>
            </td>
        </tr>
    </table>

</body>

</html>