<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    // Izinkan pengisian kolom ini secara otomatis
    protected $fillable = ['judul', 'foto_path'];
}