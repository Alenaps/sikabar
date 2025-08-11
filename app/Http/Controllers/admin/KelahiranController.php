<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelahiranModel;
use App\Models\WargaModel;
use App\Models\KartuKeluargaModel;
use Illuminate\Http\Request;

class KelahiranController extends Controller
{
    public function index(Request $request)
    {
        $query = KelahiranModel::with(['kartu_keluarga', 'ayah', 'ibu']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_bayi', 'like', "%{$search}%")
                ->orWhere('no_kk', 'like', "%{$search}%");
        }

        $kelahiran = $query->latest()->paginate(10);
        return view('admin.kelahiran.index', compact('kelahiran'));
    }

    public function create()
    {
        $kartu_keluarga = KartuKeluargaModel::all();
        $wargas = WargaModel::with('kartu_keluarga')->get();
        $ayahs = WargaModel::where('jenis_kelamin', 'L')->get();
        $ibus = WargaModel::where('jenis_kelamin', 'P')->get();

        return view('admin.kelahiran.create', compact('kartu_keluarga', 'wargas', 'ayahs', 'ibus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:kartu_keluarga,no_kk',
            'nama_bayi' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'nik_ayah' => 'required',
            'nik_ibu' => 'required',
        ]);

        KelahiranModel::create($request->all());
        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil ditambahkan.');
    }

    public function edit($id)
    {
       $kelahiran = KelahiranModel::with(['ayah', 'ibu', 'kartu_keluarga'])->findOrFail($id);
        $wargas = WargaModel::all();
        $kartu_keluarga = KartuKeluargaModel::all();
        $ayahs = WargaModel::where('jenis_kelamin', 'L')->get();
        $ibus = WargaModel::where('jenis_kelamin', 'P')->get();
        return view('admin.kelahiran.edit', compact('kelahiran', 'wargas', 'kartu_keluarga', 'ayahs', 'ibus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_kk' => 'required|exists:kartu_keluarga,no_kk',
            'nama_bayi' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nik_ayah' => 'required',
            'nik_ibu' => 'required',
        ]);

        $kelahiran = KelahiranModel::findOrFail($id);
        $kelahiran->update([
                'no_kk' => $request->no_kk,
                'nama_bayi' => $request->nama_bayi,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nik_ayah' => $request->nik_ayah,
                'nik_ibu' => $request->nik_ibu,
            ]);


        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        KelahiranModel::findOrFail($id)->delete();
        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil dihapus.');
    }
}
