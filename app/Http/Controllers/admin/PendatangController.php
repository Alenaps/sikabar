<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendatangModel;
use App\Models\WargaModel;

class PendatangController extends Controller
{
    public function index(Request $request)
    {
         $pendatang = PendatangModel::with('warga') 
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10);

        return view('admin.pendatang.index', compact('pendatang'));
    }

    public function create()
    {
        $wargas = WargaModel::all();
        return view('admin.pendatang.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:warga,nik',
            'alamat_lama' => 'required',
            'tanggal_datang' => 'required|date',
        ]);

        $warga = WargaModel::where('nik', $request->nik)->first();

        pendatangModel::create([
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'alamat_lama' => $request->alamat_lama,
            'tanggal_datang' => $request->tanggal_datang,
        ]);

        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pendatang = PendatangModel::findOrFail($id);
        $wargas = WargaModel::all();
        return view('admin.pendatang.edit', compact('pendatang', 'wargas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|exists:warga,nik',
            'alamat_lama' => 'required',
            'tanggal_datang' => 'required|date',
        ]);

        $warga = WargaModel::where('nik', $request->nik)->first();

        $pendatang = PendatangModel::findOrFail($id);
        $pendatang->update([
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'alamat_lama' => $request->alamat_lama,
            'tanggal_datang' => $request->tanggal_datang,
        ]);

        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendatang = PendatangModel::findOrFail($id);
        $pendatang->delete();

        return redirect()->back()->with('success', 'Data pendatang berhasil dihapus.');
    }
}