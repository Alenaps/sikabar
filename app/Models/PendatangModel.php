<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendatangModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'tanggal_datang',
        'alamat_lama',
    ];

    public function warga()
    {
        return $this->belongsTo(WargaModel::class, 'nik', 'nik');
    }
}

