<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data tahun ajaran dari database
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // Kirim data ke view
        return view('admin.tahun_ajaran.index', [
            'semuaTahunAjaran' => $semuaTahunAjaran
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $request->validate([
            // 'tahun_ajaran' harus diisi, harus unik (tidak boleh sama) di tabel 'tahun_ajarans'
            'tahun_ajaran' => 'required|string|max:10|unique:tahun_ajarans,tahun_ajaran',
        ], [
            // Pesan error kustom jika unik
            'tahun_ajaran.unique' => 'Tahun ajaran ini sudah ada di database.'
        ]);

        // 2. Jika validasi lolos, simpan data ke database
        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            // Cek apakah checkbox 'is_active' dicentang atau tidak
            // $request->has('is_active') akan bernilai 'true' jika dicentang, dan 'false' jika tidak
            'is_active' => $request->has('is_active') 
        ]);

        // 3. Alihkan (redirect) pengguna kembali ke halaman index (tabel)
        //    sambil mengirim 'session flash' bernama 'success'
        return redirect()->route('admin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tahunAjaran = TahunAjaran::with([
            'kelas' => function ($query) {
                $query->with(['waliKelas'])->withCount('siswa')->orderBy('nama_kelas');
            }
        ])->findOrFail($id);

        return view('admin.tahun_ajaran.show', [
            'tahunAjaran' => $tahunAjaran,
            'daftarKelas' => $tahunAjaran->kelas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        // 'TahunAjaran $tahunAjaran' (Route Model Binding)
        // Laravel otomatis mencari data TahunAjaran berdasarkan ID dari URL.
        
        // Tampilkan view 'edit.blade.php' dan kirim data $tahunAjaran
        return view('admin.tahun_ajaran.edit', [
            'tahunAjaran' => $tahunAjaran
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        // 1. Validasi data
        $request->validate([
            // Aturan unique diubah:
            // Boleh sama dengan data lama (dirinya sendiri), tapi tidak boleh sama dengan data LAIN.
            'tahun_ajaran' => 'required|string|max:10|unique:tahun_ajarans,tahun_ajaran,'.$tahunAjaran->id,
        ], [
            'tahun_ajaran.unique' => 'Tahun ajaran ini sudah ada.'
        ]);

        // 2. Update data di database
        $tahunAjaran->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_active' => $request->has('is_active')
        ]);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        // 1. Hapus data dari database
        $tahunAjaran->delete();

        // 2. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
