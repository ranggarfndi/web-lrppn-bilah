<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ImportDataCsvSeeder extends Seeder
{
    /**
     * Menjalankan database seeds.
     */
    public function run(): void
    {
        // 1. BERSIHKAN DATABASE LAMA (TRUNCATE)
        // Kita pakai DB::table('nama_tabel')->truncate() agar lebih pasti menghapus tabel yang benar
        $this->command->warn("Membersihkan database lama...");
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        User::truncate();
        \App\Models\ProfilPasien::truncate();
        
        // Hapus data di tabel hasil_klasifikasis (sesuai request Anda pakai 's')
        // Jika nanti error "Table doesn't exist", ganti jadi 'hasil_klasifikasi' (tanpa s)
        try {
            DB::table('hasil_klasifikasis')->truncate(); 
        } catch (\Exception $e) {
            // Jaga-jaga kalau ternyata namanya singular di database
            DB::table('hasil_klasifikasi')->truncate();
        }

        \App\Models\RiwayatStatus::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. LOAD CSV
        $files = glob(database_path('csv/*.csv'));
        if (empty($files)) {
            $this->command->warn("Tidak ada file CSV ditemukan di database/csv.");
            return;
        }

        foreach ($files as $file) {
            $this->command->info("Memproses: " . basename($file));
            $this->importFile($file, $this->detectDelimiter($file));
        }
    }

    private function detectDelimiter($filePath)
    {
        $handle = fopen($filePath, "r");
        $line = fgets($handle); fclose($handle);
        return (strpos($line, ';') !== false) ? ';' : ',';
    }

    private function importFile($filePath, $delimiter)
    {
        $handle = fopen($filePath, "r");
        fgetcsv($handle, 0, $delimiter); // Skip Header

        // SETUP PYTHON
        $scriptPath = "D:/laragon/www/proyek-api-python/predict.py"; 
        $pythonExec = "python"; 
        $count = 0;

        $this->command->info("Mulai Import data... (Mohon Tunggu)");

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($row) < 9) continue; 
            
            $nama = trim($row[1]);
            if (empty($nama)) continue;

            // [FIX UTAMA] TANGGAL ANTI-2026
            // Menggunakan fungsi manual di bawah agar "January 2021" terbaca benar
            $tanggalMasuk = $this->parseDateManual($row[2]); 
            
            // Generate Data
            $cleanName = preg_replace('/[^a-z0-9]/', '', strtolower($nama));
            $email = $cleanName . '.' . uniqid() . '@lrppn.com';
            
            // Cek Urica
            $uricaScore = isset($row[9]) && is_numeric($row[9]) ? (float)$row[9] : 0;
            // Cek jika terbalik dengan kolom sebelah (kadang terjadi di CSV)
            if (isset($row[8]) && is_numeric($row[8]) && !is_numeric($row[9])) {
                 $uricaScore = (float)$row[8];
            }

            // Siapkan Data AI
            $inputData = [
                'gender' => $row[3], 'lama_pakai' => $row[6],
                'urica' => $uricaScore, 'napza' => $row[5], 'penyakit' => $row[7]
            ];

            // Panggil Python
            $cmd = $pythonExec . " " . $scriptPath . " " . base64_encode(json_encode($inputData)) . " 2>&1";
            $output = shell_exec($cmd);
            $ai = json_decode($output, true);

            // Default Values
            $knnLabel = '-'; $knnConf = 0; $nbLabel = '-'; $nbConf = 0; 
            $nbProbs = []; $matrix = []; $debugKnn = [];

            if ($ai && !isset($ai['error'])) {
                $knnLabel = $ai['knn']['label'] ?? '-';
                $knnConf  = $ai['knn']['confidence'] ?? 0;
                $nbLabel  = $ai['nb']['label'] ?? '-';
                $nbConf   = $ai['nb']['confidence'] ?? 0;
                $nbProbs  = $ai['nb']['probs'] ?? [];
                $matrix   = $ai['matrix_nilai'] ?? []; 
                $debugKnn = $ai['debug_knn'] ?? [];
            }

            $program = match ($nbLabel) {
                'Berat', 'Sangat Berat' => 'Rawat Inap',
                'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
                'Ringan' => 'Rawat Jalan',
                default => 'Belum Ditentukan',
            };

            // Simpan ke DB
            try {
                DB::transaction(function () use ($nama, $email, $tanggalMasuk, $uricaScore, $knnLabel, $knnConf, $nbLabel, $nbConf, $nbProbs, $matrix, $debugKnn, $program, $inputData, $row) {
                    $user = User::create([
                        'name' => $nama, 'email' => $email, 'password' => Hash::make('password'), 
                        'role' => 'pasien', 
                        'created_at' => $tanggalMasuk, // Tanggal manual (2021)
                        'updated_at' => $tanggalMasuk,
                    ]);

                    $user->profil()->create([
                        'alamat' => $row[4] ?? '-', 'nama_wali' => 'Data Import', 
                        'urica_score' => $uricaScore, 
                        'created_at' => $tanggalMasuk, 
                        'updated_at' => $tanggalMasuk,
                    ]);

                    $dataJson = $inputData;
                    $dataJson['hasil_ai'] = [
                        'knn' => ['label' => $knnLabel, 'confidence' => $knnConf],
                        'nb'  => ['label' => $nbLabel, 'confidence' => $nbConf, 'probs' => $nbProbs],
                        'matrix_nilai' => $matrix, 'debug_knn' => $debugKnn
                    ];

                    $user->hasilKlasifikasi()->create([
                        'data_input_json' => $dataJson,
                        'prediksi_knn' => $knnLabel, 
                        'prediksi_nb' => $nbLabel,
                        // 'label_asli' => $labelAsli, // SUDAH DIHAPUS TOTAL (Anti Error)
                        'rekomendasi_program' => $program,
                        'catatan_sistem' => "Import CSV. KNN:{$knnConf}%",
                        'created_at' => $tanggalMasuk, 
                        'updated_at' => $tanggalMasuk,
                    ]);

                    $user->riwayatStatus()->create([
                        'admin_id' => 1, 'status_baru' => $nbLabel, 'program_baru' => $program,
                        'faktor_penyebab' => 'Import CSV', 
                        'created_at' => $tanggalMasuk, 
                        'updated_at' => $tanggalMasuk,
                    ]);
                });
                
                $count++;

                // ============================================
                // [MODE TESTING] Berhenti setelah 10 data
                // Hapus blok if ini nanti kalau sudah sukses tes
                // ============================================
                // if ($count >= 100) { 
                //     $this->command->warn("MODE TESTING: Berhenti otomatis setelah 10 data.");
                //     break; 
                // }
                // ============================================

                if($count % 20 == 0) $this->command->info("..terproses $count data..");

            } catch (\Exception $e) {
                 // Skip error
                 $this->command->error("Gagal Import '$nama': " . $e->getMessage());
            }
        }
        fclose($handle);
        $this->command->info("SELESAI: Berhasil import $count data.");
    }

    // --- FUNGSI PENYELAMAT TAHUN (ANTI 2026) ---
    private function parseDateManual($dateString) {
        $dateString = trim($dateString);
        if (empty($dateString)) return now();

        // Kamus Bulan (Inggris & Indonesia -> Angka)
        $bulanMap = [
            'January' => '01', 'February' => '02', 'March' => '03', 'April' => '04', 'May' => '05', 'June' => '06',
            'July' => '07', 'August' => '08', 'September' => '09', 'October' => '10', 'November' => '11', 'December' => '12',
            'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'Mei' => '05', 'Juni' => '06', 
            'Juli' => '07', 'Agustus' => '08', 'Oktober' => '10', 'Nopember' => '11', 'Desember' => '12'
        ];

        // Ganti nama bulan jadi angka. Contoh: "01 January 2021" -> "01 01 2021"
        $formattedString = str_ireplace(array_keys($bulanMap), array_values($bulanMap), $dateString);

        try {
            // Kita paksa baca format "Hari Bulan Tahun"
            // Kalau CSV anda formatnya "2021" (ada tahunnya), ini akan sukses.
            return Carbon::createFromFormat('d m Y', $formattedString);
        } catch (\Exception $e) {
            // Kalau gagal baca, JANGAN kembali ke now() (2026).
            // Kita coba tebak tahunnya dari string, kalau ada 2021, paksa 2021.
            if (strpos($dateString, '2021') !== false) {
                 return Carbon::create(2021, 1, 1);
            }
            if (strpos($dateString, '2022') !== false) {
                 return Carbon::create(2022, 1, 1);
            }
            
            // Kalau parah banget gak bisa dibaca, terpaksa now(), tapi harusnya kode di atas sudah handle.
            return now();
        }
    }
}