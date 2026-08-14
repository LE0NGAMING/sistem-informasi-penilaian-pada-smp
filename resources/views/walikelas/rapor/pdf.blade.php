<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Belajar (Rapor) - {{ $siswa->nama_lengkap }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #111;
            line-height: 1.3;
        }

        .header-kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header-kop h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .header-kop p {
            margin: 2px 0 0;
            font-size: 9pt;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .grade-table th,
        .grade-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
        }

        .grade-table th {
            background-color: #F0F0F0;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .footer-signature {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 33%;
            float: left;
            text-align: center;
            font-size: 9.5pt;
        }

        .qr-box {
            margin-top: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <!-- Kop Sekolah -->
    <div class="header-kop">
        <h2>PEMERINTAH KOTA BANDUNG<br>DINAS PENDIDIKAN<br>SMP NEGERI 1 DIGITAL</h2>
        <p>Jl. Pendidikan No. 45, Bandung | Telp: (022) 1234567 | Website: smpn1digital.sch.id</p>
    </div>

    <h3 class="text-center" style="margin-bottom: 15px; font-size: 12pt;">LAPORAN HASIL BELAJAR SISWA (RAPOR)</h3>

    <!-- Identitas Siswa -->
    <table class="meta-table">
        <tr>
            <td width="18%">Nama Siswa</td>
            <td width="2%">:</td>
            <td width="40%" class="text-bold">{{ $siswa->nama_lengkap }}</td>
            <td width="18%">Kelas / Rombel</td>
            <td width="2%">:</td>
            <td width="20%">{{ $siswa->rombel->nama_rombel ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN / NIS</td>
            <td>:</td>
            <td>{{ $siswa->nisn }} / {{ $siswa->nis }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>1 (Ganjil)</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td>SMPN 1 Digital</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>2025/2026</td>
        </tr>
    </table>

    <!-- Tabel Nilai Capaian Akademik -->
    <table class="grade-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="10%">Nilai Akhir</th>
                <th width="10%">Predikat</th>
                <th width="40%">Capaian Kompetensi / Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penilaian as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->mapel->nama_mapel }}</td>
                <td class="text-center text-bold">{{ number_format($item->nilai_akhir, 0) }}</td>
                <td class="text-center text-bold">{{ $item->predikat }}</td>
                <td style="font-size: 8.5pt;">{{ $item->deskripsi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data nilai belum dimasukkan.</td>
            </tr>
            @endforelse
            <tr style="background-color: #F9F9F9;">
                <td colspan="2" class="text-bold text-center">RATA-RATA NILAI KESELURUHAN</td>
                <td class="text-center text-bold">{{ number_format($rapor->rata_rata ?? 0, 2) }}</td>
                <td colspan="2" class="text-bold">Peringkat Kelas: {{ $rapor->ranking ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Area Tanda Tangan & QR Code Signature -->
    <div class="footer-signature">
        <div class="signature-box">
            <p>Orang Tua / Wali Siswa,</p>
            <br><br><br>
            <p class="text-bold">( .................................... )</p>
        </div>

        <div class="signature-box">
            <p>Verifikasi Digital Signature:</p>
            <div class="qr-box">
                <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Signature" width="90">
            </div>
            <p style="font-size: 7.5pt; color: #555;">Scan untuk mengecek keaslian dokumen</p>
        </div>

        <div class="signature-box">
            <p>Bandung, 20 Desember 2026<br>Wali Kelas,</p>
            <br><br><br>
            <p class="text-bold"><u>NIP. 19850315 201001 1 002</u></p>
        </div>
    </div>

</body>

</html>