<?php

namespace App\Http\Controllers;

use App\Models\User; // Untuk mengambil data Pasien
use App\Models\RiwayatStatus; // Model untuk menyimpan data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID admin

class RiwayatStatusController extends Controller
{
    /**
     * Menampilkan form untuk mengubah status/program pasien secara manual.
     * URL: /admin/pasien/{user}/status/create
     *
     * @param  \App\Models\User  $user (Pasien yang akan diubah)
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        // Pastikan $user adalah pasien
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // Ambil data status & program terakhir pasien (jika ada)
        $statusTerakhir = $user->riwayatStatus()->latest()->first();

        // Siapkan daftar program (dari proposal Anda)
        $daftarProgram = [
            'Rehabilitasi Medis',
            'Rehabilitasi Non-Medis (Sosial)',
            'Rawat Inap',
            'Rawat Jalan',
            'Terapi Komunitas',
            'Program Selesai', // Tambahan
            'Lainnya',
        ];

        // Tampilkan view 'admin.status.create' (yang akan kita buat nanti)
        return view('admin.status.create', compact('user', 'statusTerakhir', 'daftarProgram'));
    }

    /**
     * Menyimpan perubahan status/program baru ke database.
     * URL: POST /admin/pasien/{user}/status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user (Pasien yang akan diubah)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        // 1. Pastikan $user adalah pasien
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // 2. Validasi input dari form
        $validatedData = $request->validate([
            'status_baru' => 'required|string|max:255',
            'program_baru' => 'required|string|max:255',
            'faktor_penyebab' => 'required|string|min:10', // Wajib diisi alasan
        ]);

        // 3. Simpan data ke database
        // Kita gunakan relasi yang sudah kita buat di Model User
        $user->riwayatStatus()->create([
            'admin_id' => Auth::id(), // ID admin yang sedang login
            'status_baru' => $validatedData['status_baru'],
            'program_baru' => $validatedData['program_baru'],
            'faktor_penyebab' => $validatedData['faktor_penyebab'],
        ]);

        // 4. Arahkan Admin kembali ke halaman riwayat pasien
        return redirect()->route('admin.pasien.show', $user->id)
            ->with('success', 'Status dan program pasien berhasil diperbarui.');
    }
}
