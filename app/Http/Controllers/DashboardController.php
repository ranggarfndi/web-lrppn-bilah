<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\HasilKlasifikasi;
use App\Models\CatatanSoap;

class DashboardController extends Controller
{
    /**
     * Menangani request ke halaman /dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {

            // --- Logika Statistik Admin (Ini sudah benar) ---
            $totalPasien = User::where('role', 'pasien')->count();
            $klasifikasiBulanIni = HasilKlasifikasi::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $soapHariIni = CatatanSoap::whereDate('created_at', today())->count();
            $pasienTerbaru = User::where('role', 'pasien')
                ->with('profil')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', [
                'user' => $user,
                'totalPasien' => $totalPasien,
                'klasifikasiBulanIni' => $klasifikasiBulanIni,
                'soapHariIni' => $soapHariIni,
                'pasienTerbaru' => $pasienTerbaru,
            ]);
        } elseif ($user->role === 'pasien') {

            // --- LOGIKA STATISTIK PASIEN (DIPERBAIKI) ---

            // 1. Ambil relasi yang diperlukan (Eager Loading)
            // KITA TAMBAHKAN 'riwayatStatus'
            $user->load('hasilKlasifikasi', 'tesLikertHasil', 'catatanSoap', 'riwayatStatus');

            // 2. Ambil data program TERBARU
            // Kita ambil data pertama dari relasi riwayatStatus (yang sudah diurutkan 'desc')
            $statusTerbaru = $user->riwayatStatus->first();

            if ($statusTerbaru) {
                // Jika sudah ada riwayat, gunakan program terbaru
                $program = $statusTerbaru->program_baru;
            } else {
                // Fallback jika (karena alasan aneh) riwayatnya kosong
                $program = 'Belum Ditentukan';
            }

            // 3. Hitung total tes
            $totalTes = $user->tesLikertHasil->count();

            // 4. Hitung total catatan
            $totalSoap = $user->catatanSoap->count();

            // --- AKHIR LOGIKA STATISTIK PASIEN ---

            // Kirim semua data ke view
            return view('pasien.dashboard', [
                'user' => $user,
                'program' => $program,
                'totalTes' => $totalTes,
                'totalSoap' => $totalSoap,
            ]);
        } else {
            // Fallback (Breeze default)
            return view('dashboard');
        }
    }
}
