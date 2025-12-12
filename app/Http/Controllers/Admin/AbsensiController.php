<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Menampilkan Halaman Scanner
     */
    public function index()
    {
        return view('admin.absensi.scan');
    }

    /**
     * Mode khusus untuk penggunaan Gun Scanner (keyboard wedge).
     */
    public function modeGun()
    {
        return view('admin.absensi.gun');
    }

    /**
     * Memproses Data QR Code yang dikirim Scanner (AJAX)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'qrcode_token' => 'required|string'
        ]);

        // 2. Cari siswa berdasarkan token
        $siswa = Siswa::with('kelas')->where('qrcode_token', $request->qrcode_token)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak dikenali / Siswa tidak ditemukan.'
            ], 404);
        }

        // 3. Cek apakah sudah absen hari ini
        $tanggalHariIni = Carbon::today();
        
        $sudahAbsen = Kehadiran::where('siswa_id', $siswa->id)
                                ->where('tanggal', $tanggalHariIni)
                                ->first();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Siswa atas nama ' . $siswa->nama_siswa . ' sudah absen hari ini.',
                'siswa' => $siswa,
                'jam_masuk' => optional($sudahAbsen)->jam_masuk,
                'tanggal' => $tanggalHariIni->toDateString(),
            ]);
        }

        // 4. Simpan Kehadiran Baru
        $jamMasuk = Carbon::now();

        Kehadiran::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'Hadir', // Default Hadir jika scan
            'jam_masuk' => $jamMasuk->format('H:i:s'),
        ]);

        // 5. Kirim respon sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil! ' . $siswa->nama_siswa . ' telah hadir.',
            'siswa' => $siswa,
            'jam_masuk' => $jamMasuk->format('H:i:s'),
            'tanggal' => $tanggalHariIni->toDateString(),
        ]);
    }
}