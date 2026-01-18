<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\WaliKelas;
use App\Traits\BuildsMonthlyAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TahunAjaran;

class LaporanController extends Controller
{
    use BuildsMonthlyAttendance;

    public function index(Request $request)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        // 1. Ambil semua tahun ajaran untuk dropdown
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // 2. Ambil tahun aktif
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // 3. Tentukan tahun yang dipilih (default ke tahun aktif)
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        // 4. Filter kelas berdasarkan wali_kelas_id DAN tahun ajaran
        $daftarKelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->when($tahunPilihanId, function($q) use ($tahunPilihanId) {
                $q->where('tahun_ajaran_id', $tahunPilihanId);
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('wali.laporan.index', [
            'daftarKelas' => $daftarKelas,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }

    public function show($kelasId)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->findOrFail($kelasId);

        $rekapKehadiran = $this->getRekapData($kelas->id);

        return view('wali.laporan.show', [
            'kelas' => $kelas,
            'rekapKehadiran' => $rekapKehadiran,
        ]);
    }

    public function detail($kelasId, $tanggal)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $kelas = Kelas::with([
            'waliKelas',
            'tahunAjaran',
            'siswas' => function ($query) {
                $query->orderBy('nama_siswa', 'asc');
            },
        ])
            ->where('wali_kelas_id', $wali->id)
            ->findOrFail($kelasId);

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

        return view('wali.laporan.detail', [
            'kelas' => $kelas,
            'tanggal' => $tanggalParsed->toDateString(),
            'tanggalLabel' => $tanggalParsed->translatedFormat('l, d F Y'),
            'kehadiranPadaTanggal' => $kehadiranPadaTanggal,
            'daftarSiswa' => $kelas->siswas,
        ]);
    }

    public function exportPdf($kelasId)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->findOrFail($kelasId);

        $rekapAbsensi = $this->getRekapData($kelas->id);

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'kelas' => $kelas,
            'rekapAbsensi' => $rekapAbsensi,
            'tanggalCetak' => now(),
        ]);

        return $pdf->download('Laporan_Absensi_' . $kelas->nama_kelas . '.pdf');
    }

    public function bulanan($kelasId, Request $request)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->findOrFail($kelasId);

        $bulanInput = $request->input('bulan') ?: now()->format('Y-m');
        $periode = $this->normalizeMonth($bulanInput) ?? now()->startOfDay();
        $rekapBulanan = $this->buildMonthlyAttendance($kelas->id, $periode);

        return view('wali.laporan.bulanan', [
            'kelas' => $kelas,
            'rekapBulanan' => $rekapBulanan,
            'bulanDipilih' => $periode->format('Y-m'),
            'periodeLabel' => $periode->translatedFormat('F Y'),
        ]);
    }

    public function exportBulananPdf($kelasId, Request $request)
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->findOrFail($kelasId);

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

    private function getRekapData($kelasId)
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
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
