<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::with('kelas.tahunAjaran')
            ->where(function ($query) use ($user) {
                $query->where('parent_user_id', $user->id)
                      ->orWhere('user_id', $user->id);
            })
            ->first();

        $kelas = $siswa?->kelas;
        $pesanError = null;
        $riwayatKehadiran = collect();
        $kehadiranKelasHariIni = collect();
        $totalSemua = $totalHadir = $totalSakit = $totalIzin = $totalAlpha = 0;

        // Ambil semua tahun ajaran untuk dropdown
        $semuaTahun = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Tentukan tahun yang dipilih (default: tahun aktif)
        $tahunDipilihId = $request->input('tahun_ajaran_id', $tahunAktif?->id);
        $tahunDipilih = TahunAjaran::find($tahunDipilihId) ?? $tahunAktif;

        // Parse tanggal dari tahun ajaran (misal "2024/2025" -> 1 Juli 2024 s/d 30 Juni 2025)
        $startDate = null;
        $endDate = null;
        if ($tahunDipilih && $tahunDipilih->tahun_ajaran) {
            $parts = explode('/', $tahunDipilih->tahun_ajaran);
            if (count($parts) === 2) {
                $yearStart = (int) trim($parts[0]);
                $yearEnd = (int) trim($parts[1]);
                $startDate = Carbon::create($yearStart, 7, 1)->startOfDay(); // 1 Juli
                $endDate = Carbon::create($yearEnd, 6, 30)->endOfDay(); // 30 Juni
            }
        }

        if (! $siswa) {
            $pesanError = 'Akun orang tua Anda belum terhubung dengan data siswa. Silakan hubungi admin.';
        } elseif (! $kelas) {
            $pesanError = 'Kelas untuk siswa Anda belum tersedia. Mohon hubungi wali kelas.';
        } else {
            // Query riwayat dengan filter tanggal tahun ajaran
            $riwayatQuery = Kehadiran::where('siswa_id', $siswa->id)
                ->orderByDesc('tanggal');

            if ($startDate && $endDate) {
                $riwayatQuery->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $riwayatKehadiran = $riwayatQuery->limit(30)->get();

            // Query rekap statistik dengan filter tanggal tahun ajaran
            $rekapQuery = Kehadiran::selectRaw(
                "SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
                SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
                SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
                SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS total_alpha,
                COUNT(*) AS total_semua"
            )
                ->where('siswa_id', $siswa->id);

            if ($startDate && $endDate) {
                $rekapQuery->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $rekap = $rekapQuery->first();

            $totalHadir = (int) ($rekap->total_hadir ?? 0);
            $totalSakit = (int) ($rekap->total_sakit ?? 0);
            $totalIzin = (int) ($rekap->total_izin ?? 0);
            $totalAlpha = (int) ($rekap->total_alpha ?? 0);
            $totalSemua = (int) ($rekap->total_semua ?? 0);

            $kehadiranKelasHariIni = Kehadiran::with(['siswa' => function ($query) {
                $query->select('id', 'nama_siswa', 'kelas_id');
            }])
                ->whereDate('tanggal', Carbon::today())
                ->whereHas('siswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })
                ->orderBy('status')
                ->get();
        }

        return view('orang_tua.dashboard', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'riwayatKehadiran' => $riwayatKehadiran,
            'totalSemua' => $totalSemua,
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlpha' => $totalAlpha,
            'pesanError' => $pesanError,
            'kehadiranKelasHariIni' => $kehadiranKelasHariIni,
            'semuaTahun' => $semuaTahun,
            'tahunDipilih' => $tahunDipilih,
            'tahunAktif' => $tahunAktif,
        ]);
    }
}
