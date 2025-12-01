<?php

// DAFTAR IMPORT UNTUK web.php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ProfileController;

// Import SEMUA controller admin Anda
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| File ini untuk rute APLIKASI Anda (halaman utama, dashboard, admin panel).
|
*/

// Rute Halaman Utama (/) -> Arahkan ke Login
Route::get('/', function () {
    return Redirect::to('/login');
});

// Rute Dashboard Bawaan Breeze
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute Profil Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==============================================
// == GRUP RUTE ADMIN ANDA ==
// ==============================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Rute 'Resource' untuk CRUD
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('wali-kelas', WaliKelasController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('siswa', SiswaController::class);
    // Halaman Scanner (Kamera)
    Route::get('/absensi/scan', [AbsensiController::class, 'index'])->name('absensi.scan');
    // Proses Scan (Terima Data)
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

});
// ==============================================
// == AKHIR GRUP RUTE ADMIN ==
// ==============================================


// Baris ini WAJIB ada di PALING BAWAH
// Ini untuk memuat semua rute login/register/logout dari file auth.php
require __DIR__.'/auth.php';