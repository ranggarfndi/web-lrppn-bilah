<?php

use Illuminate\Support\Facades\Route;
// Import semua Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\CatatanSoapController;
use App\Http\Controllers\TesLikertController; // Pastikan ini ada
use App\Http\Controllers\RiwayatPasienController;
use App\Http\Controllers\RiwayatStatusController;
use App\Models\HasilKlasifikasi;

// Halaman Awal (Publik)
Route::get('/', function () {
    return redirect('/dashboard');
});

// ====================================================================
// == RUTE UNTUK SEMUA PENGGUNA (ADMIN & PASIEN) YANG SUDAH LOGIN ==
// ====================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (Halaman utama setelah login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengaturan Profil (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================================================================
// == RUTE KHUSUS ADMIN (MANAJEMEN PASIEN) ==
// ====================================================================
// Catatan: Idealnya tambahkan middleware 'admin' di sini nanti
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // --- Manajemen Pasien ---
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::get('/pasien/{user}', [PasienController::class, 'show'])->name('pasien.show');
    
    // Chart Data
    Route::get('/pasien/{user}/chart-data', [PasienController::class, 'getChartData'])->name('pasien.chart');

    // --- Input SOAP ---
    Route::get('/pasien/{user}/soap/create', [CatatanSoapController::class, 'create'])->name('soap.create');
    Route::post('/pasien/{user}/soap', [CatatanSoapController::class, 'store'])->name('soap.store');

    // --- Input Tes Likert ---
    // (Perbaikan: Menggunakan Array Syntax [Class, Method])
    Route::get('/pasien/{user}/likert/create', [TesLikertController::class, 'create'])->name('likert.create');
    Route::post('/pasien/{user}/likert', [TesLikertController::class, 'store'])->name('likert.store');

    // --- Ubah Status Pasien ---
    Route::get('/pasien/{user}/status/create', [RiwayatStatusController::class, 'create'])->name('status.create');
    Route::post('/pasien/{user}/status', [RiwayatStatusController::class, 'store'])->name('status.store');

    // Route untuk Laporan Klasifikasi
    Route::get('/laporan-klasifikasi', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
});


// ====================================================================
// == RUTE KHUSUS PASIEN / KELUARGA ==
// ====================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman Riwayat Pasien (Melihat diri sendiri)
    Route::get('/pasien/riwayat', [RiwayatPasienController::class, 'show'])->name('pasien.riwayat.show');

    // Endpoint data untuk Chart.js
    Route::get('/pasien/riwayat/chart-data', [RiwayatPasienController::class, 'getChartData'])->name('pasien.riwayat.chart');
});

// Route Debugging (Bisa dihapus nanti)
Route::get('/cek-data', function () {
    $data = HasilKlasifikasi::latest()->first();
    if(!$data) return "Belum ada data klasifikasi.";

    return [
        'Tipe Data Asli' => gettype($data->data_input_json),
        'Isi Data' => $data->data_input_json,
        'Isi Raw' => $data->getRawOriginal('data_input_json')
    ];
});

require __DIR__ . '/auth.php';