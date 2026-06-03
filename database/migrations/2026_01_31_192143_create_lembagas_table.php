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
        Schema::create('lembagas', function (Blueprint $table) {
            $table->id();

            // 1. Relasi Wilayah (Wajib ada)
            $table->foreignId('kecamatan_id')
                  ->constrained('kecamatans')
                  ->cascadeOnDelete();
                  
            $table->foreignId('desa_id')
                  ->constrained('desas')
                  ->cascadeOnDelete();

            // 2. Identitas Lembaga
            $table->string('nama_lembaga');
            $table->string('jenis_lembaga'); // TPQ, MADIN, PONPES
            $table->string('nsbq')->nullable(); 
            $table->string('ormas')->nullable(); // NU, Muhammadiyah, dll
            $table->string('status')->default('AKTIF'); 

            // 3. Detail & Kontak
            $table->text('alamat')->nullable();
            $table->string('kepala_lembaga')->nullable(); 
            $table->string('no_telp')->nullable(); 
            $table->integer('jumlah_santri')->default(0);

            // ==========================================
            // 4. DATA GURU (INI YANG TADI KURANG)
            // ==========================================
            // Kolom ini wajib ada agar sesuai dengan Excel yang Mas kirim
            $table->integer('jumlah_guru')->default(0);
            $table->integer('penerima_insentif')->default(0);
            $table->integer('belum_menerima_insentif')->default(0);
            $table->integer('jumlah_pns')->default(0);
            $table->integer('jumlah_pppk')->default(0);
            $table->integer('jumlah_sertifikasi')->default(0);

            // 5. Legalitas (Ijin Operasional)
            $table->string('ijop')->default('TIDAK ADA'); 
            $table->date('masa_berlaku_ijop')->nullable(); 
            
            $table->text('keterangan')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembagas');
    }
};