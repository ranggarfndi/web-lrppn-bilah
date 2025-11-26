<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilPasien;
use App\Models\HasilKlasifikasi;
use Illuminate\Http\Request; // Pastikan Request di-import
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Exception;

class PasienController extends Controller
{
    /**
     * Menampilkan daftar semua pasien (role 'pasien').
     * URL: /admin/pasien
     */
    public function index(Request $request) // Tambahkan Request $request di sini
    {
        // === LOGIKA FILTER DAN SORTIR BARU ===

        // 1. Ambil input dari URL
        $search = $request->input('search');
        // Ambil 'sort', defaultnya adalah 'date_desc' (Baru ditambahkan)
        $sort = $request->input('sort', 'date_desc');

        // 2. Mulai kueri ke database
        $query = User::where('role', 'pasien');

        // 3. Terapkan filter PENCARIAN (jika ada)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // 4. Terapkan filter PENGURUTAN (SORTIR)
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc'); // Nama A-Z
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc'); // Nama Z-A
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc'); // Terlama
                break;
            default:
                $query->orderBy('created_at', 'desc'); // Baru Ditambahkan (default)
                break;
        }

        // 5. Ambil hasil dan buat Paginasi
        // appends() akan memastikan filter & sortir tetap ada saat pindah halaman
        $pasienList = $query->paginate(15)->appends($request->query());

        // 6. Kirim semua data (termasuk variabel search & sort) ke view
        return view('admin.pasien.index', compact('pasienList', 'search', 'sort'));
    }

    /**
     * Menampilkan form untuk mendaftarkan pasien baru.
     * URL: /admin/pasien/create
     */
    public function create()
    {
        return view('admin.pasien.create');
    }

    /**
     * Menyimpan pasien baru, memanggil API klasifikasi,
     * dan menyimpan semua data.
     * URL: POST /admin/pasien
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'nama_wali' => 'nullable|string|max:255',
            'no_telepon_wali' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'lama_penggunaan' => 'required|string|max:100',
            'jenis_napza' => 'required|string|max:255',
        ]);

        // 2. Siapkan data untuk API Python
        $dataUntukApi = [
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'lama_penggunaan' => $validatedData['lama_penggunaan'],
            'jenis_napza' => $validatedData['jenis_napza'],
        ];

        // 3. Panggil API Python
        $urlApi = 'http://127.0.0.1:5000/predict';
        $response = null;
        try {
            $response = Http::timeout(10)->post($urlApi, $dataUntukApi);
            if (!$response->successful()) {
                return back()->with('error', 'Gagal terhubung ke server klasifikasi (Python).')->withInput();
            }
        } catch (Exception $e) {
            return back()->with('error', 'Server klasifikasi (Python) tidak merespons. Pastikan server Python berjalan.')->withInput();
        }
        $hasilApi = $response->json();

        // 5. Simpan semua data ke Database (Gunakan Transaksi)
        DB::beginTransaction();
        try {
            // a. Buat User (Pasien) baru
            $pasien = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'pasien',
            ]);

            // b. Buat Profil Pasien
            $pasien->profil()->create([
                'alamat' => $validatedData['alamat'],
                'tgl_lahir' => $validatedData['tgl_lahir'],
                'nama_wali' => $validatedData['nama_wali'],
                'no_telepon_wali' => $validatedData['no_telepon_wali'],
            ]);

            // c. Simpan Hasil Klasifikasi Awal
            $pasien->hasilKlasifikasi()->create([
                'data_input_json' => json_encode($dataUntukApi),
                'prediksi_knn' => $hasilApi['prediksi_knn']['tingkat_keparahan'],
                'prediksi_nb' => $hasilApi['prediksi_naive_bayes']['tingkat_keparahan'],
                'rekomendasi_program' => $hasilApi['rekomendasi_sistem']['program'],
                'catatan_sistem' => $hasilApi['rekomendasi_sistem']['catatan'],
            ]);

            // d. Buat Log Status Awal
            $pasien->riwayatStatus()->create([
                'admin_id' => Auth::id(),
                'status_baru' => $hasilApi['prediksi_knn']['tingkat_keparahan'],
                'program_baru' => $hasilApi['rekomendasi_sistem']['program'],
                'faktor_penyebab' => 'Hasil klasifikasi awal AI (Prediksi KNN).',
            ]);

            DB::commit();

            // 6. Arahkan ke halaman riwayat
            return redirect()->route('admin.pasien.show', $pasien->id)
                ->with('success', 'Pasien baru berhasil dibuat dan diklasifikasi.');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Gagal menyimpan data ke database. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Menampilkan halaman riwayat/profil detail dari satu pasien.
     * URL: /admin/pasien/{user}
     */
    public function show(User $user)
    {
        if ($user->role !== 'pasien') {
            abort(404);
        }

        /** @var \App\Models\User $user */
        $user->load([
            'profil',
            'hasilKlasifikasi',
            'riwayatStatus',
            'catatanSoap' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'tesLikertHasil' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ]);

        return view('admin.pasien.show', compact('user'));
    }

    public function getChartData(User $user)
    {
        // 1. Ambil semua hasil tes likert pasien ini, urutkan dari LAMA ke BARU
        $hasilTes = $user->tesLikertHasil()
                        ->orderBy('created_at', 'asc')
                        ->get();

        // 2. Format data agar bisa dibaca oleh Chart.js
        $labels = []; // Untuk sumbu X (Tanggal)
        $dataSkor = []; // Untuk sumbu Y (Skor)

        foreach ($hasilTes as $hasil) {
            $labels[] = $hasil->created_at->format('d M Y'); 
            $dataSkor[] = $hasil->total_skor;
        }

        // 3. Kembalikan data sebagai JSON
        return response()->json([
            'labels' => $labels,
            'data' => $dataSkor,
        ]);
    }
}
