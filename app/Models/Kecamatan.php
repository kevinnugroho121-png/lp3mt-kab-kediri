<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kecamatan'];

    // PENTING: Nama fungsi ini harus 'desa' (singular/tunggal)
    // Agar cocok dengan kode Controller: withCount('desa')
    // Jika Mas ganti jadi 'desas', maka di Controller juga harus withCount('desas')
    public function desa()
    {
        return $this->hasMany(Desa::class);
    }

    // Relasi ke tabel users (Satu kecamatan bisa punya banyak user/korcam)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}