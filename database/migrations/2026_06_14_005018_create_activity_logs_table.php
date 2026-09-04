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
     Schema::create('activity_logs', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('user_id')->nullable()->index(); // Tambah index agar filter korcam cepat
         $table->string('nama_user');
         $table->string('aksi');
         $table->text('target')->nullable(); // Ubah ke text agar muat nama file panjang/pesan error
         $table->timestamps();

         $table->index('created_at'); // Tambah index agar urutan waktu terbaru selalu instan
     });
 }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
