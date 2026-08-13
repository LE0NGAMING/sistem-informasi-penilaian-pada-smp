<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Siswa</title>
</head>

<body>
    <h3>REKAPITULASI PRESENSI SISWA</h3>
    <p>
        <strong>Kelas:</strong> {{ $rombelBinaan->nama_rombel ?? $rombelBinaan->nama_kelas }}<br>
        <strong>Periode:</strong> {{ $namaBulan }}
    </p>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Hadir (H)</th>
                <th>Sakit (S)</th>
                <th>Izin (I)</th>
                <th>Alpa (A)</th>
                <th>Total Kehadiran (%)</th>
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
            <tr>`
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">'{{ $siswa->nisn ?? '-' }}</td>
                <td>{{ $siswa->nama_lengkap ?? $siswa->nama }}</td>
                <td align="center">{{ $hadir }}</td>
                <td align="center">{{ $sakit }}</td>
                <td align="center">{{ $izin }}</td>
                <td align="center">{{ $alpa }}</td>
                <td align="center">{{ $persentase }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>