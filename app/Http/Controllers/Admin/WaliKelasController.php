<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; 

class WaliKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data wali kelas
        $semuaWaliKelas = WaliKelas::orderBy('nama_lengkap', 'asc')->get();

        // tampilkan view dan kirim datanya
        return view('admin.wali_kelas.index', [
            'semuaWaliKelas' => $semuaWaliKelas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wali_kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input
        $request->validate([
            'nip' => 'required|string|max:20|unique:wali_kelas,nip',
            'nama_lengkap' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Simpan data ke tabel 'users' dulu
        $user = User::create([
            'name' => $request->nama_lengkap, // Kita samakan 'name' di tabel User dengan 'nama_lengkap'
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Nanti kita bisa tambahkan 'role' di sini
        ]);

        // 3. Simpan data ke tabel 'wali_kelas'
        WaliKelas::create([
            'user_id' => $user->id, // <-- Ambil ID dari user yang baru dibuat
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.wali-kelas.index')
                         ->with('success', 'Wali Kelas baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaliKelas $waliKela)
    {
        // $waliKela (variabel $waliKelas dari route) 
        // sudah otomatis diambil oleh Laravel berdasarkan ID di URL.
        
        // Kita gunakan load('user') agar relasi user (untuk email) ikut terambil
        // Ini akan menjalankan query untuk mengambil data User terkait.
        $waliKela->load('user');

        // Tampilkan view 'edit.blade.php' dan kirim data $waliKela
        return view('admin.wali_kelas.edit', [
            'waliKelas' => $waliKela // Kirim data dengan nama 'waliKelas' (sesuai di view)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaliKelas $waliKela)
    {
        // 1. Validasi
        $request->validate([
            // unique:wali_kelas,nip, (abaikan ID $waliKela->id)
            'nip' => 'required|string|max:20|unique:wali_kelas,nip,'.$waliKela->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            // unique:users,email, (abaikan ID $waliKela->user_id)
            'email' => 'required|string|email|max:255|unique:users,email,'.$waliKela->user_id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password boleh kosong (nullable)
        ]);

        // 2. Update data di tabel 'wali_kelas'
        $waliKela->update([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        // 3. Update data di tabel 'users'
        $user = $waliKela->user; // Ambil data user yang terkait
        $user->name = $request->nama_lengkap;
        $user->email = $request->email;
        
        // Cek jika field password diisi (tidak kosong)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save(); // Simpan perubahan pada data user

        // 4. Redirect kembali ke index
        return redirect()->route('admin.wali-kelas.index')
                         ->with('success', 'Data Wali Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaliKelas $waliKela)
    {
        // $waliKela adalah data dari tabel 'wali_kelas'
        
        // 1. Ambil data user yang terkait SEBELUM $waliKela dihapus
        $user = $waliKela->user;

        // 2. Hapus data WaliKelas (data di tabel 'wali_kelas')
        $waliKela->delete();

        // 3. Hapus data User (data di tabel 'users')
        //    (Kita cek dulu apakah $user-nya ada)
        if ($user) {
            $user->delete();
        }

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.wali-kelas.index')
                         ->with('success', 'Data Wali Kelas (dan akun login) berhasil dihapus.');
    }
}
