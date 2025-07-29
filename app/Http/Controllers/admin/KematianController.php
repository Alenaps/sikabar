<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KematianModel;
use App\Models\WargaModel;
use Illuminate\Http\Request;

class KematianController extends Controller
{
    public function index()
    {
        $kematians = KematianModel::with('warga')->latest()->get();
        return view('admin.kematian.index', compact('kematians'));
    }

    public function create()
    {
        $wargas = WargaModel::all();
        return view('admin.kematian.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:wargas,nik|unique:kematian,nik',
            'tanggal_meninggal' => 'required|date',
            'penyebab' => 'required|string|max:255',
            'tempat_meninggal' => 'required|string|max:255',
        ]);

        KematianModel::create($request->all());

        return redirect()->route('admin.kematian.index')->with('success', 'Data kematian berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kematian = KematianModel::with('warga')->findOrFail($id);
        return view('admin.kematian.show', compact('kematian'));
    }

    public function edit($id)
    {
        $kematian = KematianModel::findOrFail($id);
        $wargas = WargaModel::all();
        return view('admin.kematian.edit', compact('kematian', 'wargas'));
    }

    public function update(Request $request, $id)
    {
        $kematian = KematianModel::findOrFail($id);

        $request->validate([
            'nik' => 'required|exists:wargas,nik|unique:kematian,nik,' . $kematian->id,
            'tanggal_meninggal' => 'required|date',
            'penyebab' => 'required|string|max:255',
            'tempat_meninggal' => 'required|string|max:255',
        ]);

        $kematian->update($request->all());

        return redirect()->route('admin.kematian.index')->with('success', 'Data kematian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kematian = KematianModel::findOrFail($id);
        $kematian->delete();

        return redirect()->route('admin.kematian.index')->with('success', 'Data kematian berhasil dihapus.');
    }
}
