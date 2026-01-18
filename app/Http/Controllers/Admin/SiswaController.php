<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        $query = Siswa::with(['kelas.waliKelas', 'kelas.tahunAjaran'])
                      ->orderBy('nama_siswa', 'asc');

        if ($tahunPilihanId) {
            $query->whereHas('kelas', function($q) use ($tahunPilihanId) {
                $q->where('tahun_ajaran_id', $tahunPilihanId);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $semuaSiswa = $query->paginate(10)->withQueryString();
        
        $semuaKelas = Kelas::when($tahunPilihanId, function($q) use ($tahunPilihanId) {
            $q->where('tahun_ajaran_id', $tahunPilihanId);
        })->orderBy('nama_kelas', 'asc')->get();

        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.siswa.index', [
            'semuaSiswa' => $semuaSiswa,
            'semuaKelas' => $semuaKelas,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'kelasDipilih' => $request->kelas_id,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }

    public function create()
    {
        $semuaKelas = Kelas::with('tahunAjaran')->orderBy('nama_kelas', 'asc')->get();

        return view('admin.siswa.create', [
            'semuaKelas' => $semuaKelas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'parent_email' => 'nullable|email|unique:users,email',
            'parent_password' => 'nullable|string|min:8',
        ]);

        $parentUserId = null;

        if ($request->filled('parent_email')) {
            $parentUser = User::create([
                'name' => $request->nama_siswa.' - Orang Tua',
                'email' => $request->parent_email,
                'password' => Hash::make($request->parent_password ?: Str::random(12)),
                'role' => 'orang_tua',
            ]);

            $parentUserId = $parentUser->id;
        }

        Siswa::create([
            'kelas_id' => $request->kelas_id,
            'parent_user_id' => $parentUserId,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'qrcode_token' => (string) Str::uuid(),
        ]);

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas.tahunAjaran', 'kelas.waliKelas']);

        return view('admin.siswa.show', [
            'siswa' => $siswa
        ]);
    }

    public function edit(Siswa $siswa)
    {
        $semuaKelas = Kelas::with('tahunAjaran')->orderBy('nama_kelas', 'asc')->get();

        return view('admin.siswa.edit', [
            'siswa' => $siswa,
            'semuaKelas' => $semuaKelas
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nis' => 'required|string|max:20|unique:siswas,nis,'.$siswa->id,
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'parent_email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore(optional($siswa->orangTua)->id),
            ],
            'parent_password' => 'nullable|string|min:8',
        ]);

        $parentEmail = $request->input('parent_email');
        $parentPassword = $request->input('parent_password');

        $orangTua = $siswa->orangTua;

        if ($parentEmail) {
            if (! $orangTua) {
                $orangTua = User::create([
                    'name' => $request->nama_siswa.' - Orang Tua',
                    'email' => $parentEmail,
                    'password' => Hash::make($parentPassword ?: Str::random(12)),
                    'role' => 'orang_tua',
                ]);

                $siswa->parent_user_id = $orangTua->id;
            } else {
                $orangTua->email = $parentEmail;

                if ($parentPassword) {
                    $orangTua->password = Hash::make($parentPassword);
                }

                $orangTua->save();
            }
        }

        $siswa->kelas_id = $request->kelas_id;
        $siswa->nis = $request->nis;
        $siswa->nama_siswa = $request->nama_siswa;
        $siswa->jenis_kelamin = $request->jenis_kelamin;

        $siswa->save();

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls,csv',
        ], [
            'file_import.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_import.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
        ]);

        $import = new SiswaImport();

        try {
            $import->import($request->file('file_import'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Terjadi kesalahan saat memproses file: '.$th->getMessage());
        }

        $successCount = $import->successCount;
        $message = "Berhasil mengimport {$successCount} data siswa.";

        if ($import->failures()->isNotEmpty()) {
            $failures = $import->failures()->map(function ($failure) {
                return [
                    'row' => $failure->row(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            })->toArray();

            return back()->with([
                'success' => $message.' Beberapa baris dilewati karena data tidak valid.',
                'import_failures' => $failures,
            ]);
        }

        return back()->with('success', $message);
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport(), 'template_import_siswa.xlsx');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Siswa berhasil dihapus.');
    }
}
