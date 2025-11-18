<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use App\Models\User; // Kita akan memuat data User
use App\Models\TesLikertHasil; // Untuk mengambil data chart

class RiwayatPasienController extends Controller
{
    /**
     * Menampilkan halaman riwayat untuk pasien yang sedang login.
     * URL: /pasien/riwayat
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // 1. Dapatkan user (pasien) yang sedang login
        $pasien = Auth::user();

        /** @var \App\Models\User $pasien */ // <--- TAMBAHKAN BARIS INI

        // 2. Pastikan rolenya adalah 'pasien'
        if ($pasien->role !== 'pasien') {
            // ... (sisa kode) ...
        }

        // 3. Ambil semua data riwayat pasien
        $pasien->load([
            'profil',
            'hasilKlasifikasi',
            'riwayatStatus', // <-- TAMBAHKAN BARIS INI
            'catatanSoap' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'tesLikertHasil' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ]);

        // 4. Tampilkan view 'pasien.riwayat' (yang akan kita buat nanti)
        return view('pasien.riwayat', compact('pasien'));
    }

    /**
     * Menyediakan data untuk chart perkembangan (Skala Likert).
     * URL: /pasien/riwayat/chart-data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData()
    {
        // 1. Dapatkan user (pasien) yang sedang login
        $pasien = Auth::user();

        // --- KODE YANG DIPERBAIKI (LEBIH EKSPLISIT) ---
        // 2. Ambil semua hasil tes likert secara manual (tanpa "sihir")
        // Ini adalah cara yang SANGAT JELAS bagi editor
        $hasilTes = TesLikertHasil::where('user_id', $pasien->id)
            ->orderBy('created_at', 'asc')
            ->get();
        // --- AKHIR KODE YANG DIPERBAIKI ---

        // 3. Format data agar bisa dibaca oleh Chart.js
        $labels = []; // Untuk sumbu X (Tanggal)
        $dataSkor = []; // Untuk sumbu Y (Skor)

        foreach ($hasilTes as $hasil) {
            // Format tanggal menjadi "13 Nov 2025"
            $labels[] = $hasil->created_at->format('d M Y');
            $dataSkor[] = $hasil->total_skor;
        }

        // 4. Kembalikan data sebagai JSON
        return response()->json([
            'labels' => $labels,
            'data' => $dataSkor,
        ]);
    }
}
