<?php

use Illuminate\Support\Facades\Route;
// Import semua Controller yang akan kita gunakan
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\CatatanSoapController;
use App\Http\Controllers\TesLikertController;
use App\Http\Controllers\RiwayatPasienController;
use App\Http\Controllers\RiwayatStatusController;

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

// Kita gunakan middleware 'auth' dan middleware 'role:admin' (yang akan kita buat)
// Untuk sementara, kita hanya gunakan 'auth' dulu
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Manajemen Pasien (Oleh Admin) ---
    // Menampilkan daftar semua pasien
    Route::get('/admin/pasien', [PasienController::class, 'index'])->name('admin.pasien.index');
    // Menampilkan form untuk membuat pasien baru
    Route::get('/admin/pasien/create', [PasienController::class, 'create'])->name('admin.pasien.create');
    // Menyimpan data pasien baru (termasuk memanggil API Python)
    Route::post('/admin/pasien', [PasienController::class, 'store'])->name('admin.pasien.store');
    // Menampilkan profil detail satu pasien (ini halaman riwayat)
    Route::get('/admin/pasien/{user}', [PasienController::class, 'show'])->name('admin.pasien.show');
    // Chart Data
    Route::get('/admin/pasien/{user}/chart-data', [PasienController::class, 'getChartData'])->name('admin.pasien.chart');

    // --- Klasifikasi (Sudah kita gabung ke PasienController) ---
    // Route::get('/klasifikasi/baru', [KlasifikasiController::class, 'create'])->name('klasifikasi.create');
    // Route::post('/klasifikasi', [KlasifikasiController::class, 'store'])->name('klasifikasi.store');

    // --- Input SOAP (Oleh Admin) ---
    // Menampilkan form SOAP untuk pasien tertentu
    Route::get('/admin/pasien/{user}/soap/create', [CatatanSoapController::class, 'create'])->name('admin.soap.create');
    // Menyimpan data SOAP baru
    Route::post('/admin/pasien/{user}/soap', [CatatanSoapController::class, 'store'])->name('admin.soap.store');

    // --- Input Tes Likert (Oleh Admin) ---
    // Menampilkan form Likert untuk pasien tertentu
    Route::get('/admin/pasien/{user}/likert/create', [TesLikertController::class, 'create'])->name('admin.likert.create');
    // Menyimpan data tes Likert baru
    Route::post('/admin/pasien/{user}/likert', [TesLikertController::class, 'store'])->name('admin.likert.store');

    // Menampilkan form "Ubah Status" untuk pasien tertentu
    Route::get('/admin/pasien/{user}/status/create', [RiwayatStatusController::class, 'create'])->name('admin.status.create');
    // Menyimpan data status baru
    Route::post('/admin/pasien/{user}/status', [RiwayatStatusController::class, 'store'])->name('admin.status.store');
});


// ====================================================================
// == RUTE KHUSUS UNTUK PASIEN / KELUARGA ==
// ====================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Halaman Riwayat Pasien ---
    // Pasien hanya bisa melihat riwayatnya sendiri
    Route::get('/pasien/riwayat', [RiwayatPasienController::class, 'show'])->name('pasien.riwayat.show');

    // Endpoint data untuk Chart.js (akan dipanggil oleh JavaScript)
    Route::get('/pasien/riwayat/chart-data', [RiwayatPasienController::class, 'getChartData'])->name('pasien.riwayat.chart');
});


// Ini harus ada di paling bawah file
require __DIR__ . '/auth.php';
