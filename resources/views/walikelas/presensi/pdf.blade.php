<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Presensi - {{ $namaBulan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 16px;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .ttd-container {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 200px;
            text-align: center;
        }

        .ttd-space {
            height: 60px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <div class="header">
        <h2>REKAPITULASI PRESENSI SISWA</h2>
        <p>Kelas: <strong>{{ $rombelBinaan->nama_rombel ?? $rombelBinaan->nama_kelas }}</strong> | Periode: <strong>{{ $namaBulan }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NISN</th>
                <th>Nama Siswa</th>
                <th width="8%">Hadir</th>
                <th width="8%">Sakit</th>
                <th width="8%">Izin</th>
                <th width="8%">Alpa</th>
                <th width="12%">Kehadiran (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $siswa)
            @php
            $records = $presensiData[$siswa->id] ?? collect();
            $hadir = $records->filter(fn($r) => strtolower($r->status) === 'hadir')->count();
            $sakit = $records->filter(fn($r) => strtolower($r->status) === 'sakit')->count();
            $izin = $records->filter(fn($r) => strtolower($r->status) === 'izin')->count();
            $alpa = $records->filter(fn($r) => in_array(strtolower($r->status), ['alpa', 'alfa']))->count();
            $totalHari = $records->count();
            $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $siswa->nisn ?? '-' }}</td>
                <td>{{ $siswa->nama_lengkap ?? $siswa->nama }}</td>
                <td class="text-center">{{ $hadir }}</td>
                <td class="text-center">{{ $sakit }}</td>
                <td class="text-center">{{ $izin }}</td>
                <td class="text-center">{{ $alpa }}</td>
                <td class="text-center"><strong>{{ $persentase }}%</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="ttd-space"></div>
            <p><strong>____________________</strong></p>
        </div>
        <div class="ttd-box">
            <p>Wali Kelas,</p>
            <div class="ttd-space"></div>
            <p><strong>{{ $guru->nama_lengkap ?? 'Wali Kelas' }}</strong><br>NIP. {{ $guru->nip ?? '-' }}</p>
        </div>
    </div>

</body>

</html>