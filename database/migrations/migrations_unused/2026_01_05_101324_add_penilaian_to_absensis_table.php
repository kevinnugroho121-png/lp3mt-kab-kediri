<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Kita tambahkan kolom penilaian skill (Bisa null jika pelatih belum isi)
            $table->integer('nilai_dribbling')->nullable()->after('status');
            $table->integer('nilai_passing')->nullable()->after('nilai_dribbling');
            $table->integer('nilai_shooting')->nullable()->after('nilai_passing');
            $table->integer('nilai_perilaku')->nullable()->comment('Termasuk IQ Basket & Attitude')->after('nilai_shooting');
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['nilai_dribbling', 'nilai_passing', 'nilai_shooting', 'nilai_perilaku']);
        });
    }
};