<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelatih extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 
        'nama_lengkap',
        'tanggal_lahir',
        'no_hp',
        'status',
        'foto_profil', // <--- TAMBAHAN BARU (Supaya foto bisa disimpan)
    ];

    // Relasi ke User (Supaya bisa ambil Email login)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}