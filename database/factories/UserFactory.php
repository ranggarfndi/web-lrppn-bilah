<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Impor 4 model baru kita
use App\Models\ProfilPasien;
use App\Models\HasilKlasifikasi;
use App\Models\CatatanSoap;
use App\Models\TesLikertHasil;
use App\Models\User; // Pastikan User juga di-impor

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Kata sandi default untuk semua user dummy.
     *
     * @var string
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // Password default: "password"
            'remember_token' => Str::random(10),
            'role' => 'pasien', // Buat 'pasien' sebagai default
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * === INI BAGIAN PENTINGNYA ===
     * Logika yang berjalan SETELAH satu user dibuat.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            
            // Hanya jalankan jika user yang dibuat adalah 'pasien'
            if ($user->role === 'pasien') {
                
                // 1. Buat 1 Profil Pasien untuk user ini
                ProfilPasien::factory()->create([
                    'user_id' => $user->id,
                ]);

                // 2. Buat 1 Hasil Klasifikasi Awal untuk user ini
                HasilKlasifikasi::factory()->create([
                    'user_id' => $user->id,
                ]);

                // 3. Buat 5 Catatan SOAP (dummy) untuk user ini
                CatatanSoap::factory()->count(5)->create([
                    'user_id' => $user->id,
                    'admin_id' => 1, // Kita asumsikan Admin punya ID 1
                ]);

                // 4. Buat 5 Tes Likert (dummy) untuk user ini
                // Kita buat dengan tanggal yang berbeda untuk data chart
                TesLikertHasil::factory()->create([
                    'user_id' => $user->id, 'admin_id' => 1, 'created_at' => now()->subDays(28)
                ]);
                TesLikertHasil::factory()->create([
                    'user_id' => $user->id, 'admin_id' => 1, 'created_at' => now()->subDays(21)
                ]);
                TesLikertHasil::factory()->create([
                    'user_id' => $user->id, 'admin_id' => 1, 'created_at' => now()->subDays(14)
                ]);
                TesLikertHasil::factory()->create([
                    'user_id' => $user->id, 'admin_id' => 1, 'created_at' => now()->subDays(7)
                ]);
                TesLikertHasil::factory()->create([
                    'user_id' => $user->id, 'admin_id' => 1, 'created_at' => now()
                ]);
            }
        });
    }

    /**
     * Fungsi state baru untuk membuat Admin
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}