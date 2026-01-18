<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        // 1. Ambil semua tahun ajaran untuk dropdown
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // 2. Ambil tahun aktif
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // 3. Tentukan tahun yang dipilih (default ke tahun aktif)
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        $hariIni = Carbon::today();

        // 4. Filter kelas berdasarkan wali_kelas_id DAN tahun ajaran
        $kelasDiampu = Kelas::withCount('siswas as siswa_count')
            ->where('wali_kelas_id', $wali->id)
            ->when($tahunPilihanId, function($q) use ($tahunPilihanId) {
                $q->where('tahun_ajaran_id', $tahunPilihanId);
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $hadirHariIni = Kehadiran::whereDate('tanggal', $hariIni)
            ->where('status', 'Hadir')
            ->whereHas('siswa.kelas', function ($kelasQuery) use ($wali, $tahunPilihanId) {
                $kelasQuery->where('wali_kelas_id', $wali->id);
                if ($tahunPilihanId) {
                    $kelasQuery->where('tahun_ajaran_id', $tahunPilihanId);
                }
            })
            ->count();

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
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }
}

