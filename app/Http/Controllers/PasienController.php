<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilPasien;
use App\Models\HasilKlasifikasi;
use App\Models\RiwayatStatusPasien; // Pastikan model ini di-import
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
        // Daftar 28 Pertanyaan URICA
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
     * Menyimpan pasien baru, memanggil SCRIPT PYTHON,
     * dan menyimpan semua data lengkap (KNN & NB).
     * URL: POST /admin/pasien
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (BIARKAN SAMA)
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
            'riwayat_penyakit' => 'nullable|string|max:255',
            'urica' => 'required|array',
            'urica.*' => 'required|integer|min:1|max:5',
        ]);

        // 2. HITUNG SKOR URICA (BIARKAN SAMA)
        $uricaAnswers = $request->input('urica');
        $totalPoin = array_sum($uricaAnswers);
        $finalUricaScore = $totalPoin / 7;

        // 3. SIAPKAN DATA UNTUK PYTHON
        $dataUntukApi = [
            'gender' => $validatedData['jenis_kelamin'],
            'lama_pakai' => $validatedData['lama_penggunaan'],
            'urica' => $finalUricaScore,
            'napza' => $validatedData['jenis_napza'],
            'penyakit' => $validatedData['riwayat_penyakit'] ?? 'Tidak Ada'
        ];

        // 4. PANGGIL PYTHON (VERSI BASE64 & ERROR LOGGING)
        $pythonExec = "python"; 
        // [PASTIKAN PATH INI SESUAI DENGAN KOMPUTER ANDA]
        $scriptPath = "D:/laragon/www/proyek-api-python/predict.py"; 

        $jsonString = json_encode($dataUntukApi);
        $base64Args = base64_encode($jsonString);
        
        // Tambahkan 2>&1 agar error tertangkap (sama seperti Seeder)
        $command = $pythonExec . " " . $scriptPath . " " . $base64Args . " 2>&1";
        
        $output = shell_exec($command);
        $hasilApi = json_decode($output, true);

        // Cek Error Python
        if (!$hasilApi || isset($hasilApi['error'])) {
            return back()->with('error', 'Gagal memproses AI: ' . ($hasilApi['error'] ?? $output))->withInput();
        }

        // 5. AMBIL HASIL LENGKAP (Termasuk Matrix & Debug)
        $knnLabel = $hasilApi['knn']['label'] ?? 'Belum Ditentukan';
        $knnConf  = $hasilApi['knn']['confidence'] ?? 0;
        
        $nbLabel  = $hasilApi['nb']['label'] ?? 'Belum Ditentukan';
        $nbConf   = $hasilApi['nb']['confidence'] ?? 0;
        $nbProbs  = $hasilApi['nb']['probs'] ?? [];

        // [BARU] Ambil Data Numerik & Debug KNN
        $matrix   = $hasilApi['matrix_nilai'] ?? [];
        $debugKnn = $hasilApi['debug_knn'] ?? [];

        // Tentukan Program
        $program = match ($nbLabel) {
            'Berat', 'Sangat Berat' => 'Rawat Inap',
            'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
            'Ringan' => 'Rawat Jalan',
            default => 'Belum Ditentukan',
        };

        // 6. SIMPAN KE DATABASE
        DB::beginTransaction();
        try {
            $pasien = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'pasien',
            ]);

            $pasien->profil()->create([
                'alamat' => $validatedData['alamat'],
                'tgl_lahir' => $validatedData['tgl_lahir'],
                'nama_wali' => $validatedData['nama_wali'],
                'no_telepon_wali' => $validatedData['no_telepon_wali'],
                'urica_score' => $finalUricaScore,
            ]);

            // [UPDATE PENTING] Simpan struktur lengkap agar Modal Popup & Tabel Numerik berfungsi
            $dataSimpanJson = $dataUntukApi;
            $dataSimpanJson['hasil_ai'] = [
                'knn' => ['label' => $knnLabel, 'confidence' => $knnConf],
                'nb'  => ['label' => $nbLabel, 'confidence' => $nbConf, 'probs' => $nbProbs],
                'matrix_nilai' => $matrix,   // <--- Supaya Tab 2 Muncul Angkanya
                'debug_knn' => $debugKnn     // <--- Supaya Tombol Mata Muncul Rumusnya
            ];

            $pasien->hasilKlasifikasi()->create([
                'data_input_json' => $dataSimpanJson,
                'prediksi_knn' => $knnLabel,
                'prediksi_nb' => $nbLabel,
                'rekomendasi_program' => $program,
                'catatan_sistem' => "Input Manual. NB Conf: {$nbConf}%, KNN Conf: {$knnConf}%",
            ]);

            $pasien->riwayatStatus()->create([
                'admin_id' => Auth::id(),
                'status_baru' => $nbLabel,
                'program_baru' => $program,
                'faktor_penyebab' => 'Input Manual (AI Processed)',
                'keterangan' => 'Klasifikasi awal sistem (Komparasi KNN & NB).'
            ]);

            DB::commit();

            return redirect()->route('admin.pasien.show', $pasien->id)
                ->with('success', 'Pasien berhasil dibuat. Prediksi AI: ' . $nbLabel);
        
        } catch (Exception $e) {
            DB::rollBack();
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

    /**
     * METHOD BARU: Untuk Analisa Ulang Data (Re-Diagnose)
     * URL: POST /admin/pasien/{user}/reprocess
     */
    public function reprocessAi(User $user)
    {
        // 1. Ambil data pasien yang sudah ada
        $profil = $user->profil;
        $klasifikasi = $user->hasilKlasifikasi;

        if (!$profil || !$klasifikasi) {
            return back()->with('error', 'Data profil tidak lengkap.');
        }

        // Ambil data dari JSON lama atau kolom profil
        $jsonLama = $klasifikasi->data_input_json ?? [];
        
        // Mapping ulang data untuk Python
        $inputData = [
            'gender'     => $jsonLama['gender'] ?? $jsonLama['jenis_kelamin'] ?? 'Laki-Laki',
            'lama_pakai' => $jsonLama['lama_pakai'] ?? $jsonLama['lama_penggunaan'] ?? '0 Tahun',
            'urica'      => $profil->urica_score ?? 0,
            'napza'      => $jsonLama['napza'] ?? $jsonLama['jenis_napza'] ?? '-',
            'penyakit'   => $jsonLama['penyakit'] ?? $jsonLama['riwayat_penyakit'] ?? '-'
        ];

        // 2. Panggil Python
        $pythonExec = "python"; 
        $scriptPath = "D:/laragon/www/proyek-api-python/predict.py"; 

        $jsonArgs = json_encode($inputData);
        $command = $pythonExec . " " . $scriptPath . " " . escapeshellarg($jsonArgs);
        $output = shell_exec($command);
        $ai = json_decode($output, true);

        if (!$ai || isset($ai['error'])) {
            return back()->with('error', 'Gagal hitung AI: ' . ($ai['error'] ?? 'Output Error'));
        }

        // 3. Ambil Hasil Baru
        $knnLabel = $ai['knn']['label'] ?? '-';
        $knnConf  = $ai['knn']['confidence'] ?? 0;
        $nbLabel  = $ai['nb']['label'] ?? '-';
        $nbConf   = $ai['nb']['confidence'] ?? 0;
        $nbProbs  = $ai['nb']['probs'] ?? [];

        $program = match ($nbLabel) {
            'Berat', 'Sangat Berat' => 'Rawat Inap',
            'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
            'Ringan' => 'Rawat Jalan',
            default => 'Belum Ditentukan',
        };

        // 4. Update Database
        $jsonBaru = $inputData;
        $jsonBaru['hasil_ai'] = [
            'knn' => ['label' => $knnLabel, 'confidence' => $knnConf],
            'nb'  => ['label' => $nbLabel, 'confidence' => $nbConf, 'probs' => $nbProbs]
        ];

        $user->hasilKlasifikasi()->update([
            'data_input_json' => $jsonBaru,
            'prediksi_knn' => $knnLabel,
            'prediksi_nb' => $nbLabel,
            'rekomendasi_program' => $program,
            'catatan_sistem' => "Analisa Ulang (Reprocess). NB: {$nbConf}%, KNN: {$knnConf}%",
        ]);

        // Tambah status baru ke timeline
        $user->riwayatStatus()->create([
            'admin_id' => Auth::id(),
            'status_baru' => $nbLabel,
            'program_baru' => $program,
            'faktor_penyebab' => 'Analisa Ulang (Manual Trigger)',
            'keterangan' => 'Perhitungan ulang menggunakan model terbaru.'
        ]);

        return back()->with('success', 'Data berhasil dianalisa ulang! Hasil: ' . $nbLabel);
    }

    public function getChartData(User $user)
    {
        $hasilTes = $user->tesLikertHasil()
                        ->orderBy('created_at', 'asc')
                        ->get();

        $labels = []; 
        $dataSkor = []; 

        foreach ($hasilTes as $hasil) {
            $labels[] = $hasil->created_at->format('d M Y'); 
            $dataSkor[] = $hasil->total_skor;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $dataSkor,
        ]);
    }
}