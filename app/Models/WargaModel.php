<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WargaModel extends Model
{
     use HasFactory;

    protected $table = 'warga';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_kependudukkan',
              
    ];

    // Relasi dengan Kartu Keluarga
    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluargaModel::class, 'no_kk', 'no_kk');
    }

    // Relasi dengan Pendatang
    public function pendatang()
    {
        return $this->hasOne(PendatangModel::class, 'nik', 'nik');
    }

    // Relasi dengan Kematian
    public function kematian()
    {
        return $this->hasOne(KematianModel::class, 'nik', 'nik');
    }

    // Relasi dengan Kelahiran (jika relevan, tergantung struktur)
    public function kelahiranSebagaiAyah()
    {
        return $this->hasMany(KelahiranModel::class, 'nik_ayah', 'nik');
    }
     public function kelahiranSebagaiIbu()
    {
        return $this->hasMany(KelahiranModel::class, 'nik_ibu', 'nik');
    }

    // Relasi dengan Perpindahan
    public function perpindahan()
    {
        return $this->hasOne(PerpindahanModel::class, 'nik', 'nik');
    }
}

