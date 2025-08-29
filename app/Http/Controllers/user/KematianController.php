<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KematianModel;
use TCPDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class kematianController extends Controller
{
    public function index(Request $request)
    {
        $query = KematianModel::with(['warga','kartu_keluarga','warga.kartu_keluarga']);

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
                $q->whereMonth('tanggal_kematian', $request->bulan);
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

        $kematian = $query->paginate(10);

        return view('user.kematian.index', compact('kematian'));
    }

    public function exportPdfWithKOP()
    {
        $dataKematian = KematianModel::with(['warga.kartu_keluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Kematian');
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
        $pdf->Cell(0, 10, 'LAPORAN DATA KEMATIAN', 0, 1, 'C');
        $pdf->Ln(2);

        // Header tabel
        $pdf->SetFont('helvetica', 'B', 7);
        $headers = [
            'No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir',
            'Tanggal Lahir', 'Desa', 'Alamat', 'Status Kependudukan',
            'Tanggal Kematian', 'Tempat Kematian'
        ];

        // Data
        $rows = [];
        foreach ($dataKematian as $k) {
            $rows[] = [
                '',
                $k->nik ?? '-',
                optional(optional($k->warga)->kartu_keluarga)->no_kk ?? '-',
                $k->nama ?? '-',
                optional($k->warga)->jenis_kelamin ?? '-',
                optional($k->warga)->tempat_lahir ?? '-',
                optional($k->warga)->tanggal_lahir 
                    ? \Carbon\Carbon::parse(optional($k->warga)->tanggal_lahir)->format('d-m-Y') 
                    : '-',
                optional(optional($k->warga)->kartu_keluarga)->desa ?? '-',
                optional(optional($k->warga)->kartu_keluarga)->alamat ?? '-',
                optional($k->warga)->status_kependudukkan ?? '-',
                $k->tanggal_kematian 
                    ? \Carbon\Carbon::parse($k->tanggal_kematian)->format('d-m-Y') 
                    : '-',
                $k->tempat_kematian ?? '-'
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

        // Hitung total lebar tabel dan posisi awal untuk center
        $totalTableWidth = array_sum($colWidths);
        $startX = ($pdf->getPageWidth() - $totalTableWidth) / 2;

        // Cetak header tabel di tengah
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetX($startX);
        foreach ($headers as $i => $header) {
            $pdf->Cell($colWidths[$i], 8, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Cetak data di tengah
        $pdf->SetFont('helvetica', '', 7);
        $no = 1;
        foreach ($rows as $row) {
            $row[0] = $no++;
            $pdf->SetX($startX);
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

        $pdf->Output('laporan_data_kematian.pdf', 'I');
    }

    public function exportExcelWithKop()
    {
        $kematian = KematianModel::with('warga.kartu_keluarga')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Logo 
        $logo = new Drawing();
        $logo->setName('Logo Kecamatan');
        $logo->setDescription('Logo');
        $logo->setPath(public_path('assets/img/kec.png')); 
        $logo->setHeight(73);
        $logo->setCoordinates('A1');
        $logo->setOffsetX(10);
        $logo->setOffsetY(5);
        $logo->setWorksheet($sheet);

        // Kop surat 
        $sheet->mergeCells('A1:L1')->setCellValue('A1', 'PEMERINTAH KABUPATEN LAMPUNG TIMUR');
        $sheet->mergeCells('A2:L2')->setCellValue('A2', 'KECAMATAN BANDAR SRIBHAWONO');
        $sheet->mergeCells('A3:L3')->setCellValue('A3', 'Jl. Ir. Sutami Km 58 Sribhawono, Kode Pos 34199');
        $sheet->mergeCells('A5:L5')->setCellValue('A5', 'LAPORAN DATA KEMATIAN');

        for ($i = 1; $i <= 5; $i++) {
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A$i")->getFont()->setBold(true)->setSize(12);
        }

        // Header tabel 
        $header = [
            'No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 
            'Tempat Lahir', 'Tanggal Lahir', 'Desa', 'Alamat', 
            'Status Kependudukan', 'Tanggal Kematian', 'Tempat Kematian'
        ];
        $sheet->fromArray($header, NULL, 'A7');

        // Style header tabel
        $sheet->getStyle('A7:L7')->getFont()->setBold(true);
        $sheet->getStyle('A7:L7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data tabel 
        $row = 8;
        $no = 1;
        foreach ($kematian as $k) {
            $w = $k->warga;
            $kk = $w->kartu_keluarga ?? null;

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $w->nik ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$row}", $kk->no_kk ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue("D{$row}", $w->nama ?? '-');
            $sheet->setCellValue("E{$row}", $w->jenis_kelamin ?? '-');
            $sheet->setCellValue("F{$row}", $w->tempat_lahir ?? '-');
            $sheet->setCellValue("G{$row}", $w->tanggal_lahir ? Carbon::parse($w->tanggal_lahir)->format('d-m-Y') : '-');
            $sheet->setCellValue("H{$row}", $kk->desa ?? '-');
            $sheet->setCellValue("I{$row}", $kk->alamat ?? '-');
            $sheet->setCellValue("J{$row}", $w->status_kependudukkan ?? '-');
            $sheet->setCellValue("K{$row}", $k->tanggal_kematian ? Carbon::parse($k->tanggal_kematian)->format('d-m-Y') : '-');
            $sheet->setCellValue("L{$row}", $k->tempat_kematian ?? '-');
            $row++;
        }

        // Border tabel 
        $sheet->getStyle("A7:L" . ($row - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Lebar kolom otomatis 
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tanda tangan
        $ttdRow = $row + 2;
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $sheet->mergeCells("J$ttdRow:L$ttdRow")->setCellValue("J$ttdRow", 'Bandar Sribhawono, ' . $tanggal);
        $sheet->mergeCells("J" . ($ttdRow + 1) . ":L" . ($ttdRow + 1))->setCellValue("J" . ($ttdRow + 1), 'Sekretaris Camat Bandar Sribhawono');
        $sheet->mergeCells("J" . ($ttdRow + 5) . ":L" . ($ttdRow + 5))->setCellValue("J" . ($ttdRow + 5), 'Aliando, S.E., M.M');
        $sheet->mergeCells("J" . ($ttdRow + 6) . ":L" . ($ttdRow + 6))->setCellValue("J" . ($ttdRow + 6), 'NIP. 198411062010011009');

        $sheet->getStyle("J$ttdRow:J" . ($ttdRow + 6))->getFont()->setBold(true);

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Data_Kematian.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

}
