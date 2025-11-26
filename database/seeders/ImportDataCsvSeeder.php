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
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua file .csv di folder database/csv
        $files = glob(database_path('csv/*.csv'));

        foreach ($files as $file) {
            $this->command->info("------------------------------------------------");
            $this->command->info("Memproses file: " . basename($file));
            
            // Deteksi Delimiter (Koma atau Titik Koma)
            $delimiter = $this->detectDelimiter($file);
            $this->command->warn("Delimiter terdeteksi: '{$delimiter}'");

            $this->importFile($file, $delimiter);
        }
    }

    private function detectDelimiter($filePath)
    {
        $handle = fopen($filePath, "r");
        $firstLine = fgets($handle); // Baca baris pertama
        fclose($handle);

        // Jika ada titik koma, kita asumsikan itu pemisahnya
        if (strpos($firstLine, ';') !== false) {
            return ';';
        }
        return ',';
    }

    private function importFile($filePath, $delimiter)
    {
        $handle = fopen($filePath, "r");
        if ($handle === false) return;

        // Lewati Header
        fgetcsv($handle, 2000, $delimiter);

        $count = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            
            // Debugging: Jika kolom kurang dari 8, beri tahu user
            if (count($row) < 8) {
                $skipped++;
                continue;
            }

            // Bersihkan data dari spasi berlebih
            $nama = trim($row[1]);
            
            // Skip jika nama kosong
            if (empty($nama)) {
                $skipped++;
                continue;
            }

            // Buat email dummy unik
            $email = strtolower(str_replace([' ', '.', ','], '', $nama)) . '_' . uniqid() . '@lrppn.com';
            
            // Parsing Tanggal
            $tanggalMasuk = $this->parseDate($row[2]);

            try {
                DB::transaction(function () use ($row, $nama, $email, $tanggalMasuk) {
                    
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
                        'nama_wali' => 'Data Historis',
                        'no_telepon_wali' => '-',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 3. Buat Hasil Klasifikasi
                    $keparahan = trim($row[7]);
                    
                    // Normalisasi keparahan (Huruf besar awal)
                    $keparahan = ucwords(strtolower($keparahan));

                    $program = match ($keparahan) {
                        'Berat', 'Sangat Berat' => 'Rawat Inap',
                        'Sedang' => 'Rehabilitasi Non-Medis (Sosial)',
                        'Ringan' => 'Rawat Jalan',
                        default => 'Belum Ditentukan',
                    };

                    $user->hasilKlasifikasi()->create([
                        'data_input_json' => json_encode([
                            'jenis_napza' => $row[5],
                            'lama_penggunaan' => $row[6],
                            'jenis_kelamin' => $row[3],
                        ]),
                        'prediksi_knn' => $keparahan,
                        'prediksi_nb' => $keparahan,
                        'rekomendasi_program' => $program,
                        'catatan_sistem' => 'Import data historis (CSV).',
                        'created_at' => $tanggalMasuk,
                        'updated_at' => $tanggalMasuk,
                    ]);

                    // 4. Buat Riwayat Status
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
                $this->command->error("Gagal: " . $nama . " - " . $e->getMessage());
            }
        }

        fclose($handle);
        $this->command->info("Sukses import: $count data. (Skipped: $skipped)");
    }

    private function parseDate($dateString)
    {
        try {
            // Coba format Excel standard (Y-m-d)
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            // Jika gagal, coba format Indonesia manual (dd Mei yyyy atau dd-mm-yyyy)
            return now(); 
        }
    }
}