<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->text('link_gmaps')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn('link_gmaps');
        });
    }
};