<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuKeluargaModel;
use App\Models\WargaModel;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = WargaModel::with('kartu_keluarga');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('kartu_keluarga', function ($qq) use ($search) {
                      $qq->where('no_kk', 'like', "%{$search}%");
                  });
            });
        }

        $wargas = $query->latest()->paginate(10);
        return view('admin.warga.index', compact('wargas'));
    }

    public function create()
    {
        $kartu_keluarga = KartuKeluargaModel::all();
        return view('admin.warga.create', compact('kartu_keluarga'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:warga,nik',
            'kartu_keluarga_id' => 'required|exists:kartu_keluarga,id',
            'no_kk' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required',
            'status_kependudukkan' => 'required',
            'hubungan_dalam_keluarga' => 'nullable'
        ]);

        WargaModel::create($validated);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan');
    }


    public function edit($id)
    {
        $warga = WargaModel::findOrFail($id);
        $kartu_keluarga = KartuKeluargaModel::all();
        return view('admin.warga.edit', compact('warga', 'kartu_keluarga'));
    }



    public function update(Request $request, WargaModel $warga)
    {
        $request->validate([
            'nik' => 'required|string|unique:warga,nik,' . $warga->id,
            'kartu_keluarga_id' => 'required|exists:kartu_keluarga,id',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string',
            'status_kependudukkan' => 'required|string',
            'hubungan_dalam_keluarga' => 'required|string|max:50',
        ]);
        
        $warga->update($request->all());
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(WargaModel $warga)
    {
        $warga->delete();
        return back()->with('success', 'Data warga berhasil dihapus.');
    }
}
