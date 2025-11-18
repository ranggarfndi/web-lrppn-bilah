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
        Schema::create('riwayat_status', function (Blueprint $table) {
            $table->id();

            // Pasien yang statusnya berubah
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Admin yang mencatat perubahan
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');

            // Data yang dicatat
            $table->string('status_baru'); // Misal: "Berat", "Membaik", "Stabil"
            $table->string('program_baru'); // Misal: "Rawat Inap", "Rawat Jalan"
            $table->text('faktor_penyebab'); // Alasan (Hasil AI, Tes Likert, Observasi Manual)

            $table->timestamps(); // Tanggal perubahan status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_statuses');
    }
};
