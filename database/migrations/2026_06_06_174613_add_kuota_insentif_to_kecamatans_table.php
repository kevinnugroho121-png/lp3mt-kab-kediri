<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            // Menambah kolom kuota_insentif, default 0 agar aman
            $table->integer('kuota_insentif')->default(0)->after('nama_kecamatan');
        });
    }

    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropColumn('kuota_insentif');
        });
    }
};