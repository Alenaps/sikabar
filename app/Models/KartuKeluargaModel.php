<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuKeluargaModel extends Model
{
     use HasFactory;

     protected $table = 'kartu_keluarga';
     public $incrementing = false;
     protected $keyType = 'string';

    protected $fillable = [
        'no_kk',
        'alamat',
        'desa',
       
    ];

    public function warga()
    {
        return $this->hasMany(WargaModel::class, 'kartu_keluarga_id', 'id');
    }
    public function kelahiran()
    {
        return $this->hasMany(KelahiranModel::class, 'kartu_keluarga_id', 'id');
    }
    
    public function anggota()
    {
        return $this->hasMany(WargaModel::class, 'kartu_keluarga_id', 'id');
    }

}

