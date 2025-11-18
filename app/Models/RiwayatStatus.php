<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatus extends Model
{
    use HasFactory;

    /** Tentukan nama tabel secara manual */
    protected $table = 'riwayat_status';

    /** Kolom yang boleh diisi */
    protected $fillable = [
        'user_id',
        'admin_id',
        'status_baru',
        'program_baru',
        'faktor_penyebab',
    ];

    /** Relasi: Riwayat ini milik satu Pasien (User) */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Relasi: Riwayat ini dicatat oleh satu Admin (User) */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}