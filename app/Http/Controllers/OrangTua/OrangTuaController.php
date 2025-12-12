<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::with('kelas')
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

        if (! $siswa) {
            $pesanError = 'Akun orang tua Anda belum terhubung dengan data siswa. Silakan hubungi admin.';
        } elseif (! $kelas) {
            $pesanError = 'Kelas untuk siswa Anda belum tersedia. Mohon hubungi wali kelas.';
        } else {
            $riwayatKehadiran = Kehadiran::where('siswa_id', $siswa->id)
                ->orderByDesc('tanggal')
                ->limit(30)
                ->get();

            $rekap = Kehadiran::selectRaw(
                "SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
                SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
                SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
                SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS total_alpha,
                COUNT(*) AS total_semua"
            )
                ->where('siswa_id', $siswa->id)
                ->first();

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
        ]);
    }
}