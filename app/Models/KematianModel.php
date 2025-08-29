<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KematianModel extends Model
{
    use HasFactory;

    protected $table = 'kematian';

    protected $fillable = [
        'nik',
        'nama',
        'tanggal_kematian',
        'tempat_kematian',
    ];

    public function warga()
    {
        return $this->belongsTo(WargaModel::class, 'nik', 'nik'); 
    }

    public function kartu_keluarga()
    {
        return $this->belongsTo(KartuKeluargaModel::class, 'kartu_keluarga_id');
    }
}
