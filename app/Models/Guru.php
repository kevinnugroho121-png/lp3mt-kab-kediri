<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    // Pastikan tabelnya benar
    protected $table = 'gurus';

    /**
     * The attributes that are mass assignable.
     * Pastikan semua kolom baru dari Migration ada di sini.
     */
    protected $fillable = [
        // 1. Identitas Utama
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin', // L atau P
        'nama_ibu_kandung',
        'agama',
        'status_kepegawaian',
        'status_sertifikasi',
        'penerima_insentif', // <--- [PENTING] SUDAH DITAMBAHKAN

        // 2. Kontak & Wilayah
        'alamat_ktp',
        'desa',
        'kecamatan',
        'kabupaten',
        'no_hp',

        // 3. Data Bank & Lembaga
        'nomor_rekening',
        'lembaga_id',
        'jenis_guru', // MADIN, TPQ, PONPES

        // 4. File Dokumen (PDF)
        'file_ktp',
        'file_kk',
        'file_bukurekening',

        // 5. Status Verifikasi Dokumen
        'status_ktp',
        'status_kk',
        'status_bukurekening',

        // 6. Lainnya
        'keterangan',
    ];

    /**
     * Agar tanggal lahir otomatis jadi objek Carbon (bisa diformat tanggalnya).
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        // 'penerima_insentif' => 'boolean', // Opsional: Agar otomatis jadi true/false
    ];

    // ==========================================
    // RELASI
    // ==========================================
    
    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    // [BARU] Accessor untuk mengecek status keseluruhan berkas Guru
    public function getStatusBerkasAttribute()
    {
        if (empty($this->file_ktp) || empty($this->file_kk) || empty($this->file_bukurekening) ||
            $this->status_ktp == 'Ditolak' || $this->status_kk == 'Ditolak' || $this->status_bukurekening == 'Ditolak') {
            return 'bermasalah';
        }

        if ($this->status_ktp == 'Pending' || $this->status_kk == 'Pending' || $this->status_bukurekening == 'Pending') {
            return 'pending';
        }

        return 'lengkap';
    }
}