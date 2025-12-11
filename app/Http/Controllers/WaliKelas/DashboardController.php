<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $wali = WaliKelas::where('user_id', $user->id)->first();

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $isAdmin = false;

        $hariIni = Carbon::today();

        $hadirHariIni = Kehadiran::whereDate('tanggal', $hariIni)
            ->where('status', 'Hadir')
            ->whereHas('siswa.kelas', function ($kelasQuery) use ($wali) {
                $kelasQuery->where('wali_kelas_id', $wali->id);
            })
            ->count();

        $kelasDiampu = Kelas::withCount('siswa as siswa_count')
            ->where('wali_kelas_id', $wali->id)
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $jumlahKelasDiampu = $kelasDiampu->count();
        $totalSiswaDiampu = $kelasDiampu->sum('siswa_count');

        return view('dashboard', [
            'jumlahSiswa' => 0,
            'jumlahKelas' => 0,
            'jumlahWaliKelas' => 0,
            'hadirHariIni' => $hadirHariIni,
            'isAdmin' => $isAdmin,
            'kelasDiampu' => $kelasDiampu,
            'jumlahKelasDiampu' => $jumlahKelasDiampu,
            'totalSiswaDiampu' => $totalSiswaDiampu,
        ]);
    }
}
