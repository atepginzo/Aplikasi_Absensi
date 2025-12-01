<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas; // <-- Tambahkan model Kelas
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- Tambahkan helper Str untuk UUID

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semuaSiswa = Siswa::with(['kelas.waliKelas'])
                                ->orderBy('nama_siswa', 'asc')
                                ->get();
                                
        return view('admin.siswa.index', [
            'semuaSiswa' => $semuaSiswa
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data kelas untuk dropdown
        // Sertakan 'tahunAjaran' agar kita bisa menampilkannya di dropdown (Contoh: "Kelas 7A - 2024/2025")
        $semuaKelas = Kelas::with('tahunAjaran')->orderBy('nama_kelas', 'asc')->get();

        return view('admin.siswa.create', [
            'semuaKelas' => $semuaKelas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // 2. Simpan Data
        // Kita generate UUID untuk qrcode_token di sini
        Siswa::create([
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'qrcode_token' => (string) Str::uuid(), // <-- Magic happens here!
        ]);

        // 3. Redirect
        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        // Kita load relasi kelas, tahun ajaran, dan wali kelas
        // agar informasinya lengkap saat ditampilkan di halaman detail
        $siswa->load(['kelas.tahunAjaran', 'kelas.waliKelas']);

        return view('admin.siswa.show', [
            'siswa' => $siswa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $semuaKelas = Kelas::with('tahunAjaran')->orderBy('nama_kelas', 'asc')->get();

        // 2. Tampilkan view 'edit' dan kirim data $kela (kelas yg diedit) + data dropdown
        return view('admin.siswa.edit', [
            'siswa' => $siswa, // Data kelas yang akan diedit
            'semuaKelas' => $semuaKelas
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        // 1. Validasi input
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            // Validasi unik untuk NIS, TAPI abaikan NIS milik siswa ini sendiri ($siswa->id)
            'nis' => 'required|string|max:20|unique:siswas,nis,'.$siswa->id,
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        // 2. Update data
        // Kita TIDAK mengupdate 'qrcode_token' agar QR code lama tetap valid
        $siswa->update([
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        // 3. Redirect kembali ke index
        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Siswa berhasil dihapus.');
    }
}