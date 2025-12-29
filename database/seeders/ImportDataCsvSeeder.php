<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ImportDataCsvSeeder extends Seeder
{
    public function run(): void
    {
        $files = glob(database_path('csv/*.csv'));

        if (empty($files)) {
            $this->command->warn("Tidak ada file CSV ditemukan.");
            return;
        }

        foreach ($files as $file) {
            $this->command->info("Memproses file: " . basename($file));
            $delimiter = $this->detectDelimiter($file);
            $this->importFile($file, $delimiter);
        }
    }

    private function detectDelimiter($filePath)
    {
        $handle = fopen($filePath, "r");
        $firstLine = fgets($handle); 
        fclose($handle);
        return (strpos($firstLine, ';') !== false) ? ';' : ',';
    }

    private function importFile($filePath, $delimiter)
    {
        $handle = fopen($filePath, "r");
        if ($handle === false) return;

        fgetcsv($handle, 0, $delimiter); // Skip Header

        $count = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            
            // --- STRUKTUR KOLOM CSV (Update Single URICA) ---
            // 0: No
            // 1: Nama
            // 2: Tanggal Masuk
            // 3: Gender
            // 4: Alamat
            // 5: NAPZA
            // 6: Lama Penggunaan
            // 7: Riwayat Penyakit
            // 8: Tingkat Keparahan (Diagnosa Lama)
            // 9: SKOR URICA (Single Value) -> Kolom Baru

            if (count($row) < 9) {
                $skipped++;
                continue;
            }

            $nama = trim($row[1]);
            if (empty($nama)) { $skipped++; continue; }

            $cleanName = preg_replace('/[^a-z0-9]/', '', strtolower($nama));
            $email = $cleanName . '.' . uniqid() . '@lrppn.com';
            $tanggalMasuk = $this->parseDate($row[2]);

            // Ambil Skor URICA Tunggal dari kolom ke-9
            // Jika kosong, default 0
            $uricaScore = isset($row[9]) && is_numeric($row[9]) ? (float)$row[9] : 0;

            try {
                DB::transaction(function () use ($row, $nama, $email, $tanggalMasuk, $uricaScore) {
                    
                    // 1. Buat User
                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make('password'),
                        'role' => 'pasien',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 2. Buat Profil (Simpan Single Score)
                    $user->profil()->create([
                        'alamat' => $row[4] ?? '-',
                        'nama_wali' => 'Data Historis',
                        'no_telepon_wali' => '-',
                        'urica_score' => $uricaScore, // Masukkan ke kolom urica_score
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 3. Buat Hasil Klasifikasi
                    $keparahan = trim($row[8]); 
                    $keparahan = ucwords(strtolower($keparahan));

                    $program = match ($keparahan) {
                        'Berat', 'Sangat Berat' => 'Rawat Inap',
                        'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
                        'Ringan' => 'Rawat Jalan',
                        default => 'Belum Ditentukan',
                    };

                    $riwayatPenyakit = trim($row[7]);

                    // Simpan JSON Input dengan key 'urica_score'
                    $inputJson = [ 
                        'jenis_napza' => $row[5],
                        'lama_penggunaan' => $row[6],
                        'jenis_kelamin' => $row[3], 
                        'riwayat_penyakit' => $riwayatPenyakit,
                        'urica_score' => $uricaScore, 
                    ];

                    $user->hasilKlasifikasi()->create([
                        // [PERBAIKAN] Hapus json_encode(), kirim array $inputJson langsung
                        'data_input_json' => $inputJson, 
                        
                        'prediksi_knn' => $keparahan,
                        'prediksi_nb' => $keparahan,
                        'rekomendasi_program' => $program,
                        'catatan_sistem' => 'Import CSV (Single URICA Score).',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 4. Riwayat Status
                    $user->riwayatStatus()->create([
                        'admin_id' => 1,
                        'status_baru' => $keparahan,
                        'program_baru' => $program,
                        'faktor_penyebab' => 'Data Historis (Import CSV)',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                });
                $count++;
            } catch (\Exception $e) {
                $this->command->error("Gagal baris $count ($nama): " . $e->getMessage());
            }
        }

        fclose($handle);
        $this->command->info("Sukses import: $count data.");
    }

    private function parseDate($dateString)
    {
        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return now(); 
        }
    }
}