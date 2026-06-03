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
        Schema::create('pelatihs', function (Blueprint $table) {
            $table->id();
            
            // 1. RELASI KE AKUN LOGIN (WAJIB ADA)
            // Ini yang bikin error tadi, sekarang kita tambahkan:
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 2. Data Pribadi
            $table->string('nama_lengkap');
            
            // [BARU] Ditambahkan agar sesuai dengan Seeder
            $table->string('spesialisasi')->nullable(); 
            $table->text('alamat')->nullable(); 

            // 3. Opsional (Boleh Kosong)
            // Kita buat nullable dulu biar Seeder lancar
            $table->date('tanggal_lahir')->nullable(); 
            $table->string('no_hp')->nullable(); 
            
            // 4. Status
            $table->string('status')->default('Aktif'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihs');
    }
};