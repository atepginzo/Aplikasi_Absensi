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
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ManualAbsensiController;
use App\Http\Controllers\WaliKelas\DashboardController as WaliDashboardController;
use App\Http\Controllers\WaliKelas\LaporanController as WaliLaporanController;

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
    ->middleware(['auth', 'verified', 'role:admin'])
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Rute 'Resource' untuk CRUD
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('wali-kelas', WaliKelasController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('siswa', SiswaController::class);
    // Rute Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/{kelas}/export', [LaporanController::class, 'exportPdf'])->name('laporan.export');
    Route::get('/laporan/{kelas}/tanggal/{tanggal}', [LaporanController::class, 'detail'])->name('laporan.detail');
    Route::get('/laporan/{kelas}', [LaporanController::class, 'show'])->name('laporan.show');

});
// ==============================================
// == RUTE ABSENSI (ADMIN & WALI KELAS) ==
// ==============================================
Route::middleware(['auth', 'role:admin,wali_kelas'])->prefix('admin/absensi')->name('admin.absensi.')->group(function () {
    Route::get('/scan', [AbsensiController::class, 'index'])->name('scan');
    Route::post('/store', [AbsensiController::class, 'store'])->name('store');

    Route::get('/manual', [ManualAbsensiController::class, 'index'])->name('manual.index');
    Route::get('/manual/{kelas}', [ManualAbsensiController::class, 'show'])->name('manual.show');
    Route::post('/manual/store', [ManualAbsensiController::class, 'store'])->name('manual.store');
});

// ==============================================
// == DASHBOARD & LAPORAN WALI KELAS ==
// ==============================================
Route::middleware(['auth', 'role:wali_kelas'])->prefix('wali-kelas')->name('wali.')->group(function () {
    Route::get('/dashboard', [WaliDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [WaliLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{kelas}', [WaliLaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{kelas}/detail/{tanggal}', [WaliLaporanController::class, 'detail'])->name('laporan.detail');
    Route::get('/laporan/{kelas}/export', [WaliLaporanController::class, 'exportPdf'])->name('laporan.export');
});
// ==============================================
// == AKHIR GRUP RUTE ADMIN ==
// ==============================================


// Baris ini WAJIB ada di PALING BAWAH
// Ini untuk memuat semua rute login/register/logout dari file auth.php
require __DIR__.'/auth.php';