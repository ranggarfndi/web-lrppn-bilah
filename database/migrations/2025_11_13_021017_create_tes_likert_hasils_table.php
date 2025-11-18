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
        Schema::create('tes_likert_hasil', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke pasien (user)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Mencatat siapa admin yang mengisi
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');

            // Data untuk chart
            $table->integer('total_skor'); // Skor total untuk digambar di grafik
            $table->integer('skor_maksimal'); // Skor maksimal tes (misal: 50)

            // Menyimpan semua jawaban (opsional tapi bagus)
            $table->json('jawaban_detail')->nullable(); 

            $table->timestamps(); // Tanggal tes diambil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_likert_hasils');
    }
};
