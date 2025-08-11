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
        'nama',
        'no_kk',
        'alamat',
        'desa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_kependudukkan',
        'hubungan_dalam_keluarga',
        'kartu_keluarga_id',
              
    ];

    // Relasi dengan Kartu Keluarga
    public function kartu_keluarga()
    {
        return $this->belongsTo(KartuKeluargaModel::class, 'kartu_keluarga_id');
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

    // Relasi dengan Kelahiran 
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

    public function getRouteKeyName()
    {
        return 'nik';
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nik', 'like', "%$cari%")
                ->orWhere('nama', 'like', "%$cari%");
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_lahir', $request->bulan);
        }

        if ($request->filled('desa')) {
            $query->whereHas('kartu_keluarga', function ($q) use ($request) {
                $q->where('desa', $request->desa);
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('usia_min')) {
            $min = now()->subYears($request->usia_min)->startOfYear();
            $query->where('tanggal_lahir', '<=', $min);
        }

        if ($request->filled('usia_max')) {
            $max = now()->subYears($request->usia_max)->endOfYear();
            $query->where('tanggal_lahir', '>=', $max);
        }

        return $query;
    }

}


