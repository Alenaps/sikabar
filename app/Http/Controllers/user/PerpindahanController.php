<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerpindahanModel;

class PerpindahanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpindahanModel::with(['warga.kartuKeluarga']);

        if ($request->filled('cari')) {
            $query->where('nik', 'like', '%' . $request->cari . '%')
                ->orWhere('nama', 'like', '%' . $request->cari . '%');
        }

        $data = $query->paginate(10);

        return view('user.perpindahan.index', compact('data'));
    }

}
