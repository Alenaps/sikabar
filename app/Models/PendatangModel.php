<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendatangModel extends Model
{
    use HasFactory;

    // Nama tabel dalam database
    protected $table = 'pendatang';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nik',
        'tanggal_datang',
        'alamat_lama',
    ];

    // Relasi ke tabel warga
    public function warga()
    {
        return $this->belongsTo(WargaModel::class, 'nik', 'nik');
    }
}