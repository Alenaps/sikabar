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

        
      // Hitung masing-masing kategori
        $jumlahWargaTetap   = WargaModel::count();
        $jumlahKelahiran    = KelahiranModel::count();
        $jumlahPendatang    = PendatangModel::count();
        $jumlahKematian     = KematianModel::count();
        $jumlahPerpindahan  = PerpindahanModel::count();
        $jumlahKartuKeluarga = KartuKeluargaModel::count();

        // Rumus: Warga tetap + kelahiran + pendatang - (kematian + perpindahan)
        $jumlahWarga = ($jumlahWargaTetap + $jumlahKelahiran + $jumlahPendatang) 
                    - ($jumlahKematian + $jumlahPerpindahan);

      
        return view('user.beranda', [
            'jumlahWarga' => $jumlahWarga,
            'jumlahPendatang' => $jumlahPendatang,
            'jumlahPerpindahan' => $jumlahPerpindahan,
            'jumlahKelahiran' => $jumlahKelahiran,
            'jumlahKematian' => $jumlahKematian,
            'jumlahKartuKeluarga' => $jumlahKartuKeluarga,
            'statistikBulanIni' => $statistikBulanIni,
            'statistikBulanLalu' => $statistikBulanLalu,
        ]);
    }
}