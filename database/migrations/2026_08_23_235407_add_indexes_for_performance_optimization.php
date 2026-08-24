<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan penambahan Index untuk optimasi pencarian data besar (20.000+ baris).
     */
    public function up(): void
    {
        // 1. Indexing pada tabel GURUS (Kolom yang sering difilter / dicari)
        Schema::table('gurus', function (Blueprint $table) {
            $table->index('penerima_insentif');
            $table->index('lembaga_id');
            $table->index('nik');
        });

        // 2. Indexing pada tabel LEMBAGAS (Kolom relasi & jenis)
        Schema::table('lembagas', function (Blueprint $table) {
            $table->index('kecamatan_id');
            $table->index('desa_id');
            $table->index('jenis_lembaga');
        });

        // 3. Indexing pada tabel DESAS (Relasi kecamatan)
        Schema::table('desas', function (Blueprint $table) {
            $table->index('kecamatan_id');
        });
    }

    /**
     * Rollback penambahan index jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropIndex(['penerima_insentif']);
            $table->dropIndex(['lembaga_id']);
            $table->dropIndex(['nik']);
        });

        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropIndex(['kecamatan_id']);
            $table->dropIndex(['desa_id']);
            $table->dropIndex(['jenis_lembaga']);
        });

        Schema::table('desas', function (Blueprint $table) {
            $table->dropIndex(['kecamatan_id']);
        });
    }
};