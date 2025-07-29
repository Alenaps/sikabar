<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuKeluargaModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;
use App\Models\PendatangModel;
use App\Models\PerpindahanModel;
use App\Models\WargaModel;

class BerandaController extends Controller
{
    public function index()
    {
        $totalWarga = WargaModel::count();
        $totalPendatang = PendatangModel::count();
        $totalKelahiran = KelahiranModel::count();
        $totalKematian = KematianModel::count();
        $totalPerpindahan = PerpindahanModel::count();
        $totalKK = KartuKeluargaModel::count();

        return view('admin.beranda', compact(
            'totalWarga', 'totalPendatang', 'totalKelahiran',
            'totalKematian', 'totalPerpindahan', 'totalKK'
        ));
    }
}
