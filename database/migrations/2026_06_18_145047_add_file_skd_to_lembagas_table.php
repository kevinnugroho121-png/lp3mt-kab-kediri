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
        Schema::table('lembagas', function (Blueprint $table) {
            // Menambahkan kolom untuk nama file SKD dan status verifikasinya
            $table->string('file_skd')->nullable()->after('file_ijop');
            $table->string('status_skd')->nullable()->default('Pending')->after('status_ijop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn(['file_skd', 'status_skd']);
        });
    }
};