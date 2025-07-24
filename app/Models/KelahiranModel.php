<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelahiranModel extends Model
{
   use HasFactory;

    protected $table = 'kelahiran';

    protected $fillable = [
        'no_kk',
        'nama_bayi',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'nik_ayah',
        'nik_ibu',
    ];

    public function ayah()
    {
        return $this->belongsTo(WargaModel::class, 'nik_ayah', 'nik');
    }
    public function ibu()
    {
        return $this->belongsTo(WargaModel::class, 'nik_ayah', 'nik');
    }

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluargaModel::class, 'no_kk', 'no_kk');
    }

}
