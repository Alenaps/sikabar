<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelahiranModel;
use App\Models\WargaModel;
use Illuminate\Http\Request;

class KelahiranController extends Controller
{
    public function index()
    {
        $kelahiran = KelahiranModel::with('warga')->get();
        return view('admin.kelahiran.index', compact('kelahiran'));
    }

    public function create()
    {
        $wargas = WargaModel::all();
        return view('admin.kelahiran.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:wargas,nik',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'nama_ibu' => 'required',
            'nama_ayah' => 'required',
        ]);

        KelahiranModel::create($request->all());
        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelahiran = KelahiranModel::findOrFail($id);
        $wargas = WargaModel::all();
        return view('admin.kelahiran.edit', compact('kelahiran', 'wargas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|exists:wargas,nik',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'nama_ibu' => 'required',
            'nama_ayah' => 'required',
        ]);

        $kelahiran = KelahiranModel::findOrFail($id);
        $kelahiran->update($request->all());

        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        KelahiranModel::findOrFail($id)->delete();
        return redirect()->route('admin.kelahiran.index')->with('success', 'Data kelahiran berhasil dihapus.');
    }
}
