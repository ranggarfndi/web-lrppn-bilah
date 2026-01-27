<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Kita ambil dari User karena relasinya ada di sana
use App\Models\HasilKlasifikasi;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data pasien yang punya hasil klasifikasi (dari CSV/Input Manual)
        // Kita gunakan Pagination (misal 50 data per halaman) agar website tidak berat meload 1399 data sekaligus
        $query = User::where('role', 'pasien')
                     ->whereHas('hasilKlasifikasi') // Hanya yang punya data klasifikasi
                     ->with(['hasilKlasifikasi', 'profil']) // Eager load biar cepat
                     ->orderBy('created_at', 'desc');

        // Fitur Pencarian Sederhana
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate(50);

        return view('admin.laporan.index', compact('data'));
    }
}