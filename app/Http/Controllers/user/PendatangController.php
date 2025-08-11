<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendatangModel;
use TCPDF;
use Carbon\Carbon;

class PendatangController extends Controller
{
    public function index(Request $request)
    {
        $query = PendatangModel::with(['warga','kartu_keluarga','warga.kartu_keluarga']);

        // Pencarian
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->cari . '%')
                  ->orWhere('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('no_kk', 'like', '%' . $request->cari . '%');
            });
        }

        // Filter
        if ($request->filled('desa')) {
            $query->whereHas('kartu_keluarga', function ($q) use ($request) {
                $q->where('desa', $request->desa);
            });
        }

        if ($request->filled('bulan')) {
            $query->where(function ($q) use ($request) {
                $q->whereMonth('tanggal_datang', $request->bulan);
            });
        }


        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('usia_min')) {
            $query->having('usia', '>=', $request->usia_min);
        }

        if ($request->filled('usia_max')) {
            $query->having('usia', '<=', $request->usia_max);
        }

        $pendatang = $query->paginate(10);

        return view('user.pendatang.index', compact('pendatang'));
    }

    public function exportPdfWithKOP()
    {
        $dataPendatang = PendatangModel::with(['warga', 'kartu_keluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Pendatang');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Logo
        $pdf->Image(public_path('assets/img/kec.png'), 10, 10, 16);

        // Kop Surat
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 6, 'PEMERINTAH KABUPATEN LAMPUNG TIMUR', 0, 1, 'C');
        $pdf->Cell(0, 6, 'KECAMATAN BANDAR SRIBHAWONO', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 6, 'Jl. Ir. Sutami Km 58 Sribhawono, Kode Pos 34199', 0, 1, 'C');
        $pdf->Ln(5);

        // Garis
        $pdf->SetLineWidth(0.7);
        $pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $pdf->GetY() + 1.5, 287, $pdf->GetY() + 1.5);
        $pdf->Ln(4);

        // Judul Laporan
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'LAPORAN DATA PENDATANG', 0, 1, 'C');
        $pdf->Ln(2);

        // Tabel
        $pdf->SetFont('helvetica', 'B', 7);

        $headers = [
            'No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir',
            'Tanggal Lahir', 'Desa', 'Alamat Baru', 'Status Kependudukan',
            'Tanggal Datang', 'Alamat Lama'
        ];

        // Data dalam array
        $rows = [];
        foreach ($dataPendatang as $p) {
            $rows[] = [
                '',
                optional($p->warga)->nik ?? '-',
                optional(optional($p->warga)->kartu_keluarga)->no_kk ?? '-',
                optional($p->warga)->nama ?? '-',
                optional($p->warga)->jenis_kelamin ?? '-',
                optional($p->warga)->tempat_lahir ?? '-',
                optional($p->warga)->tanggal_lahir ? \Carbon\Carbon::parse(optional($p->warga)->tanggal_lahir)->format('d-m-Y') : '-',
                optional(optional($p->warga)->kartu_keluarga)->desa ?? '-',
                optional(optional($p->warga)->kartu_keluarga)->alamat ??'-',
                optional($p->warga)->status_kependudukkan ?? '-',
                $p->tanggal_datang ? \Carbon\Carbon::parse($p->tanggal_datang)->format('d-m-Y') : '-',
                $p->alamat_lama ?? '-'
            ];
        }

        // Hitung lebar tiap kolom
        $colWidths = [];
        foreach ($headers as $i => $header) {
            $maxWidth = $pdf->GetStringWidth($header) + 4;
            foreach ($rows as $row) {
                $cellWidth = $pdf->GetStringWidth($row[$i]) + 4;
                if ($cellWidth > $maxWidth) {
                    $maxWidth = $cellWidth;
                }
            }
            $colWidths[$i] = $maxWidth;
        }

        // Cetak header tabel
        $pdf->SetFillColor(220, 220, 220);
        foreach ($headers as $i => $header) {
            $pdf->Cell($colWidths[$i], 8, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Cetak data
        $pdf->SetFont('helvetica', '', 7);
        $no = 1;
        foreach ($rows as $row) {
            $row[0] = $no++;
            foreach ($row as $i => $cell) {
                $align = ($i == 0) ? 'C' : 'L';
                $pdf->Cell($colWidths[$i], 8, $cell, 1, 0, $align);
            }
            $pdf->Ln();
        }

        // Tanda tangan
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $pdf->Cell(0, 6, 'Bandar Sribhawono, ' . $tanggal, 0, 1, 'R');
        $pdf->Cell(0, 6, 'Sekretaris Camat Bandar Sribhawono', 0, 1, 'R');
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Cell(0, 6, 'Aliando, S.E., M.M', 0, 1, 'R');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'NIP. 198411062010011009', 0, 1, 'R');

        $pdf->Output('laporan_data_pendatang.pdf', 'I');
    }


}
