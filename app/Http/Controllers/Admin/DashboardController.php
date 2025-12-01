<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\WaliKelas;
use App\Models\Kehadiran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Data Master
        $jumlahSiswa = Siswa::count();
        $jumlahKelas = Kelas::count();
        $jumlahWaliKelas = WaliKelas::count();

        // 2. Hitung Kehadiran HARI INI
        $hariIni = Carbon::today();
        $hadirHariIni = Kehadiran::where('tanggal', $hariIni)->where('status', 'Hadir')->count();
        
        // Kirim semua data ke view dashboard
        return view('dashboard', [
            'jumlahSiswa' => $jumlahSiswa,
            'jumlahKelas' => $jumlahKelas,
            'jumlahWaliKelas' => $jumlahWaliKelas,
            'hadirHariIni' => $hadirHariIni
        ]);
    }
}