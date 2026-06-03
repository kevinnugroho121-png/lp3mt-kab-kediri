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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();

            // 1. RELASI KE LEMBAGA (Wajib Ada)
            $table->foreignId('lembaga_id')
                  ->constrained('lembagas')
                  ->cascadeOnDelete();

            // 2. IDENTITAS PRIBADI
            // Nama kolom disesuaikan dengan Controller ($request->nama_lengkap)
            $table->string('nama_lengkap'); 
            
            // NIK harus unik agar tidak ada data ganda
            $table->string('nik')->unique(); 
            
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('nama_ibu')->nullable(); // Sesuai Controller

            // 3. KONTAK & ALAMAT
            $table->text('alamat')->nullable();   // Alamat KTP
            $table->string('desa')->nullable();   // Domisili
            $table->string('kecamatan')->nullable();
            $table->string('no_hp')->nullable();  // Sesuai Controller

            // 4. DATA BANK & LAINNYA
            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable(); // Opsional
            $table->text('keterangan')->nullable();
            
            // 5. FILE UPLOAD
            $table->string('file_ktp')->nullable();
            $table->string('file_ijazah')->nullable(); // Tambahan untuk arsip

            // 6. STATUS & SISTEM
            // status_insentif penting untuk Menu "Guru Insentif"
            $table->boolean('status_insentif')->default(false); 
            $table->boolean('status_aktif')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};