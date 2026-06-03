<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasGuru extends Model
{
    protected $fillable = [
        'guru_nik',
        'file_ktp',
        'file_rekening',
        'file_lain'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nik', 'nik');
    }
}
