<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // KITA PAKAI GUARDED KOSONG
    // Artinya: Izinkan semua kolom (jadwal_id, atlet_id, status, nilai_dribbling, dll) diisi.
    // Ini lebih aman dan praktis daripada menulis satu-satu di $fillable.
    protected $guarded = [];

    // Relasi: Data Absen ini milik Jadwal mana?
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Relasi: Data Absen ini milik Atlet siapa?
    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }
}