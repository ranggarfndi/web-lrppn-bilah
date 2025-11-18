<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User; // Untuk mengambil data Pasien
use App\Models\CatatanSoap; // Model untuk menyimpan data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID admin yang login

class CatatanSoapController extends Controller
{
    /**
     * Menampilkan form untuk membuat Catatan SOAP baru.
     * URL: /admin/pasien/{user}/soap/create
     *
     * @param  \App\Models\User  $user (Ini adalah pasien yang akan dicatat)
     * @return \Illuminate\View\View
     */
    public function create(User $user)
    {
        // Pastikan $user adalah pasien, bukan admin
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // Tampilkan view 'admin.soap.create' (yang akan kita buat nanti)
        // Kita kirim data $user (pasien) ke view agar kita tahu ini SOAP untuk siapa
        return view('admin.soap.create', compact('user'));
    }

    /**
     * Menyimpan Catatan SOAP baru ke database.
     * URL: POST /admin/pasien/{user}/soap
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user (Ini adalah pasien yang akan dicatat)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, User $user)
    {
        // 1. Pastikan $user adalah pasien
        if ($user->role !== 'pasien') {
            abort(404);
        }

        // 2. Validasi 4 field SOAP
        $validatedData = $request->validate([
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
        ]);

        // 3. Simpan data ke database
        // Kita gunakan relasi yang sudah kita buat di Model User
        $user->catatanSoap()->create([
            'admin_id' => Auth::id(), // ID admin yang sedang login
            'subjective' => $validatedData['subjective'],
            'objective' => $validatedData['objective'],
            'assessment' => $validatedData['assessment'],
            'plan' => $validatedData['plan'],
        ]);

        // 4. Arahkan Admin kembali ke halaman riwayat pasien
        return redirect()->route('admin.pasien.show', $user->id)
                        ->with('success', 'Catatan SOAP berhasil disimpan.');
    }
}