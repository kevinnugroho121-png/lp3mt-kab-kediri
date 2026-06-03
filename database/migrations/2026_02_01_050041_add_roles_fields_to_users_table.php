<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            // 1. Cek dulu: Apakah kolom 'role' SUDAH ADA?
            // Kalau BELUM ADA, baru kita buat.
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('super_admin')->after('email');
            }

            // 2. Cek dulu: Apakah kolom 'kecamatan_id' SUDAH ADA?
            if (!Schema::hasColumn('users', 'kecamatan_id')) {
                $table->foreignId('kecamatan_id')->nullable()->after('email') // Taruh setelah email (atau role)
                      ->constrained('kecamatans')->nullOnDelete();
            }

            // 3. Cek dulu: Apakah kolom 'desa_id' SUDAH ADA?
            if (!Schema::hasColumn('users', 'desa_id')) {
                $table->foreignId('desa_id')->nullable()->after('kecamatan_id')
                      ->constrained('desas')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key dulu biar aman
            if (Schema::hasColumn('users', 'desa_id')) {
                $table->dropForeign(['desa_id']);
                $table->dropColumn('desa_id');
            }
            
            if (Schema::hasColumn('users', 'kecamatan_id')) {
                $table->dropForeign(['kecamatan_id']);
                $table->dropColumn('kecamatan_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};