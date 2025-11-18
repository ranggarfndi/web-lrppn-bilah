<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_klasifikasi', function (Blueprint $table) {
        $table->id();

        // Menghubungkan ke tabel 'users'. Jika user dihapus, data ini ikut terhapus.
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->text('data_input_json'); // Menyimpan data (NAPZA, Lama Pakai)
        $table->string('prediksi_knn');
        $table->string('prediksi_nb');
        $table->string('rekomendasi_program');
        $table->text('catatan_sistem');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_klasifikasis');
    }
};
