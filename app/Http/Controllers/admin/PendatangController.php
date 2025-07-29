<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendatangModel;
use Illuminate\Http\Request;

class PendatangController extends Controller
{
    public function index()
    {
        $pendatangs = PendatangModel::all();
        return view('admin.pendatang.index', compact('pendatangs'));
    }

    public function create()
    {
        return view('admin.pendatang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:wargas,nik|unique:pendatang,nik',
            'nama' => 'required|string',
            'alamat_lama' => 'required|string',
            'alamat_baru' => 'required|string',
            'tanggal_datang' => 'required|date',
        ]);

        PendatangModel::create($request->all());
        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pendatang = PendatangModel::findOrFail($id);
        return view('admin.pendatang.edit', compact('pendatang'));
    }

    public function update(Request $request, $id)
    {
        $pendatang = PendatangModel::findOrFail($id);

        $request->validate([
            'nik' => 'required|exists:wargas,nik|unique:pendatang,nik,' . $pendatang->id,
            'nama' => 'required|string',
            'alamat_lama' => 'required|string',
            'alamat_baru' => 'required|string',
            'tanggal_datang' => 'required|date',
        ]);

        $pendatang->update($request->all());
        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PendatangModel::findOrFail($id)->delete();
        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil dihapus.');
    }
}
