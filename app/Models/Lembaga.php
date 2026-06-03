<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    /**
     * Daftar kolom yang boleh diisi (Mass Assignment).
     * Pastikan semua kolom di Migration ada di sini.
     */
    protected $fillable = [
        // 1. Wilayah
        'kecamatan_id',
        'desa_id',

        // 2. Identitas
        'nama_lembaga',
        'jenis_lembaga',
        'nsbq',
        'ormas',
        'status',

        // 3. Detail & Kontak
        'alamat',
        'kepala_lembaga',
        'no_telp',
        'jumlah_santri',

        // 4. DATA GURU
        'jumlah_guru',
        'penerima_insentif',
        'belum_menerima_insentif',
        'jumlah_pns',
        'jumlah_pppk',
        'jumlah_sertifikasi',

        // 5. Legalitas Dasar
        'ijop',
        'masa_berlaku_ijop',
        'keterangan',
        
        // ===============================================
        // 6. DOKUMEN PDF & STATUS VERIFIKASI
        // ===============================================
        'file_ijop',
        'file_super',
        'file_skam', // <--- [BARU] File Surat Ket. Aktif Mengajar
        'status_ijop',
        'status_super',
        'status_skam', // <--- [BARU] Status Verifikasi SKAM
    ];

    /**
     * Casting tipe data otomatis.
     * 'masa_berlaku_ijop' akan otomatis jadi objek Tanggal (Carbon).
     * Ini PENTING agar Date Picker di menu Edit bisa membaca nilai tanggalnya.
     */
    protected $casts = [
        'masa_berlaku_ijop' => 'date',
    ];

    // ==========================================
    // RELASI DATABASE
    // ==========================================

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
}