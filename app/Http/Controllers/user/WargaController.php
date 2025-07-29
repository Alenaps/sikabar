<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WargaModel;
use Illuminate\Http\Request;

class WargaController extends Controller
{
   public function index(Request $request)
    {
        $query = WargaModel::query();

        // Pencarian
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->cari . '%')
                  ->orWhere('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('no_kk', 'like', '%' . $request->cari . '%');
            });
        }

        // Filter
        if ($request->filled('desa')) {
            $query->where('desa', $request->desa);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('usia_min')) {
            $query->where('usia', '>=', $request->usia_min);
        }

        if ($request->filled('usia_max')) {
            $query->where('usia', '<=', $request->usia_max);
        }

        $wargas = $query->paginate(10);

        return view('user.warga.index', compact('wargas'));
    }

    public function export(Request $request)
    {
       //
    }
}