<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->integer('jumlah_santri_l')->default(0)->after('jumlah_santri');
            $table->integer('jumlah_santri_p')->default(0)->after('jumlah_santri_l');
        });
    }

    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn(['jumlah_santri_l', 'jumlah_santri_p']);
        });
    }
};