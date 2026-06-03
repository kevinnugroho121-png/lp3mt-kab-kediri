<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihs', function (Blueprint $table) {
            // Tambah kolom foto setelah no_hp, boleh kosong (nullable)
            $table->string('foto_profil')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('pelatihs', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};