<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpindahanModel extends Model
{
    use HasFactory;
    
    protected $table = 'perpindahan';

    protected $fillable = [
        'nik',
        'nama',
        'alamat_baru',
        'tanggal_pindah',
    ];

    public function warga()
    {
        return $this->belongsTo(WargaModel::class, 'nik', 'nik');
    }
}
