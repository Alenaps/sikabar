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

class BerandaController extends Controller
{
   public function index()
{
    $statistikBulanIni = DB::table('warga')
        ->join('kartu_keluarga', 'warga.no_kk', '=', 'kartu_keluarga.no_kk')
        ->select('kartu_keluarga.desa', DB::raw('COUNT(*) as jumlah'))
        ->whereMonth('warga.created_at', now()->month)
        ->groupBy('kartu_keluarga.desa')
        ->pluck('jumlah', 'kartu_keluarga.desa');

    $statistikBulanLalu = DB::table('warga')
        ->join('kartu_keluarga', 'warga.no_kk', '=', 'kartu_keluarga.no_kk')
        ->select('kartu_keluarga.desa', DB::raw('COUNT(*) as jumlah'))
        ->whereMonth('warga.created_at', now()->subMonth()->month)
        ->groupBy('kartu_keluarga.desa')
        ->pluck('jumlah', 'kartu_keluarga.desa');

    $jumlahWarga = WargaModel::count();
    $jumlahPendatang = PendatangModel::count();
    $jumlahPerpindahan = PerpindahanModel::count();
    $jumlahKelahiran = KelahiranModel::count();
    $jumlahKematian = KematianModel::count();
    $jumlahKartuKeluarga = KartuKeluargaModel::count();

    return view('user.beranda', [
        'statistikBulanIni' => $statistikBulanIni,
        'statistikBulanLalu' => $statistikBulanLalu,
        'jumlahWarga' => $jumlahWarga,
        'jumlahPendatang' => $jumlahPendatang,
        'jumlahPerpindahan' => $jumlahPerpindahan,
        'jumlahKelahiran' => $jumlahKelahiran,
        'jumlahKematian' => $jumlahKematian,
        'jumlahKartuKeluarga' => $jumlahKartuKeluarga,
    ]);
}




    public function lihatData(Request $request)
{
    $query = WargaModel::query();

    // Filter opsional
    if ($request->filled('bulan')) {
        $query->whereMonth('created_at', $request->bulan);
    }
    if ($request->filled('desa')) {
        $query->where('desa', $request->desa);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('jenis_kelamin')) {
        $query->where('jenis_kelamin', $request->jenis_kelamin);
    }
    if ($request->filled('usia')) {
        $query->where('usia', $request->usia); 
    }

    $warga = $query->get();

    return view('user.lihatdata', compact('warga'));
}

}
