<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\Kelas; // <-- Tambahkan model Kelas
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- Tambahkan helper Str untuk UUID
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas.waliKelas'])
                      ->orderBy('nama_siswa', 'asc');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $semuaSiswa = $query->paginate(10)->withQueryString();
        $semuaKelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('admin.siswa.index', [
            'semuaSiswa' => $semuaSiswa,
            'semuaKelas' => $semuaKelas,
            'kelasDipilih' => $request->kelas_id,
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
            'parent_email' => 'nullable|email|unique:users,email',
            'parent_password' => 'nullable|string|min:8',
        ]);

        // 2. Buat akun orang tua (jika diisi)
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

        // 3. Simpan Data Siswa
        // Kita generate UUID untuk qrcode_token di sini
        Siswa::create([
            'kelas_id' => $request->kelas_id,
            'parent_user_id' => $parentUserId,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'qrcode_token' => (string) Str::uuid(), // <-- Magic happens here!
        ]);

        // 4. Redirect
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
            'parent_email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore(optional($siswa->orangTua)->id),
            ],
            'parent_password' => 'nullable|string|min:8',
        ]);

        // 2. Update / buat akun orang tua jika diperlukan
        $parentEmail = $request->input('parent_email');
        $parentPassword = $request->input('parent_password');

        $orangTua = $siswa->orangTua;

        if ($parentEmail) {
            // Jika belum punya akun orang tua -> buat baru
            if (! $orangTua) {
                $orangTua = User::create([
                    'name' => $request->nama_siswa.' - Orang Tua',
                    'email' => $parentEmail,
                    'password' => Hash::make($parentPassword ?: Str::random(12)),
                    'role' => 'orang_tua',
                ]);

                $siswa->parent_user_id = $orangTua->id;
            } else {
                // Sudah punya akun orang tua -> update data
                $orangTua->email = $parentEmail;

                if ($parentPassword) {
                    $orangTua->password = Hash::make($parentPassword);
                }

                $orangTua->save();
            }
        }

        // 3. Update data siswa
        // Kita TIDAK mengupdate 'qrcode_token' agar QR code lama tetap valid
        $siswa->kelas_id = $request->kelas_id;
        $siswa->nis = $request->nis;
        $siswa->nama_siswa = $request->nama_siswa;
        $siswa->jenis_kelamin = $request->jenis_kelamin;

        $siswa->save();

        // 4. Redirect kembali ke index
        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Import data siswa dari file Excel.
     */
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

    /**
     * Download template import siswa.
     */
    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport(), 'template_import_siswa.xlsx');
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