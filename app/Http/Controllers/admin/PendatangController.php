<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendatangModel;
use App\Models\WargaModel;
use App\Models\KartuKeluargaModel;

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
            // KK
            'no_kk' => 'required|string',
            'alamat' => 'required|string',
            'desa' => 'required|string',
            // Warga
            'nik' => 'required|unique:warga,nik',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'agama' => 'required|string',
            'hubungan_dalam_keluarga' => 'required|string',
            // Pendatang
            'alamat_lama' => 'required|string',
            'tanggal_datang' => 'required|date',
        ]);

        // 1. Simpan/Update KK
        $kk = KartuKeluargaModel::updateOrCreate(
            ['no_kk' => $request->no_kk],
            ['alamat' => $request->alamat, 'desa' => $request->desa]
        );

        // Cek apakah ID ada
        if (!$kk->id) {
            dd('KK tidak berhasil tersimpan', $kk);
        }

        // 2. Simpan Warga
        $warga = WargaModel::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'agama' => $request->agama,
            'hubungan_dalam_keluarga' => $request->hubungan_dalam_keluarga,
            'status_kependudukkan' => 'Pendatang',
            'kartu_keluarga_id' => $kk->id,
        ]);

        // 3. Simpan Pendatang
        PendatangModel::create([
            //'warga_id' => $warga->id,
            'nik' => $warga->nik,
            'alamat_lama' => $request->alamat_lama,
            'tanggal_datang' => $request->tanggal_datang,
        ]);

        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil ditambahkan.');
    }

    public function edit(PendatangModel $pendatang)
    {
        $pendatang->load('warga.kartu_keluarga');
        $kartuKeluarga = KartuKeluargaModel::all();

        return view('admin.pendatang.edit', compact('pendatang', 'kartuKeluarga'));
    }

    public function update(Request $request, PendatangModel $pendatang)
    {
        $request->validate([
            'alamat_lama' => 'required|string',
            'tanggal_datang' => 'required|date',
        ]);

        $pendatang->update($request->only('alamat_lama', 'tanggal_datang'));

        return redirect()->route('admin.pendatang.index')->with('success', 'Data pendatang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendatang = PendatangModel::findOrFail($id);
        $pendatang->delete();

        return redirect()->back()->with('success', 'Data pendatang berhasil dihapus.');
    }
}