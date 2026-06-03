<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa_user', function (Blueprint $table) {
            $table->id();
            
            // Hubungkan ke tabel users
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Hubungkan ke tabel desas
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            
            // Mencegah duplikasi (User A tidak bisa pegang Desa X dua kali)
            $table->unique(['user_id', 'desa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa_user');
    }
};