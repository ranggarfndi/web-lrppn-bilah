<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanSoap extends Model
{
    use HasFactory;
    protected $table = 'catatan_soap';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        // 'user_id',
        'admin_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
    ];

    /**
     * Catatan ini milik satu User (Pasien).
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Catatan ini ditulis oleh satu User (Admin).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}