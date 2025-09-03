<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuKeluargaModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;
use App\Models\PendatangModel;
use App\Models\PerpindahanModel;
use App\Models\WargaModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        // Statistik per desa
        $statistikBulanIni = DB::table('warga')
            ->join('kartu_keluarga', 'warga.kartu_keluarga_id', '=', 'kartu_keluarga.id') 
            ->select('kartu_keluarga.desa', DB::raw('COUNT(*) as total'))
            ->whereMonth('warga.created_at', Carbon::now()->month)
            ->whereYear('warga.created_at', Carbon::now()->year)
            ->groupBy('kartu_keluarga.desa')
            ->pluck('total', 'kartu_keluarga.desa');

        $statistikBulanLalu = DB::table('warga')
            ->join('kartu_keluarga', 'warga.kartu_keluarga_id', '=', 'kartu_keluarga.id') 
            ->select('kartu_keluarga.desa', DB::raw('COUNT(*) as total'))
            ->whereMonth('warga.created_at', Carbon::now()->subMonth()->month)
            ->whereYear('warga.created_at', Carbon::now()->subMonth()->year)
            ->groupBy('kartu_keluarga.desa')
            ->pluck('total', 'kartu_keluarga.desa');

        // Statistik gender
        $jumlahLaki = WargaModel::where('jenis_kelamin', 'L')->count();
        $jumlahPerempuan = WargaModel::where('jenis_kelamin', 'P')->count();

        $dataGender = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'counts' => [$jumlahLaki, $jumlahPerempuan]
        ];

        // Statistik usia
        $dataUsia = [
            'labels' => ['0-5', '6-12', '13-17', '18-25', '26-40', '41-60', '60+'],
            'counts' => [
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 0 AND 5')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 6 AND 12')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 13 AND 17')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 25')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 26 AND 40')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 41 AND 60')->count(),
                WargaModel::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 60')->count(),
            ]
        ];

        // Statistik kependudukan
        $jumlahWargaTetap   = WargaModel::where('status_kependudukkan', 'Warga')->count();
        $jumlahPendatang    = WargaModel::where('status_kependudukkan', 'Pendatang')->count();
        $jumlahKematian     = WargaModel::where('status_kependudukkan', 'Kematian')->count();
        $jumlahPerpindahan  = WargaModel::where('status_kependudukkan', 'Perpindahan')->count();
        $jumlahKelahiran    = KelahiranModel::count();
        $jumlahKartuKeluarga = KartuKeluargaModel::count();

        // Rumus total warga
        $jumlahWarga = ($jumlahWargaTetap + $jumlahPendatang + $jumlahKelahiran);

        return view('user.beranda', [
            'jumlahWarga' => $jumlahWarga,
            'jumlahPendatang' => $jumlahPendatang,
            'jumlahPerpindahan' => $jumlahPerpindahan,
            'jumlahKelahiran' => $jumlahKelahiran,
            'jumlahKematian' => $jumlahKematian,
            'jumlahKartuKeluarga' => $jumlahKartuKeluarga,
            'statistikBulanIni' => $statistikBulanIni,
            'statistikBulanLalu' => $statistikBulanLalu,
            'dataGender' => $dataGender,
            'dataUsia' => $dataUsia,
        ]);
    }
}
