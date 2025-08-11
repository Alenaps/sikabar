<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerpindahanModel;
use TCPDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PerpindahanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpindahanModel::with(['warga','kartu_keluarga','warga.kartu_keluarga']);

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
                $q->whereMonth('tanggal_pindah', $request->bulan);
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

        $perpindahan = $query->paginate(10);

        return view('user.perpindahan.index', compact('perpindahan'));
    }

    public function exportPdfWithKOP()
    {
        $dataPerpindahan = PerpindahanModel::with(['warga', 'kartu_keluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Perpindahan');
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

        // Garis Kop
        $pdf->SetLineWidth(0.7);
        $pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $pdf->GetY() + 1.5, 287, $pdf->GetY() + 1.5);
        $pdf->Ln(4);

        // Judul Laporan
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'LAPORAN DATA PERPINDAHAN', 0, 1, 'C');
        $pdf->Ln(2);

        // Header kolom
        $pdf->SetFont('helvetica', 'B', 7);
        $headers = [
            'No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir',
            'Tanggal Lahir', 'Desa', 'Alamat Lama', 'Status Kependudukan',
            'Tanggal Pindah', 'Alamat Baru'
        ];

        // Data isi tabel
        $rows = [];
        foreach ($dataPerpindahan as $p) {
            $rows[] = [
                '',
                optional($p->warga)->nik ?? '-',
                optional(optional($p->warga)->kartu_keluarga)->no_kk ?? '-',
                optional($p->warga)->nama ?? '-',
                optional($p->warga)->jenis_kelamin ?? '-',
                optional($p->warga)->tempat_lahir ?? '-',
                optional($p->warga)->tanggal_lahir 
                    ? \Carbon\Carbon::parse(optional($p->warga)->tanggal_lahir)->format('d-m-Y') 
                    : '-',
                optional(optional($p->warga)->kartu_keluarga)->desa ?? '-',
                optional(optional($p->warga)->kartu_keluarga)->alamat ?? '-',
                optional($p->warga)->status_kependudukkan ?? '-',
                $p->tanggal_pindah 
                    ? \Carbon\Carbon::parse($p->tanggal_pindah)->format('d-m-Y') 
                    : '-',
                $p->alamat_baru ?? '-'
            ];
        }

        // Hitung lebar kolom otomatis
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

        // Hitung posisi awal (biar tabel di tengah halaman)
        $totalWidth = array_sum($colWidths);
        $startX = ($pdf->getPageWidth() - $totalWidth) / 2;

        // Cetak header tabel
        $pdf->SetX($startX);
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
            $pdf->SetX($startX); // mulai dari posisi tengah
            foreach ($row as $i => $cell) {
                $align = ($i == 0) ? 'C' : 'L';
                $pdf->Cell($colWidths[$i], 8, $cell, 1, 0, $align);
            }
            $pdf->Ln();
        }

        // Tanda tangan
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Bandar Sribhawono, ' . \Carbon\Carbon::now()->format('d F Y'), 0, 1, 'R');
        $pdf->Cell(0, 6, 'Sekretaris Camat Bandar Sribhawono', 0, 1, 'R');
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', 'U', 10);
        $pdf->Cell(0, 6, 'Aliando, S.E., M.M', 0, 1, 'R');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'NIP. 198411062010011009', 0, 1, 'R');

        $pdf->Output('laporan_data_perpindahan.pdf', 'I');
    }

    
}
