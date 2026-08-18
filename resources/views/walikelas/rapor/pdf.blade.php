<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Belajar (Rapor) - {{ $siswaList->first()?->nama_lengkap ?? 'Siswa' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #111;
            line-height: 1.3;
        }

        .page-break {
            page-break-after: always;
        }

        .header-kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .header-kop h2 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }

        .header-kop p {
            margin: 2px 0 0;
            font-size: 8.5pt;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9.5pt;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .grade-table,
        .sub-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .grade-table th,
        .grade-table td,
        .sub-table th,
        .sub-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 8.5pt;
        }

        .grade-table th,
        .sub-table th {
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

        .section-title {
            font-weight: bold;
            font-size: 9.5pt;
            margin-bottom: 4px;
        }

        .signature-table {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
            font-size: 9pt;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 33.33%;
        }

        .qr-box {
            margin: 4px 0;
        }
    </style>
</head>

<body>

    @foreach($siswaList as $siswa)
    <!-- Kop Sekolah -->
    <div class="header-kop">
        <h2>PEMERINTAH KOTA DKI JAKARTA<br>DINAS PENDIDIKAN<br>SMP NEGERI 110 JAKARTA</h2>
        <p>Jl. Pendidikan No. 45, Bandung | Telp: (022) 1234567 | Website: smpn1digital.sch.id</p>
    </div>

    <h3 class="text-center" style="margin-bottom: 10px; font-size: 11pt;">LAPORAN HASIL BELAJAR SISWA (RAPOR)</h3>

    <!-- Identitas Siswa -->
    <table class="meta-table">
        <tr>
            <td width="18%">Nama Siswa</td>
            <td width="2%">:</td>
            <td width="40%" class="text-bold">{{ $siswa->nama_lengkap }}</td>
            <td width="18%">Kelas / Rombel</td>
            <td width="2%">:</td>
            <td width="20%">{{ $rombel->nama_rombel ?? $siswa->rombel?->nama_rombel ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN / NIS</td>
            <td>:</td>
            <td>{{ $siswa->nisn ?? '-' }} / {{ $siswa->nis ?? '-' }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ ucfirst($semester ?? 'Ganjil') }}</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td>SMPN 1 Digital</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $rombel->tahunAjaran->tahun ?? '2025/2026' }}</td>
        </tr>
    </table>

    <!-- A. Capaian Pengetahuan -->
    <div class="section-title">A. Pencapaian Pengetahuan</div>
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
            @forelse($siswa->penilaian as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->mapel?->nama_mapel ?? '-' }}</td>
                <td class="text-center text-bold">{{ number_format($item->nilai_akhir ?? $item->nilai_pengetahuan ?? $item->nilai ?? 0, 0) }}</td>
                <td class="text-center text-bold">{{ $item->predikat ?? $item->predikat_pengetahuan ?? '-' }}</td>
                <td style="font-size: 8pt;">{{ $item->deskripsi ?? $item->deskripsi_pengetahuan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data nilai pengetahuan belum dimasukkan.</td>
            </tr>
            @endforelse
            <tr style="background-color: #F9F9F9;">
                <td colspan="2" class="text-bold text-center">RATA-RATA NILAI PENGETAHUAN</td>
                <td class="text-center text-bold">
                    {{ number_format($siswa->penilaian?->avg('nilai_akhir') ?? $siswa->penilaian?->avg('nilai') ?? 0, 2) }}
                </td>
                <td colspan="2" class="text-bold"></td>
            </tr>
        </tbody>
    </table>

    <!-- B. Capaian Keterampilan -->
    <div class="section-title">B. Pencapaian Keterampilan</div>
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
            @forelse($siswa->penilaian as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->mapel?->nama_mapel ?? '-' }}</td>
                <td class="text-center text-bold">
                    {{ number_format($item->nilai_keterampilan ?? $item->nilai_praktik ?? $item->nilai_akhir ?? 0, 0) }}
                </td>
                <td class="text-center text-bold">
                    {{ $item->predikat_keterampilan ?? $item->predikat ?? '-' }}
                </td>
                <td style="font-size: 8pt;">
                    {{ $item->deskripsi_keterampilan ?? $item->deskripsi ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data nilai keterampilan belum dimasukkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- C & D: Ekstrakurikuler & Presensi -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <!-- C. Ekstrakurikuler -->
            <td style="width: 58%; vertical-align: top; padding-right: 10px;">
                <div class="section-title">C. Ekstrakurikuler</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="45%">Kegiatan Ekstrakurikuler</th>
                            <th width="15%">Nilai</th>
                            <th width="30%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa->nilaiEkskul ?? [] as $idx => $ekskul)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>{{ $ekskul->ekstrakurikuler?->nama_ekskul ?? $ekskul->nama_ekskul ?? '-' }}</td>
                            <td class="text-center text-bold">
                                {{ $ekskul->nilai ?? $ekskul->predikat ?? $ekskul->nilai_ekskul ?? $ekskul->grade ?? '-' }}
                            </td>
                            <td>{{ $ekskul->keterangan ?? $ekskul->deskripsi ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data ekstrakurikuler.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <!-- D. Ketidakhadiran (Presensi) -->
            <td style="width: 40%; vertical-align: top;">
                <div class="section-title">D. Ketidakhadiran</div>
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>Alasan Ketidakhadiran</th>
                            <th width="35%">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sakit</td>
                            <td class="text-center">{{ $siswa->rekap_presensi?->sakit ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td>Izin</td>
                            <td class="text-center">{{ $siswa->rekap_presensi?->izin ?? 0 }} hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan (Alpa)</td>
                            <td class="text-center">{{ $siswa->rekap_presensi?->alpa ?? 0 }} hari</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Area Tanda Tangan -->
    <table class="signature-table">
        <!-- Baris Tanggal Cetak (Khusus Kolom Kanan) -->
        <tr>
            <td></td>
            <td></td>
            <td style="padding-bottom: 5px;">
                Bandung, {{ $tanggalCetak ?? now()->locale('id')->translatedFormat('d F Y') }}
            </td>
        </tr>
        <!-- Baris Jabatan & Tanda Tangan (Sejajar) -->
        <tr>
            <td>
                <p>Orang Tua / Wali Siswa,</p>
                <br><br><br>
                <p class="text-bold">( .................................... )</p>
            </td>
            <td>
                @if(isset($qrCodeImage) && $qrCodeImage)
                <p>Verifikasi Digital Signature:</p>
                <div class="qr-box">
                    <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Signature" width="75">
                </div>
                <p style="font-size: 7pt; color: #555;">Scan keaslian dokumen</p>
                @endif
            </td>
            <td>
                <p>Wali Kelas,</p>
                <br><br><br>
                <p class="text-bold"><u>{{ $rombel->waliKelas?->nama_lengkap ?? $siswa->rombel?->waliKelas?->nama_lengkap ?? '....................................' }}</u></p>
                <p style="font-size: 8pt;">NIP. {{ $rombel->waliKelas?->nip ?? $siswa->rombel?->waliKelas?->nip ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <!-- Page break antar siswa -->
    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

</body>

</html>