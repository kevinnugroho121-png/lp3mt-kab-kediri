<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            // Menambahkan laci SKAM setelah kolom status_super
            $table->string('file_skam')->nullable()->after('status_super');
            $table->string('status_skam')->default('Pending')->nullable()->after('file_skam');
        });
    }

    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn(['file_skam', 'status_skam']);
        });
    }
};