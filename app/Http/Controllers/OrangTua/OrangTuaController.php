<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
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

        $siswa = $user->siswa()->with('kelas')->first();

        if (! $siswa) {
            return view('orang_tua.dashboard', [
                'user' => $user,
                'siswa' => null,
                'kelas' => null,
                'kehadiranHariIni' => collect(),
                'pesanError' => 'Akun orang tua Anda belum terhubung dengan data siswa. Silakan hubungi admin atau wali kelas untuk menghubungkan akun ini.',
            ]);
        }

        $kelas = $siswa->kelas;

        // Riwayat kehadiran anak
        $riwayatKehadiran = Kehadiran::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->limit(30)
            ->get();

        // Statistik sederhana berdasarkan seluruh riwayat
        $totalSemua = Kehadiran::where('siswa_id', $siswa->id)->count();
        $totalHadir = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
        $totalSakit = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Sakit')->count();
        $totalIzin = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Izin')->count();
        $totalAlpha = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Alpha')->count();

        return view('orang_tua.dashboard', [
            'user' => $user,
            'siswa' => $siswa,
            'kelas' => $kelas,
            'riwayatKehadiran' => $riwayatKehadiran,
            'totalSemua' => $totalSemua,
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'totalAlpha' => $totalAlpha,
            'pesanError' => null,
        ]);
    }
}
