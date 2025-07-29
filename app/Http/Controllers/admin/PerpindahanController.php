<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerpindahanModel;
use App\Models\WargaModel;

class PerpindahanController extends Controller
{
    public function index()
    {
        $perpindahan = PerpindahanModel::with('warga')->latest()->paginate(10);
        return view('admin.perpindahan.index', compact('perpindahan'));
    }

    public function create()
    {
        $wargas = WargaModel::all();
        return view('admin.perpindahan.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:warga,nik',
            'alamat_baru' => 'required',
            'tanggal_pindah' => 'required|date',
        ]);

        $warga = WargaModel::where('nik', $request->nik)->first();

        PerpindahanModel::create([
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'alamat_baru' => $request->alamat_baru,
            'tanggal_pindah' => $request->tanggal_pindah,
        ]);

        return redirect()->route('admin.perpindahan.index')->with('success', 'Data perpindahan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $perpindahan = PerpindahanModel::findOrFail($id);
        $wargas = WargaModel::all();
        return view('admin.perpindahan.edit', compact('perpindahan', 'wargas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|exists:warga,nik',
            'alamat_baru' => 'required',
            'tanggal_pindah' => 'required|date',
        ]);

        $warga = WargaModel::where('nik', $request->nik)->first();

        $perpindahan = PerpindahanModel::findOrFail($id);
        $perpindahan->update([
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'alamat_baru' => $request->alamat_baru,
            'tanggal_pindah' => $request->tanggal_pindah,
        ]);

        return redirect()->route('admin.perpindahan.index')->with('success', 'Data perpindahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perpindahan = PerpindahanModel::findOrFail($id);
        $perpindahan->delete();

        return redirect()->back()->with('success', 'Data perpindahan berhasil dihapus.');
    }
}