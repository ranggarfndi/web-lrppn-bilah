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
        Schema::create('catatan_soap', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke pasien (user)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Mencatat siapa admin yang mengisi
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');

            // Kolom untuk 4 field SOAP
            $table->text('subjective'); // Apa kata pasien
            $table->text('objective'); // Observasi staf
            $table->text('assessment'); // Penilaian staf
            $table->text('plan'); // Rencana ke depan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_soap');
    }
};
