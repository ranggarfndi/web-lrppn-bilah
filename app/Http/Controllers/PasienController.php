<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilPasien;
use App\Models\HasilKlasifikasi;
use Illuminate\Http\Request;
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
    public function index(Request $request) 
    {
        // === LOGIKA FILTER DAN SORTIR ===

        // 1. Ambil input dari URL
        $search = $request->input('search');
        // Ambil 'sort', defaultnya adalah 'date_desc'
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
        $pasienList = $query->paginate(15)->appends($request->query());

        // 6. Kirim ke view
        return view('admin.pasien.index', compact('pasienList', 'search', 'sort'));
    }

    /**
     * Menampilkan form untuk mendaftarkan pasien baru.
     * URL: /admin/pasien/create
     */
    public function create()
    {
        // Daftar 28 Pertanyaan URICA (dummy questions sudah dihapus)
        $urica_questions = [
            1 => "Sejauh yg saya ketahui, saya tidak mempunyai masalah penyalahgunaan zat yg memerlukan perubahan",
            2 => "Saya fikir saya mungkin siap untuk memperbaiki diri saya",
            3 => "Saya sedang melakukan sesuatu terkait masalah penyalahgunaan zat yg telah lama mengganggu saya",
            4 => "Saya tidak punya masalah penyalahgunaan zat. Tidak seharusnya saya berada ditempat rehab ini",
            5 => "Saya khawatir saya akan kembali pakai zat setelah saya berubah. Jadi saya ditempat rehab ini untuk mencari pertolongan",
            6 => "Akhirnya, saat ini saya melakukan sesuatu terkait masalah penyalahgunaan zat saya",
            7 => "Sudah lama saya berpikir bahwa saya mungkin menginginkan perubahan atas diri saya",
            8 => "Ada saatnya saya ingin kembali menggunakan zat, tetapi saat ini saya sedang mencoba mengatasinya",
            9 => "Berada ditempat rehab ini cukup banyak membuang waktu saya, karena masalah penyalahgunaan zat saya tidak ada hubungannya dengan tempat ini",
            10 => "Saya berharap tempat rehab ini dapat membuat saya lebih memahami diri saya",
            11 => "Saya memang memiliki kesalahan tetapi tidak ada yang harus benar-benar saya rubah",
            12 => "Saya benar-benar bekerja keras untuk berubah",
            13 => "Saya memiliki masalah penyalahgunaan zat dan saya pikir saya harus mengatasinya",
            14 => "Saya tidak menindaklanjuti apa yang telah saya ubah dan harapkan, saya di tempat ini untuk mencegah kekambuhan dari masalah penyalahgunaan zat",
            15 => "Walau saya tidak selalu berhasil merubah diri, paling tidak saya berusaha mengatasi masalah penyalahgunaan zat saya",
            16 => "Saya pikir, setelah saya menyelesaikan penyalahgunaan zat saya, maka saya akan sepenuhnya bebas, tetapi ternyata kadang saya masih harus berjuang untuk mengatasi masalah penyalahgunaan zat tersebut.",
            17 => "Saya berharap saya memiliki lebih banyak ide (cara) untuk menyelesaikan masalah penggunaan zat saya.",
            18 => "Mungkin tempat rehab ini akan menolong saya",
            19 => "Saya mungkin memerlukan sesuatu untuk mendorong saya mempertahankan perubahan yang saat ini telah saya lakukan.",
            20 => "Saya mungkin bermasalah dengan penyalahgunaan zat tetapi saya pikir sesungguhnya saya tidak ada masalah dengan hal itu.",
            21 => "Saya berharap seseorang di tempat rehab ini mempunyai nasehat-nasehat yang berguna bagi saya",
            22 => "Siapa saja dapat bicara tentang perubahan, namun saat ini saya benar-benar sedang menjalani perubahan tersebut",
            23 => "Semua pembicaraan tentang psikologis ini membosankan. Mengapa orang tidak bisa begitu saja melupakan masalah penyalahgunaan zat mereka?",
            24 => "Saya disini untuk mencegah diri saya dari kekambuhan terhadap masalah penyalahgunaan zat mereka?",
            25 => "Memang membuat frustasi, namun saya pikir saya bakal kembali menyalahgunakan zat yang saya pikir telah selesai saya atasi",
            26 => "Saya memiliki kekhawatiran, begitu juga orang di sekitar saya. Jadi mengapa saya harus menghabiskan waktu memikirkan mereka?",
            27 => "Saat ini saya sedang aktif berusaha mengatasi masalah penyalahgunaan zat saya",
            28 => "Setelah semua yang telah saya lakukan untuk berubah dari masalah penyalahgunaan zat saya, seringkali masalah tersebut kembali dan menghantui saya."
        ];

        return view('admin.pasien.create', compact('urica_questions'));
    }

    /**
     * Menyimpan pasien baru, memanggil API klasifikasi,
     * dan menyimpan semua data.
     * URL: POST /admin/pasien
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'nama_wali' => 'nullable|string|max:255',
            'no_telepon_wali' => 'nullable|string|max:20',
            // Data Klasifikasi
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'lama_penggunaan' => 'required|string|max:100',
            'jenis_napza' => 'required|string|max:255',
            'riwayat_penyakit' => 'nullable|string|max:255',
            // Validasi URICA
            'urica' => 'required|array',
            'urica.*' => 'required|integer|min:1|max:5',
        ]);

        // 2. HITUNG SKOR URICA (VERSI DISATUKAN)
        // Ambil semua jawaban (array)
        $uricaAnswers = $request->input('urica');

        // Jumlahkan total poin dari 28 soal
        $totalPoin = array_sum($uricaAnswers);

        // Bagi dengan 7 sesuai instruksi
        $finalUricaScore = $totalPoin / 7; 
        // Hasilnya berupa float, misal: 14.5

        // 3. Siapkan data untuk API Python
        $dataUntukApi = [
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'lama_penggunaan' => $validatedData['lama_penggunaan'],
            'jenis_napza' => $validatedData['jenis_napza'],
            'riwayat_penyakit' => $validatedData['riwayat_penyakit'] ?? 'Tidak Ada',
            // [UPDATE] Kirim SATU nilai saja ke API
            'urica_score' => $finalUricaScore, 
        ];

        // 4. Panggil API Python
        $urlApi = 'http://127.0.0.1:5000/predict';
        $response = null;
        try {
            $response = Http::timeout(10)->post($urlApi, $dataUntukApi);
            if (!$response->successful()) {
                return back()->with('error', 'Gagal terhubung ke server klasifikasi (Python).')->withInput();
            }
        } catch (Exception $e) {
            return back()->with('error', 'Server klasifikasi (Python) tidak merespons.')->withInput();
        }
        
        $hasilApi = $response->json();

        // 5. Simpan ke Database
        DB::beginTransaction();
        try {
            $pasien = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'pasien',
            ]);

            // Simpan Profil
            // [PENTING] Pastikan Anda sudah membuat kolom 'urica_score' (float) 
            // di tabel profil_pasiens menggantikan 4 kolom sebelumnya.
            $pasien->profil()->create([
                'alamat' => $validatedData['alamat'],
                'tgl_lahir' => $validatedData['tgl_lahir'],
                'nama_wali' => $validatedData['nama_wali'],
                'no_telepon_wali' => $validatedData['no_telepon_wali'],
                'urica_score' => $finalUricaScore, // Simpan skor tunggal
            ]);

            // Simpan Hasil Klasifikasi
            $pasien->hasilKlasifikasi()->create([
                'data_input_json' => $dataUntukApi,
                'prediksi_knn' => $hasilApi['prediksi_knn']['tingkat_keparahan'],
                'prediksi_nb' => $hasilApi['prediksi_nb']['tingkat_keparahan'],
                'rekomendasi_program' => $hasilApi['prediksi_knn']['program'],
                'catatan_sistem' => $hasilApi['prediksi_knn']['catatan'],
            ]);

            // Riwayat Status
            $pasien->riwayatStatus()->create([
                'admin_id' => Auth::id(),
                'status_baru' => $hasilApi['prediksi_nb']['tingkat_keparahan'],
                'program_baru' => $hasilApi['prediksi_nb']['program'],
                'faktor_penyebab' => 'Hasil klasifikasi awal (Prediksi NB).',
            ]);

            DB::commit();

            return redirect()->route('admin.pasien.show', $pasien->id)
                ->with('success', 'Pasien berhasil dibuat. Skor URICA: ' . number_format($finalUricaScore, 2));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Gagal menyimpan database: ' . $e->getMessage())->withInput();
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