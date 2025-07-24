<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuKeluargaModel extends Model
{
     use HasFactory;

    protected $fillable = [
        'no_kk',
        'hubungan_dalam_keluarga',
        'alamat',
        'desa',
       
    ];

    public function warga()
    {
        return $this->hasMany(WargaModel::class, 'no_kk', 'no_kk');
    }
    public function kelahiran()
    {
        return $this->hasMany(KelahiranModel::class, 'no_kk', 'no_kk');
    }
}

