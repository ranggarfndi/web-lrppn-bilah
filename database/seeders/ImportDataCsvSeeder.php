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
            
            // --- STRUKTUR KOLOM CSV (Sesuai Gambar Anda) ---
            // 0: No
            // 1: Inisial/Nama
            // 2: Tanggal
            // 3: Jenis Kelamin
            // 4: Alamat
            // 5: NAPZA
            // 6: Lama Penggunaan
            // 7: Riwayat Penyakit
            // 8: URICA Tes (Nilai 10, 16, dll)
            // 9: Tingkat Keparahan (Label: Sedang, Ringan) -> KITA PAKAI INI LANGSUNG

            if (count($row) < 10) { // Minimal ada 10 kolom
                $skipped++;
                continue;
            }

            $nama = trim($row[1]);
            if (empty($nama)) { $skipped++; continue; }

            $cleanName = preg_replace('/[^a-z0-9]/', '', strtolower($nama));
            $email = $cleanName . '.' . uniqid() . '@lrppn.com';
            $tanggalMasuk = $this->parseDate($row[2]);
            
            // Ambil Skor URICA dari Kolom I (Index 8)
            $uricaScore = isset($row[8]) && is_numeric($row[8]) ? (float)$row[8] : 0;

            // AMBIL HASIL DIAGNOSA LANGSUNG DARI CSV (Kolom J / Index 9)
            $hasilDiagnosaCSV = trim($row[9]); 
            $hasilDiagnosaCSV = ucwords(strtolower($hasilDiagnosaCSV)); // Rapikan huruf (sedang -> Sedang)

            // Tentukan Program berdasarkan teks di CSV
            $program = match ($hasilDiagnosaCSV) {
                'Berat', 'Sangat Berat' => 'Rawat Inap',
                'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
                'Ringan' => 'Rawat Jalan',
                default => 'Belum Ditentukan',
            };

            try {
                DB::transaction(function () use ($row, $nama, $email, $tanggalMasuk, $uricaScore, $hasilDiagnosaCSV, $program) {
                    
                    // 1. Buat User
                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make('password'),
                        'role' => 'pasien',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 2. Buat Profil
                    $user->profil()->create([
                        'alamat' => $row[4] ?? '-',
                        'nama_wali' => 'Data Import',
                        'no_telepon_wali' => '-',
                        'urica_score' => $uricaScore,
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 3. Simpan Hasil Klasifikasi (LANGSUNG DARI CSV)
                    // Kita simpan data input untuk arsip, tapi hasilnya kita paksa pakai data CSV
                    $inputJson = [
                        'jenis_napza' => $row[5],
                        'lama_penggunaan' => $row[6],
                        'jenis_kelamin' => $row[3],
                        'riwayat_penyakit' => $row[7],
                        'urica_score' => $uricaScore
                    ];

                    // Tambahkan data probabilitas palsu/kosong karena ini import manual
                    $inputJson['prediksi_nb'] = [
                        'tingkat_keparahan' => $hasilDiagnosaCSV,
                        'detail_probabilitas' => ['Manual' => 100] // Dummy data
                    ];

                    $user->hasilKlasifikasi()->create([
                        'data_input_json' => $inputJson,
                        'prediksi_knn' => '-', 
                        'prediksi_nb' => $hasilDiagnosaCSV, // <-- Pakai data kolom J
                        'rekomendasi_program' => $program,
                        'catatan_sistem' => 'Import CSV (Data Historis Asli)',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 4. Riwayat Status
                    $user->riwayatStatus()->create([
                        'admin_id' => 1,
                        'status_baru' => $hasilDiagnosaCSV, // <-- Pakai data kolom J
                        'program_baru' => $program,
                        'faktor_penyebab' => 'Data Historis (Dari File Excel)',
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