<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\TahunAjaran;
use App\Traits\BuildsMonthlyAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    use BuildsMonthlyAttendance;

    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        $query = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->orderBy('nama_kelas', 'asc');

        if ($tahunPilihanId) {
            $query->where('tahun_ajaran_id', $tahunPilihanId);
        }

        $daftarKelas = $query->get();
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.laporan.index', [
            'daftarKelas' => $daftarKelas,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }

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

    public function exportPdf($kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->findOrFail($kelasId);

        $rekapAbsensi = $this->getRekapData($kelas->id);

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'kelas' => $kelas,
            'rekapAbsensi' => $rekapAbsensi,
            'tanggalCetak' => now()
        ]);

        return $pdf->download('Laporan_Absensi_' . $kelas->nama_kelas . '.pdf');
    }

    private function getRekapData($kelasId, $tanggalMulai = null, $tanggalSelesai = null)
    {
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
            $periode = $this->normalizeMonth($bulanDipilih);

            if ($periode) {
                $rekapBulanan = $this->buildMonthlyAttendance($kelasDipilih->id, $periode);
                $periodeLabel = $periode->translatedFormat('F Y');
                $bulanDipilih = $periode->format('Y-m');
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

    public function bulananKelas(Request $request, $kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])->findOrFail($kelasId);
        $bulanInput = $request->input('bulan') ?: now()->format('Y-m');
        $periode = $this->normalizeMonth($bulanInput) ?? now()->startOfDay();

        $rekapBulanan = $this->buildMonthlyAttendance($kelas->id, $periode);

        return view('admin.laporan.bulanan-kelas', [
            'kelas' => $kelas,
            'rekapBulanan' => $rekapBulanan,
            'bulanDipilih' => $periode->format('Y-m'),
            'periodeLabel' => $periode->translatedFormat('F Y'),
        ]);
    }

    public function exportBulananPdf(Request $request, $kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])->findOrFail($kelasId);
        $bulanInput = $request->input('bulan') ?: now()->format('Y-m');
        $periode = $this->normalizeMonth($bulanInput) ?? now()->startOfDay();

        $rekapBulanan = $this->buildMonthlyAttendance($kelas->id, $periode);

        $pdf = Pdf::loadView('admin.laporan.pdf_bulanan', [
            'kelas' => $kelas,
            'rekapBulanan' => $rekapBulanan,
            'periodeLabel' => $periode->translatedFormat('F Y'),
            'tanggalCetak' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Rekap_Bulanan_' . $kelas->nama_kelas . '_' . $periode->format('Y_m') . '.pdf');
    }

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
