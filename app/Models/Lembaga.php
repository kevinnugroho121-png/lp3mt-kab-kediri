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
        'link_gmaps',
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
        'file_skd', // [BARU - Poin 1]
        'file_super',
        'file_skam', 
        'status_ijop',
        'status_skd', // [BARU - Poin 1]
        'status_super',
        'status_skam', 
        
        // ===============================================
        // 📸 [BARU - FASE 3] KOLOM FOTO DOKUMENTASI LEMBAGA
        // ===============================================
        'foto_lembaga',
        'foto_nambor',
        'foto_bangunan',
        'foto_kbm',
    ];

    /**
     * Casting tipe data otomatis.
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

    // ==========================================
    // [BARU - Poin 7] FUNGSI PENGHITUNG REAL-TIME GURU & INSENTIF
    // ==========================================
    public function getHitungTotalGuruAttribute()
    {
        return $this->gurus()->count();
    }

    public function getHitungGuruDiajukanAttribute()
    {
        // Menghitung jumlah guru di lembaga ini yang status insentifnya '1' (Diajukan)
        return $this->gurus()->where('penerima_insentif', 1)->count();
    }

    public function getHitungGuruTidakDiajukanAttribute()
    {
        // Sisanya: Total Guru dikurangi yang diajukan
        return $this->hitung_total_guru - $this->hitung_guru_diajukan;
    }

    // ==========================================
    // ACCESSOR STATUS BERKAS
    // ==========================================
    public function getStatusBerkasAttribute()
    {
        // [DIUBAH - Poin 1] Toleransi kelonggaran. Kalau file IJOP kosong TAPI SKD diisi, tidak apa-apa (tidak bermasalah).
        if ((empty($this->file_ijop) && empty($this->file_skd)) || empty($this->file_super) || empty($this->file_skam) ||
            $this->status_ijop == 'Ditolak' || $this->status_skd == 'Ditolak' || $this->status_super == 'Ditolak' || $this->status_skam == 'Ditolak') {
            return 'bermasalah';
        }

        if ($this->status_ijop == 'Pending' || $this->status_skd == 'Pending' || $this->status_super == 'Pending' || $this->status_skam == 'Pending') {
            return 'pending';
        }

        return 'lengkap';
    }
}