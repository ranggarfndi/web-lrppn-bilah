<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Kita butuh Hash

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus data lama (jika ada) - ini tidak akan berjalan jika kita pakai migrate:fresh
        // User::query()->delete(); // Hati-hati

        // 1. Buat 1 Akun Admin
        // Kita buat secara manual agar kita tahu pasti email & password-nya
        User::factory()->create([
            'name' => 'Admin LRPPN',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Password: "password"
            'role' => 'admin',
        ]);

        // 2. Buat 50 Akun Pasien (Dummy)
        // Factory kita akan otomatis membuat semua data riwayat
        // (Profil, Klasifikasi, 5 SOAP, 5 Likert) untuk setiap pasien.
        User::factory()->count(50)->create();

        // 3. Buat 1 Akun Pasien (Dummy) yang kita tahu login-nya
        User::factory()->create([
            'name' => 'Pasien Tes',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('password'), // Password: "password"
            'role' => 'pasien',
        ]);
    }
}