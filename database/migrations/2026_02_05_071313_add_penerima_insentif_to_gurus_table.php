<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('gurus', function (Blueprint $table) {
            // 0 = Tidak Dapat, 1 = Dapat Insentif
            $table->boolean('penerima_insentif')->default(0)->after('status_sertifikasi');
        });
    }

    public function down()
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('penerima_insentif');
        });
    }
};
