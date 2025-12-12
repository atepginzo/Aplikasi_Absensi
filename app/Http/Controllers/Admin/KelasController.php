<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
// Impor model-model yang kita butuhkan untuk relasi
use App\Models\TahunAjaran;
use App\Models\WaliKelas;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan halaman tabel (Read)
     */
    public function index()
    {
        // Ambil data kelas, DAN data relasinya (tahunAjaran, waliKelas)
        // 'with()' -> Eager Loading, ini adalah cara yang efisien
        //            untuk menghindari N+1 problem.
        $semuaKelas = Kelas::with(['tahunAjaran', 'waliKelas'])
                    ->orderBy('nama_kelas', 'asc')
                    ->paginate(10);

        // Tampilkan view dan kirim data 'semuaKelas'
        return view('admin.kelas.index', [
            'semuaKelas' => $semuaKelas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan halaman form Tambah (Create)
     */
    public function create()
    {
        // 1. Ambil semua data Tahun Ajaran (hanya yang aktif)
        $semuaTahunAjaran = TahunAjaran::where('is_active', true)->orderBy('tahun_ajaran', 'desc')->get();
        
        // 2. Ambil semua data Wali Kelas
        $semuaWaliKelas = WaliKelas::orderBy('nama_lengkap', 'asc')->get();

        // 3. Tampilkan view 'create' dan kirim data untuk dropdown
        return view('admin.kelas.create', [
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'semuaWaliKelas' => $semuaWaliKelas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data baru dari form Tambah (Create)
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id', // Pastikan ID-nya ada di tabel tahun_ajarans
            'wali_kelas_id' => 'required|exists:wali_kelas,id', // Pastikan ID-nya ada di tabel wali_kelas
        ], [
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'wali_kelas_id.required' => 'Wali Kelas wajib dipilih.',
        ]);

        // 2. Simpan data baru
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        // 3. Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela)
    {
        $kela->load(['waliKelas', 'tahunAjaran', 'siswas' => function ($query) {
            $query->orderBy('nama_siswa');
        }]);

        return view('admin.kelas.show', [
            'kelas' => $kela,
            'daftarSiswa' => $kela->siswas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan halaman form Edit (Update)
     */
    public function edit(Kelas $kela)
    {
        // 1. Ambil data untuk dropdown (sama seperti 'create')
        $semuaTahunAjaran = TahunAjaran::where('is_active', true)->orderBy('tahun_ajaran', 'desc')->get();
        $semuaWaliKelas = WaliKelas::orderBy('nama_lengkap', 'asc')->get();

        // 2. Tampilkan view 'edit' dan kirim data $kela (kelas yg diedit) + data dropdown
        return view('admin.kelas.edit', [
            'kela' => $kela, // Data kelas yang akan diedit
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'semuaWaliKelas' => $semuaWaliKelas
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Menyimpan perubahan data dari form Edit (Update)
     */
    public function update(Request $request, Kelas $kela)
    {
        // 1. Validasi data (sama seperti 'store')
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'wali_kelas_id' => 'required|exists:wali_kelas,id',
        ], [
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'wali_kelas_id.required' => 'Wali Kelas wajib dipilih.',
        ]);

        // 2. Update data di database
        $kela->update([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        // 3. Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data (Delete)
     */
    public function destroy(Kelas $kela)
    {
        // 1. Hapus data 'Kelas' dari database
        $kela->delete();

        // 2. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas berhasil dihapus.');
    }
}