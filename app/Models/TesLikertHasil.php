<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TesLikertHasil extends Model
{
    use HasFactory;
    protected $table = 'tes_likert_hasil';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        // 'user_id',
        'admin_id', 
        'total_skor',
        'skor_maksimal',
        'jawaban_detail',
    ];

    /**
     * Atribut yang harus di-cast.
     */
    protected $casts = [
        'jawaban_detail' => 'array', // Otomatis ubah JSON ke array
    ];

    /**
     * Hasil tes ini milik satu User (Pasien).
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hasil tes ini diinput oleh satu User (Admin).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}