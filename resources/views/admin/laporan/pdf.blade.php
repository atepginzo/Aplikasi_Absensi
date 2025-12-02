<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran {{ $kelas->nama_kelas }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0; /* Margin diatur oleh DomPDF */
        }
        .kop {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop p {
            margin: 2px 0;
            font-size: 12px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        .info-separator {
            width: 10px;
            text-align: center;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table thead th {
            background: #f1f5f9;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px;
            font-size: 11px;
            border: 1px solid #334155;
        }
        table.data-table tbody td {
            border: 1px solid #334155;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }
        
        /* CSS Tanda Tangan Diperbaiki */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }
        .ttd-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .ttd-box p {
            margin: 4px 0;
        }
        .ttd-space {
            height: 60px; /* Ruang untuk tanda tangan basah */
        }
        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        /* Clearfix untuk float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Laporan Rekap Kehadiran</h1>
        <p>Aplikasi Absensi Kehadiran Siswa</p>
        <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <hr style="border: 1px solid #000; margin-bottom: 20px;">

    <table class="info-table">
        <tr>
            <td class="info-label">Kelas</td>
            <td class="info-separator">:</td>
            <td>{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td class="info-label">Wali Kelas</td>
            <td class="info-separator">:</td>
            <td>{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tahun Ajaran</td>
            <td class="info-separator">:</td>
            <td>{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Total Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapAbsensi as $rekap)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($rekap->tanggal)->translatedFormat('d M Y') }}</td>
                    <td>{{ $rekap->jumlah_hadir }}</td>
                    <td>{{ $rekap->jumlah_sakit }}</td>
                    <td>{{ $rekap->jumlah_izin }}</td>
                    <td>{{ $rekap->jumlah_alpha }}</td>
                    <td>{{ $rekap->total_input }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 20px;">Belum ada data kehadiran untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container clearfix">
        <div class="ttd-box">
            {{-- Sesuaikan kota di sini --}}
            <p>Paseh, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Wali Kelas</p>
            
            <div class="ttd-space"></div>
            
            <p class="ttd-name">{{ $kelas->waliKelas->nama_lengkap ?? '................................' }}</p>
            <p>NIP. {{ $kelas->waliKelas->nip ?? '-' }}</p>
        </div>
    </div>
</body>
</html>