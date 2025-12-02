<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $wali = Auth::user()?->waliKelas;

        if (! $wali) {
            abort(403, 'Akun Anda belum terhubung dengan data wali kelas.');
        }

        $daftarKelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->where('wali_kelas_id', $wali->id)
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('wali.laporan.index', [
            'daftarKelas' => $daftarKelas,
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

        $rekapKehadiran = Kehadiran::selectRaw(
            "tanggal,
            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) AS jumlah_sakit,
            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) AS jumlah_izin,
            SUM(CASE WHEN status = 'Alpha' THEN 1 ELSE 0 END) AS jumlah_alpha,
            COUNT(*) AS total_input"
        )
            ->join('siswas', 'siswas.id', '=', 'kehadirans.siswa_id')
            ->where('siswas.kelas_id', $kelas->id)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('wali.laporan.show', [
            'kelas' => $kelas,
            'rekapKehadiran' => $rekapKehadiran,
        ]);
    }
}
