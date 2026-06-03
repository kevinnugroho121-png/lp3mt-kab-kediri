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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            
            // PENTING: user_id ini adalah PENERIMA Notifikasi (Atlet/Pelatih)
            // Bukan Admin yang memposting.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('judul');      // Contoh: "Tagihan SPP Baru"
            $table->text('pesan');        // Dulu 'isi', kita ganti 'pesan' biar lebih cocok untuk notif singkat
            
            // TIPE: Untuk menentukan warna Pop-up
            // 'info' (Biru), 'tagihan' (Merah/Peringatan), 'sukses' (Hijau)
            $table->string('tipe')->default('info'); 
            
            // STATUS BACA: Kunci utama fitur Pop-up
            // Kalau false (0), akan muncul Pop-up. Kalau true (1), masuk history saja.
            $table->boolean('is_read')->default(false); 
            
            // LINK: Opsional, misal diklik arahnya ke halaman bayar
            $table->string('link')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};