<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuKeluargaModel;
use App\Models\WargaModel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;




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
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required',
            'status_kependudukkan' => 'required',
            'hubungan_dalam_keluarga' => 'required|string|max:50',
        ]);

        WargaModel::create($validated);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan');
    }


    public function edit(WargaModel $warga)
    {
        $kartu_keluarga = KartuKeluargaModel::all();
        return view('admin.warga.edit', compact('warga', 'kartu_keluarga'));
    }



    public function update(Request $request, WargaModel $warga)
    {
        $validated = $request->validate([
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
        
       // Jika nik berubah, update manual
        if ($request->nik !== $warga->nik) {
            // Simpan perubahan manual
            $warga->nik = $request->nik;
        }

        $warga->fill($validated)->save();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(WargaModel $warga)
    {
        $warga->delete();
        return back()->with('success', 'Data warga berhasil dihapus.');
    }

    public function import(Request $request)
{
    $file = $request->file('file');
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    foreach ($rows as $index => $row) {
        if ($index == 1) continue; // skip header

        $nik       = $row['A'];
        $nama      = $row['B'];
        $jk        = $row['C'];
        $tempat    = $row['D'];
        $tgl_lahir = $row['E']; // tanggal lahir
        $agama     = $row['F'];
        $status    = $row['G'];
        $hubungan  = $row['H'];
        $no_kk     = $row['I'];

        if (!$no_kk) {
            continue;
        }

        // --- Handle tanggal lahir ---
        $tanggal_lahir = null;
        if ($tgl_lahir) {
            try {
                if (is_numeric($tgl_lahir)) {
                    // Format Excel numeric (serial date)
                    $tanggal_lahir = Date::excelToDateTimeObject($tgl_lahir)->format('Y-m-d');
                } else {
                    // Hilangkan spasi
                    $tgl_lahir = trim($tgl_lahir);

                    // Coba parse dengan beberapa format
                    $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'd M Y'];
                    foreach ($formats as $format) {
                        try {
                            $tanggal_lahir = Carbon::createFromFormat($format, $tgl_lahir)->format('Y-m-d');
                            break; // keluar loop kalau berhasil
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }

                // Validasi tahun biar masuk akal
                if ($tanggal_lahir) {
                    $year = Carbon::parse($tanggal_lahir)->year;
                    if ($year < 1900 || $year > 2100) {
                        $tanggal_lahir = null;
                    }
                }
            } catch (\Exception $e) {
                $tanggal_lahir = null;
            }
        }

        


        // --- Cari atau buat KK ---
        $kk = KartuKeluargaModel::firstOrCreate(
            ['no_kk' => $no_kk],
            ['alamat' => '-', 'desa' => '-']
        );

        // --- Insert / Update Warga ---
        WargaModel::updateOrCreate(
            ['nik' => $nik],
            [
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $tempat,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'status_kependudukkan' => $status,
                'hubungan_dalam_keluarga' => $hubungan,
                'kartu_keluarga_id' => $kk->id,
            ]
        );
    }

    return redirect()->back()->with('success', 'Import data berhasil!');
}

}
