<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi (Mass Assignable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',            // Admin, Verifikator, Korcam
        'kecamatan_id',    // Wajib untuk Korcam
        'jabatan_korcam',  // <--- PENTING! (Ketua, Anggota 1, Anggota 2)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================================================
    // RELASI
    // ========================================================

    // 1. Relasi User ke Kecamatan (Korcam bertugas di 1 Kecamatan)
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    // Relasi 'desas' SAYA HAPUS karena fitur Petugas Desa & Checkbox Desa sudah ditiadakan.
    
    // ========================================================
    // HELPER / AKSESOR
    // ========================================================

    // Label Role biar rapi saat ditampilkan di Tabel User
    public function getRoleLabelAttribute()
    {
        // Sesuaikan dengan value di <option> pada Controller/View
        $roles = [
            'admin'       => 'Super Admin',
            'verifikator' => 'Verifikator Kabupaten',
            'korcam'      => 'Koordinator Kecamatan',
        ];

        // Jika role korcam, tambahkan detail jabatannya (Contoh: Koordinator Kecamatan - Ketua)
        if ($this->role == 'korcam' && $this->jabatan_korcam) {
            return $roles[$this->role] . ' (' . $this->jabatan_korcam . ')';
        }

        return $roles[$this->role] ?? 'User Umum';
    }
}