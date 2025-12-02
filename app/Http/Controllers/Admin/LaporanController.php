<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Tampilkan daftar kelas untuk dipilih.
     */
    public function index()
    {
        $daftarKelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('admin.laporan.index', [
            'daftarKelas' => $daftarKelas,
        ]);
    }

    /**
     * Tampilkan rekap kehadiran per tanggal untuk kelas tertentu.
     */
    public function show($kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->findOrFail($kelasId);

        $rekapKehadiran = Kehadiran::selectRaw(
            "tanggal,
            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS jumlah_sakit,
            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS jumlah_izin,
            SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS jumlah_alpha,
            COUNT(*) AS total_input"
        )
            ->join('siswas', 'siswas.id', '=', 'kehadirans.siswa_id')
            ->where('siswas.kelas_id', $kelas->id)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.laporan.show', [
            'kelas' => $kelas,
            'rekapKehadiran' => $rekapKehadiran,
        ]);
    }
    /**
     * Fungsi Baru: Export PDF
     */
    public function exportPdf($kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->findOrFail($kelasId);

        // 1. Ambil data rekap (menggunakan logika query yang sama)
        $rekapAbsensi = $this->getRekapData($kelas->id);

        // 2. Load view PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'kelas' => $kelas,
            'rekapAbsensi' => $rekapAbsensi,
            'tanggalCetak' => now()
        ]);

        // 3. Download file
        return $pdf->download('Laporan_Absensi_' . $kelas->nama_kelas . '.pdf');
    }

    /**
     * Fungsi Helper: Query Rekap Kehadiran (Logika dari script lama Anda)
     * Dipisahkan agar bisa dipakai di show() dan exportPdf()
     */
    private function getRekapData($kelasId)
    {
        // Saya tetap menggunakan logika JOIN sesuai script Anda
        return Kehadiran::selectRaw(
            "tanggal,
            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS jumlah_sakit,
            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS jumlah_izin,
            SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS jumlah_alpha,
            COUNT(*) AS total_input"
        )
            ->join('siswas', 'siswas.id', '=', 'kehadirans.siswa_id')
            ->where('siswas.kelas_id', $kelasId)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
