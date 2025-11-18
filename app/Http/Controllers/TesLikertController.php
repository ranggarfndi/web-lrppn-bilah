<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User; // Untuk mengambil data Pasien
use App\Models\TesLikertHasil; // Model untuk menyimpan hasil tes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID admin

class TesLikertController extends Controller
{
    /**
     * Menampilkan form untuk tes Skala Likert.
     * URL: /admin/pasien/{user}/likert/create
     *
     * @param  \App\Models\User  $user (Pasien yang akan dites)
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        // Pastikan $user adalah pasien
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // --- PENTING ---
        // Di sini kita definisikan pertanyaan tes-nya.
        // Anda HARUS mengganti ini dengan pertanyaan asli dari LRPPN BI.
        $pertanyaan = [
            'q1' => 'Saya merasa optimis dengan masa depan saya.',
            'q2' => 'Saya merasa memiliki kontrol atas keinginan saya (craving).',
            'q3' => 'Hubungan saya dengan keluarga membaik.',
            'q4' => 'Saya merasa produktif dalam kegiatan harian.',
            'q5' => 'Saya mampu mengelola stres tanpa menggunakan zat.',
            // Tambahkan pertanyaan lain sebanyak yang Anda butuhkan (q6, q7, ...)
        ];

        // Tampilkan view 'admin.likert.create' (yang akan kita buat nanti)
        return view('admin.likert.create', compact('user', 'pertanyaan'));
    }

    /**
     * Menyimpan hasil tes Skala Likert ke database.
     * URL: POST /admin/pasien/{user}/likert
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user (Pasien yang akan dites)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        // 1. Pastikan $user adalah pasien
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // 2. Validasi data
        // Kita akan memvalidasi semua pertanyaan yang kita definisikan di 'create'
        // Ini adalah contoh validasi untuk 5 pertanyaan
        $validatedData = $request->validate([
            'q1' => 'required|integer|min:1|max:5',
            'q2' => 'required|integer|min:1|max:5',
            'q3' => 'required|integer|min:1|max:5',
            'q4' => 'required|integer|min:1|max:5',
            'q5' => 'required|integer|min:1|max:5',
            // Jika Anda menambah pertanyaan, tambahkan validasinya di sini juga
        ]);

        // 3. Hitung Skor Total
        $totalSkor = 0;
        foreach ($validatedData as $skor) {
            $totalSkor += (int)$skor;
        }

        // 4. Tentukan Skor Maksimal (Skor 5 x Jumlah Pertanyaan)
        // (Dalam contoh ini ada 5 pertanyaan)
        $skorMaksimal = 5 * count($validatedData); 

        // 5. Simpan data ke database
        $user->tesLikertHasil()->create([
            'admin_id' => Auth::id(), // ID admin yang sedang login
            'total_skor' => $totalSkor,
            'skor_maksimal' => $skorMaksimal,
            'jawaban_detail' => json_encode($validatedData), // Simpan semua jawaban sbg JSON
        ]);

        // 6. Arahkan Admin kembali ke halaman riwayat pasien
        return redirect()->route('admin.pasien.show', $user->id)
                        ->with('success', 'Hasil Tes Likert berhasil disimpan.');
    }
}