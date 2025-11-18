<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan 'role' agar bisa diisi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- TAMBAHKAN RELASI DI BAWAH INI ---

    /**
     * Satu User (Pasien) memiliki satu Profil.
     */
    public function profil(): HasOne
    {
        return $this->hasOne(ProfilPasien::class);
    }

    /**
     * Satu User (Pasien) memiliki satu Hasil Klasifikasi Awal.
     */
    public function hasilKlasifikasi(): HasOne
    {
        return $this->hasOne(HasilKlasifikasi::class);
    }

    /**
     * Satu User (Pasien) memiliki BANYAK Catatan SOAP.
     */
    public function catatanSoap(): HasMany
    {
        return $this->hasMany(CatatanSoap::class);
    }

    /**
     * Satu User (Pasien) memiliki BANYAK Hasil Tes Likert.
     */
    public function tesLikertHasil(): HasMany
    {
        return $this->hasMany(TesLikertHasil::class);
    }

    /**
     * Satu User (Pasien) memiliki BANYAK Riwayat Status.
     */
    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class)->orderBy('created_at', 'desc');
    }
}