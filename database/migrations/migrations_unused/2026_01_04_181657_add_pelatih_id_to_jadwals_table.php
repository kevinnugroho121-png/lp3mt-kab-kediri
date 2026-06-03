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
        Schema::table('jadwals', function (Blueprint $table) {
            // Kita tambah kolom pelatih_id setelah kolom kategori
            // nullable() artinya boleh kosong (jika pelatih belum ditentukan)
            // constrained() artinya menyambung ke tabel 'pelatihs'
            // onDelete('set null') artinya jika pelatih dihapus, jadwalnya tidak hilang (cuma jadi kosong pelatihnya)
            $table->foreignId('pelatih_id')->nullable()->after('kategori')->constrained('pelatihs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['pelatih_id']);
            $table->dropColumn('pelatih_id');
        });
    }
};