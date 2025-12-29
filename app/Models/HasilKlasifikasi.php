<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKlasifikasi extends Model
{
    use HasFactory;

    // === TAMBAHKAN BARIS INI ===
    // Sesuaikan dengan nama tabel di database Anda (biasanya tanpa 's')
    protected $table = 'hasil_klasifikasi'; 
    // ============================

    protected $fillable = [
        'user_id',
        'data_input_json',
        'prediksi_knn',
        'prediksi_nb',
        'rekomendasi_program',
        'catatan_sistem',
    ];

    protected $casts = [
        'data_input_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}