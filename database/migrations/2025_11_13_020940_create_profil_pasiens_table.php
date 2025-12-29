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
        Schema::create('profil_pasien', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Tambahkan kolom data diri pasien
            $table->string('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telepon_wali')->nullable();
            $table->string('nama_wali')->nullable();
            $table->float('urica_score')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pasiens');
    }
};
