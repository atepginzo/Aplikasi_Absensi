<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KenaikanKelasController extends Controller
{
    /**
     * Menampilkan halaman Kenaikan Kelas dengan filter dan daftar siswa.
     */
    public function index(Request $request)
    {
        // Ambil semua kelas untuk dropdown
        $semuaKelas = Kelas::with('tahunAjaran')
            ->orderBy('nama_kelas')
            ->get();

        $kelasAsalId = $request->get('kelas_asal_id');
        $daftarSiswa = collect();

        // Jika kelas asal dipilih, ambil daftar siswa di kelas tersebut
        if ($kelasAsalId) {
            $daftarSiswa = Siswa::where('kelas_id', $kelasAsalId)
                ->orderBy('nama_siswa')
                ->get();
        }

        return view('admin.kenaikan_kelas.index', [
            'semuaKelas' => $semuaKelas,
            'kelasAsalId' => $kelasAsalId,
            'daftarSiswa' => $daftarSiswa,
        ]);
    }

    /**
     * Memproses kenaikan kelas massal (update kelas_id siswa yang dipilih).
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswas,id',
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ], [
            'siswa_ids.required' => 'Pilih minimal satu siswa untuk dipindahkan.',
            'siswa_ids.min' => 'Pilih minimal satu siswa untuk dipindahkan.',
            'kelas_tujuan_id.required' => 'Pilih kelas tujuan.',
            'kelas_tujuan_id.exists' => 'Kelas tujuan tidak valid.',
        ]);

        // Update kelas_id secara massal (TIDAK mengubah qrcode_token)
        Siswa::whereIn('id', $validated['siswa_ids'])
            ->update(['kelas_id' => $validated['kelas_tujuan_id']]);

        // Ambil nama kelas tujuan untuk pesan
        $kelasTujuan = Kelas::find($validated['kelas_tujuan_id']);
        $jumlahSiswa = count($validated['siswa_ids']);

        return redirect()
            ->route('admin.kenaikan-kelas.index')
            ->with('success', "Berhasil memindahkan {$jumlahSiswa} siswa ke kelas {$kelasTujuan->nama_kelas}.");
    }
}
