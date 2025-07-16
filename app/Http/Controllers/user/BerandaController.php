<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendatang;
use App\Models\Perpindahan;
use App\Models\Kelahiran;
use App\Models\Kematian;
use App\Models\KartuKeluarga;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index()
    {
        $jumlahPendatang = Pendatang::count();
        $jumlahPerpindahan = Perpindahan::count();
        $jumlahKelahiran = Kelahiran::count();
        $jumlahKematian = Kematian::count();
        $jumlahKK = KartuKeluarga::count();
        $jumlahWarga = Warga::count();

        // Statistik jumlah warga per desa bulan ini dan bulan lalu
        $bulanIni = now()->format('m');
        $bulanLalu = now()->subMonth()->format('m');

        $statistikBulanIni = Warga::select('desa', DB::raw('count(*) as jumlah'))
            ->whereMonth('created_at', $bulanIni)
            ->groupBy('desa')
            ->get();

        $statistikBulanLalu = Warga::select('desa', DB::raw('count(*) as jumlah'))
            ->whereMonth('created_at', $bulanLalu)
            ->groupBy('desa')
            ->get();

        return view('user.beranda', compact(
            'jumlahPendatang',
            'jumlahPerpindahan',
            'jumlahKelahiran',
            'jumlahKematian',
            'jumlahKK',
            'jumlahWarga',
            'statistikBulanIni',
            'statistikBulanLalu'
        ));
    }
}
