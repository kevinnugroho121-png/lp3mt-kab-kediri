<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->string('foto_lembaga')->nullable()->after('file_skam');
            $table->string('foto_nambor')->nullable()->after('foto_lembaga');
            $table->string('foto_bangunan')->nullable()->after('foto_nambor');
            $table->string('foto_kbm')->nullable()->after('foto_bangunan');
        });
    }

    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn(['foto_lembaga', 'foto_nambor', 'foto_bangunan', 'foto_kbm']);
        });
    }
};