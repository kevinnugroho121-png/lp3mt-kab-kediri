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
            // Status Kepegawaian (PNS, PPPK, Non-ASN)
            if (!Schema::hasColumn('gurus', 'status_kepegawaian')) {
                $table->string('status_kepegawaian')->default('Non-ASN')->after('nik');
            }
            
            // Status Sertifikasi (Belum, Sertifikasi, Inpassing)
            if (!Schema::hasColumn('gurus', 'status_sertifikasi')) {
                $table->string('status_sertifikasi')->default('Belum')->after('status_kepegawaian');
            }
        });
    }

    public function down()
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['status_kepegawaian', 'status_sertifikasi']);
        });
    }
};
