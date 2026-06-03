<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gurus', function (Blueprint $table) {
            // 1. Identitas (Cek dulu sebelum buat)
            if (!Schema::hasColumn('gurus', 'nik')) {
                $table->string('nik', 16)->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('gurus', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('gurus', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('gurus', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('gurus', 'nama_ibu_kandung')) {
                $table->string('nama_ibu_kandung')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('gurus', 'agama')) {
                $table->string('agama')->nullable()->after('nama_ibu_kandung');
            }
            
            // 2. Kontak & Alamat
            if (!Schema::hasColumn('gurus', 'alamat_ktp')) {
                $table->text('alamat_ktp')->nullable()->after('agama'); 
            }
            if (!Schema::hasColumn('gurus', 'desa')) {
                $table->string('desa')->nullable()->after('alamat_ktp');
            }
            if (!Schema::hasColumn('gurus', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('desa');
            }
            if (!Schema::hasColumn('gurus', 'kabupaten')) {
                $table->string('kabupaten')->nullable()->after('kecamatan');
            }
            if (!Schema::hasColumn('gurus', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('kabupaten');
            }
            
            // 3. Bank & Jenis
            if (!Schema::hasColumn('gurus', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('gurus', 'jenis_guru')) {
                $table->string('jenis_guru')->default('MADIN')->after('lembaga_id'); 
            }

            // 4. File Dokumen
            if (!Schema::hasColumn('gurus', 'file_ktp')) $table->string('file_ktp')->nullable();
            if (!Schema::hasColumn('gurus', 'file_kk')) $table->string('file_kk')->nullable();
            if (!Schema::hasColumn('gurus', 'file_bukurekening')) $table->string('file_bukurekening')->nullable();

            // 5. Status Verifikasi
            if (!Schema::hasColumn('gurus', 'status_ktp')) $table->string('status_ktp')->default('Pending');
            if (!Schema::hasColumn('gurus', 'status_kk')) $table->string('status_kk')->default('Pending');
            if (!Schema::hasColumn('gurus', 'status_bukurekening')) $table->string('status_bukurekening')->default('Pending');
            
            if (!Schema::hasColumn('gurus', 'keterangan')) $table->text('keterangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $columns = [
                'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
                'nama_ibu_kandung', 'agama', 'alamat_ktp', 'desa', 'kecamatan', 
                'kabupaten', 'no_hp', 'nomor_rekening', 'jenis_guru',
                'file_ktp', 'file_kk', 'file_bukurekening',
                'status_ktp', 'status_kk', 'status_bukurekening', 'keterangan'
            ];
            
            foreach ($columns as $col) {
                if (Schema::hasColumn('gurus', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};