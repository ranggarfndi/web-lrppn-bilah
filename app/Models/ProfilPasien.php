<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilPasien extends Model
{
    use HasFactory;
    protected $table = 'profil_pasien';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        // 'user_id',
        'alamat',
        'tgl_lahir',
        'no_telepon_wali',
        'nama_wali',
        'urica_score',
    ];

    /**
     * Profil ini milik satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}