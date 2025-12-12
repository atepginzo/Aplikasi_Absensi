<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $rekapKehadiran = $this->getRekapData($kelas->id);

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
    private function getRekapData($kelasId, $tanggalMulai = null, $tanggalSelesai = null)
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
            ->when($tanggalMulai, function ($query) use ($tanggalMulai) {
                $query->whereDate('kehadirans.tanggal', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($query) use ($tanggalSelesai) {
                $query->whereDate('kehadirans.tanggal', '<=', $tanggalSelesai);
            })
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    /**
     * Rekap bulanan per siswa berdasarkan kelas.
     */
    public function bulanan(Request $request)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $kelasDipilih = null;
        $rekapBulanan = collect();
        $bulanDipilih = $request->input('bulan');
        $kelasId = $request->input('kelas_id');
        $periodeLabel = null;

        if ($bulanDipilih && $kelasId) {
            $kelasDipilih = Kelas::with(['waliKelas', 'tahunAjaran'])->findOrFail($kelasId);

            try {
                $periode = Carbon::createFromFormat('Y-m', $bulanDipilih);
                $awal = $periode->copy()->startOfMonth()->toDateString();
                $akhir = $periode->copy()->endOfMonth()->toDateString();
                $periodeLabel = $periode->translatedFormat('F Y');

                $rekapBulanan = Kehadiran::selectRaw(
                    "siswas.id as siswa_id,
                    siswas.nama_siswa,
                    SUM(CASE WHEN kehadirans.status = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
                    SUM(CASE WHEN kehadirans.status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
                    SUM(CASE WHEN kehadirans.status = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
                    SUM(CASE WHEN kehadirans.status = 'Alpha' THEN 1 ELSE 0 END) AS total_alpha"
                )
                    ->join('siswas', 'siswas.id', '=', 'kehadirans.siswa_id')
                    ->where('siswas.kelas_id', $kelasDipilih->id)
                    ->whereBetween('kehadirans.tanggal', [$awal, $akhir])
                    ->groupBy('siswas.id', 'siswas.nama_siswa')
                    ->orderBy('siswas.nama_siswa', 'asc')
                    ->get();
            } catch (\Exception $e) {
                $rekapBulanan = collect();
                $periodeLabel = null;
            }
        }

        return view('admin.laporan.bulanan', [
            'daftarKelas' => $daftarKelas,
            'kelasDipilih' => $kelasDipilih,
            'rekapBulanan' => $rekapBulanan,
            'bulanDipilih' => $bulanDipilih,
            'periodeLabel' => $periodeLabel,
            'kelasIdDipilih' => $kelasId,
        ]);
    }

    /**
     * Tampilkan detail kehadiran per siswa pada tanggal tertentu.
     */
    public function detail($kelasId, $tanggal)
    {
        $kelas = Kelas::with([
            'waliKelas',
            'tahunAjaran',
            'siswas' => function ($query) {
                $query->orderBy('nama_siswa', 'asc');
            },
        ])->findOrFail($kelasId);

        try {
            $tanggalParsed = Carbon::createFromFormat('Y-m-d', $tanggal)->startOfDay();
        } catch (\Exception $e) {
            abort(404);
        }

        $siswaIds = $kelas->siswas->pluck('id');
        $kehadiranPadaTanggal = Kehadiran::whereDate('tanggal', $tanggalParsed->toDateString())
            ->whereIn('siswa_id', $siswaIds)
            ->get()
            ->keyBy('siswa_id');

        return view('admin.laporan.detail', [
            'kelas' => $kelas,
            'tanggal' => $tanggalParsed->toDateString(),
            'tanggalLabel' => $tanggalParsed->translatedFormat('l, d F Y'),
            'kehadiranPadaTanggal' => $kehadiranPadaTanggal,
            'daftarSiswa' => $kelas->siswas,
        ]);
    }
}
