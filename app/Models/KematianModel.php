<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KematianModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'tanggal_kematian',
        'tempat_kematian',
    ];

    public function warga()
    {
        return $this->belongsTo(WargaModel::class, 'nik', 'nik');
    }
}
