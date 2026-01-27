<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. JALANKAN IMPORT CSV TERLEBIH DAHULU
        // Di sini terjadi proses Truncate (Pembersihan tabel)
        // Jadi biarkan dia membersihkan tabel dulu sebelum kita masukkan Admin.
        // $this->call(ImportDataCsvSeeder::class);

        // 2. BARU BUAT AKUN ADMIN (SETELAH BERSIH-BERSIH)
        User::create([
            'name' => 'Admin LRPPN',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        // Output info di terminal agar kita tahu Admin sudah dibuat
        $this->command->info('User Admin berhasil dibuat: admin@example.com');
    }
}