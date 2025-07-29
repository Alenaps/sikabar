<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuKeluargaModel;
use App\Models\WargaModel;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $query = KartuKeluargaModel::query();
        $query = KartuKeluargaModel::with('anggota');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('desa', 'like', "%{$search}%");
            });
        }

        $kartu_keluarga = $query->latest()->paginate(10);
        return view('admin.kartukeluarga.index', compact('kartu_keluarga'));
    }

    public function create()
    {
        return view('admin.kartukeluarga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|unique:kartu_keluarga,no_kk',
            'alamat' => 'required|string',
            'desa' => 'required|string',
        ]);

        KartuKeluargaModel::create($request->all());
        return redirect()->route('admin.kartukeluarga.index')->with('success', 'Data KK berhasil ditambahkan.');
    }

    public function show($no_kk)
    {
        $kk = KartuKeluargaModel::with('anggota')->where('no_kk', $no_kk)->firstOrFail();
        return view('admin.kartukeluarga.show', compact('kk'));
    }


    public function edit($id)
    {
        $kartu_keluarga = KartuKeluargaModel::findOrFail($id);
        return view('admin.kartukeluarga.edit', compact('kartu_keluarga'));
    }

    public function update(Request $request, KartuKeluargaModel $kartukeluarga)
    {
        $request->validate([
            'no_kk' => 'required|string|max:255|unique:kartu_keluarga,no_kk,' . $kartukeluarga->id,
            'alamat' => 'required|string',
            'desa' => 'required|string',
        ]);

        $kartukeluarga->update([
            'no_kk' => $request->no_kk,
            'alamat' => $request->alamat,
            'desa' => $request->desa,
        ]);

        return redirect()->route('admin.kartukeluarga.index')->with('success', 'Data berhasil diperbarui');
    }


    public function destroy($id)
    {
        $kk = KartuKeluargaModel::findOrFail($id);

        // Hapus relasi warga terlebih dahulu
        if ($kk->anggota()->count() > 0) {
            $kk->anggota()->delete();
        }

        $kk->delete();

        return redirect()->route('admin.kartukeluarga.index')
            ->with('success', 'Data KK berhasil dihapus.');
    }


    // Remove boot method from controller; move deletion logic to the model

    public function anggota()
    {
        return $this->hasMany(WargaModel::class, 'kartu_keluarga_id');
    }

}
