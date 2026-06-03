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
        Schema::table('atlets', function (Blueprint $table) {
            // Menambahkan kolom foto setelah jenis kelamin
            // nullable() artinya boleh kosong (tidak wajib upload)
            $table->string('foto_profil')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atlets', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};