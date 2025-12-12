<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\WaliKelas;
use App\Models\Kehadiran;
use App\Models\User; // <-- Pastikan model User di-import
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Gunakan Facade Auth

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */ // <-- INI SOLUSINYA (Type Hinting)
        // Ini memberitahu editor bahwa $user adalah objek dari App\Models\User
        $user = Auth::user(); 
        
        // Cek null safety (jika belum login, meski middleware auth harusnya sudah menangani ini)
        if (!$user) {
            return redirect()->route('login');
        }

        $isAdmin = $user->role === 'admin';

        $jumlahSiswa = $jumlahKelas = $jumlahWaliKelas = 0;
        if ($isAdmin) {
            $jumlahSiswa = Siswa::count();
            $jumlahKelas = Kelas::count();
            $jumlahWaliKelas = WaliKelas::count();
        }

        $wali = null;
        if ($user->role === 'wali_kelas') {
            $wali = WaliKelas::where('user_id', $user->id)->first();
        }

        $hariIni = Carbon::today();
        
        // Query kehadiran hari ini
        $hadirHariIniQuery = Kehadiran::whereDate('tanggal', $hariIni)
            ->where('status', 'Hadir');

        // Jika Wali Kelas, filter kehadiran hanya untuk kelas yang diampu
        if ($wali && !$isAdmin) {
            $hadirHariIniQuery->whereHas('siswa.kelas', function ($kelasQuery) use ($wali) {
                $kelasQuery->where('wali_kelas_id', $wali->id);
            });
        }

        $hadirHariIni = $hadirHariIniQuery->count();

        $kelasDiampu = collect();
        $jumlahKelasDiampu = 0;
        $totalSiswaDiampu = 0;

        if ($wali && !$isAdmin) {
            $kelasDiampu = Kelas::withCount('siswas as siswa_count')
                ->where('wali_kelas_id', $wali->id)
                ->orderBy('nama_kelas', 'asc')
                ->get();
            $jumlahKelasDiampu = $kelasDiampu->count();
            $totalSiswaDiampu = $kelasDiampu->sum('siswa_count');
        }
        
        return view('dashboard', [
            'jumlahSiswa' => $jumlahSiswa,
            'jumlahKelas' => $jumlahKelas,
            'jumlahWaliKelas' => $jumlahWaliKelas,
            'hadirHariIni' => $hadirHariIni,
            'isAdmin' => $isAdmin,
            'kelasDiampu' => $kelasDiampu,
            'jumlahKelasDiampu' => $jumlahKelasDiampu,
            'totalSiswaDiampu' => $totalSiswaDiampu,
        ]);
    }
}