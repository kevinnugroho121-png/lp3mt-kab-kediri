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
            Schema::table('lembagas', function (Blueprint $table) {
                // Menambahkan kolom status verifikasi
                // Kita taruh setelah file masing-masing biar rapi
                $table->string('status_ijop')->default('Pending')->after('file_ijop'); 
                $table->string('status_super')->default('Pending')->after('file_super');
            });
        }

        public function down()
        {
            Schema::table('lembagas', function (Blueprint $table) {
                $table->dropColumn(['status_ijop', 'status_super']);
            });
        }
};
