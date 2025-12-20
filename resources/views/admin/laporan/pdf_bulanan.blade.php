<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #0f172a; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .meta { margin-bottom: 20px; }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta td { padding: 6px 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5f5; padding: 8px; text-align: center; }
        th { background: #e2e8f0; font-size: 11px; text-transform: uppercase; }
        td:first-child, th:first-child { text-align: left; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Kehadiran Bulanan</h2>
        <p>PKBM RIDHO • Sistem Absensi</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>{{ $kelas->nama_kelas }}</td>
                <td><strong>Periode</strong></td>
                <td>{{ $periodeLabel }}</td>
            </tr>
            <tr>
                <td><strong>Wali Kelas</strong></td>
                <td>{{ $kelas->waliKelas->nama_lengkap ?? '-' }}</td>
                <td><strong>Tahun Ajaran</strong></td>
                <td>{{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>H</th>
                <th>S</th>
                <th>I</th>
                <th>A</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapBulanan as $index => $rekap)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $rekap->nama_siswa }}</td>
                    <td>{{ $rekap->total_hadir }}</td>
                    <td>{{ $rekap->total_sakit }}</td>
                    <td>{{ $rekap->total_izin }}</td>
                    <td>{{ $rekap->total_alpha }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data kehadiran pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::parse($tanggalCetak)->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>
