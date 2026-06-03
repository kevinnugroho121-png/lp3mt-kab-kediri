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
            // Kolom untuk menyimpan path/nama file PDF
            $table->string('file_ijop')->nullable()->after('ijop'); // Disimpan setelah kolom status ijop
            $table->string('file_super')->nullable()->after('file_ijop'); 
        });
    }

    public function down()
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn(['file_ijop', 'file_super']);
        });
    }
};
