<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilKlasifikasi extends Model
{
    use HasFactory;
    protected $table = 'hasil_klasifikasi';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        // 'user_id',
        'data_input_json',
        'prediksi_knn',
        'prediksi_nb',
        'rekomendasi_program',
        'catatan_sistem',
    ];

    protected $casts = [
        'data_input_json' => 'array',
    ];

    /**
     * Hasil ini milik satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}