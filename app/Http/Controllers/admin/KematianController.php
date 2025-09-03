<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KematianModel;
use App\Models\WargaModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KematianController extends Controller
{
    public function index(Request $request)
    {
        $query = KematianModel::with('warga');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nik', 'like', "%$search%")
                ->orWhereHas('warga', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                });
        }
        $data = KematianModel::all();
        $kematian = $query->latest()->paginate(10); 
        return view('admin.kematian.index', compact('kematian'));
    }

    public function create()
    {
        $wargas = WargaModel::all();
        return view('admin.kematian.create', compact('wargas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|exists:warga,nik',
            'tanggal_kematian' => 'required|date',
            'tempat_kematian' => 'required|string',
        ]);

        // Ambil data warga
        $warga = WargaModel::where('nik', $validated['nik'])->first();

        if ($warga) {
            // Simpan data kematian
            KematianModel::create([
                'nik' => $warga->nik,
                'nama' => $warga->nama,
                'tanggal_kematian' => $validated['tanggal_kematian'],
                'tempat_kematian' => $validated['tempat_kematian'],
            ]);

            // Update status kependudukan warga
            $warga->update([
                'status_kependudukkan' => 'Kematian'
            ]);

            return redirect()->route('admin.kematian.index')
                ->with('success', 'Data kematian berhasil ditambahkan, status warga diperbarui');
        }

        return back()->with('error', 'NIK tidak ditemukan di data warga');
    }

    public function edit($id)
    {
        $kematian = KematianModel::findOrFail($id);
        $wargas = WargaModel::all(); 
        return view('admin.kematian.edit', compact('kematian', 'wargas'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal_kematian' => 'required|date',
            'tempat_kematian' => 'required|string',
        ]);

        $kematian = KematianModel::findOrFail($id);
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
