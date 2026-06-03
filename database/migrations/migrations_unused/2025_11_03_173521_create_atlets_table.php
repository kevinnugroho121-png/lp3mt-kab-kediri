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
        Schema::create('atlets', function (Blueprint $table) {
            $table->id();
            
            // --- 1. RELASI KE USER ---
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); 

            // --- 2. BIODATA ---
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp_atlet')->nullable();
            
            // --- 3. DATA SEKOLAH (BARU DITAMBAHKAN) ---
            $table->string('jenjang_sekolah'); // Isinya nanti: SD, SMP, atau SMA
            $table->string('nama_sekolah');    // Isinya manual: SMAN 2 Kediri, dll

            // --- 4. DATA AKADEMI ---
            $table->string('kategori'); 
            $table->string('posisi')->nullable(); 
            $table->string('status')->default('Aktif'); 

            // --- 5. ORANG TUA ---
            $table->string('nama_orang_tua')->nullable();
            $table->string('no_hp_orang_tua')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atlets');
    }
};