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
            'qrcode_token' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $now = Carbon::now();

        // ========================================
        // VALIDASI KEAMANAN: Hari Libur (Weekend)
        // ========================================
        if (config('absensi.validasi.hari_libur', true)) {
            if ($now->isSunday()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, Absensi tidak dapat dilakukan di Hari Minggu (Libur).'
                ], 403);
            }
        }

        // ========================================
        // VALIDASI KEAMANAN: Jam Operasional
        // ========================================
        if (config('absensi.validasi.jam_operasional', true)) {
            $jamBuka = config('absensi.jam_operasional.buka', '06:00');
            $jamTutup = config('absensi.jam_operasional.tutup', '17:00');
            $jamSekarang = $now->format('H:i');

            if ($jamSekarang < $jamBuka) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Belum waktunya absen. Jam absensi dibuka pukul {$jamBuka}."
                ], 403);
            }

            if ($jamSekarang > $jamTutup) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Sesi absensi hari ini sudah ditutup. Jam absensi tutup pukul {$jamTutup}."
                ], 403);
            }
        }

        // ========================================
        // VALIDASI KEAMANAN: Geofencing (Lokasi)
        // ========================================
        // Hanya validasi jika request membawa koordinat (dari Camera Scanner)
        // Gun Scanner tidak perlu validasi lokasi karena alatnya ada di sekolah
        if (config('absensi.validasi.geofencing', true)) {
            if ($request->filled('latitude') && $request->filled('longitude')) {
                $userLat = (float) $request->latitude;
                $userLng = (float) $request->longitude;
                
                $schoolLat = config('absensi.school.latitude');
                $schoolLng = config('absensi.school.longitude');
                $allowedRadius = config('absensi.school.radius', 200);

                $distance = $this->calculateHaversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

                if ($distance > $allowedRadius) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Anda berada di luar jangkauan sekolah (" . round($distance) . " meter dari lokasi). Maksimal jarak: {$allowedRadius} meter."
                    ], 403);
                }
            }
        }

        // ========================================
        // PROSES ABSENSI NORMAL
        // ========================================

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

    /**
     * Menghitung jarak antara dua titik koordinat menggunakan Haversine Formula.
     * 
     * @param float $lat1 Latitude titik 1
     * @param float $lng1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lng2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    private function calculateHaversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Radius bumi dalam meter
        $earthRadius = 6371000;

        // Konversi derajat ke radian
        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLng = deg2rad($lng2 - $lng1);

        // Haversine formula
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLng / 2) * sin($deltaLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Jarak dalam meter
        return $earthRadius * $c;
    }
}