<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        $query = Kelas::with(['tahunAjaran', 'waliKelas'])
                    ->orderBy('nama_kelas', 'asc');

        if ($tahunPilihanId) {
            $query->where('tahun_ajaran_id', $tahunPilihanId);
        }

        $semuaKelas = $query->paginate(10)->withQueryString();
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.kelas.index', [
            'semuaKelas' => $semuaKelas,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }

    public function create()
    {
        $semuaTahunAjaran = TahunAjaran::where('is_active', true)->orderBy('tahun_ajaran', 'desc')->get();
        $semuaWaliKelas = WaliKelas::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.kelas.create', [
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'semuaWaliKelas' => $semuaWaliKelas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'wali_kelas_id' => 'required|exists:wali_kelas,id',
        ], [
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'wali_kelas_id.required' => 'Wali Kelas wajib dipilih.',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas baru berhasil ditambahkan.');
    }

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

    public function edit(Kelas $kela)
    {
        $semuaTahunAjaran = TahunAjaran::where('is_active', true)->orderBy('tahun_ajaran', 'desc')->get();
        $semuaWaliKelas = WaliKelas::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.kelas.edit', [
            'kela' => $kela,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'semuaWaliKelas' => $semuaWaliKelas
        ]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'wali_kelas_id' => 'required|exists:wali_kelas,id',
        ], [
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'wali_kelas_id.required' => 'Wali Kelas wajib dipilih.',
        ]);

        $kela->update([
            'nama_kelas' => $request->nama_kelas,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')
                         ->with('success', 'Data Kelas berhasil dihapus.');
    }
}
