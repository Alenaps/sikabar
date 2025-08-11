<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WargaModel;
use Illuminate\Http\Request;
use TCPDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class WargaController extends Controller
{
   public function index(Request $request)
    {
        $query = WargaModel::query();

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
                $q->whereMonth('tanggal_lahir', $request->bulan)
                ->orWhereHas('kematian', function ($q2) use ($request) {
                    $q2->whereMonth('tanggal_kematian', $request->bulan);
                })
                ->orWhereHas('perpindahan', function ($q3) use ($request) {
                    $q3->whereMonth('tanggal_pindah', $request->bulan);
                })
                ->orWhereHas('pendatang', function ($q4) use ($request) {
                    $q4->whereMonth('tanggal_datang', $request->bulan);
                });
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

        $wargas = $query->paginate(10);

        return view('user.warga.index', compact('wargas'));
    }

     public function exportPdfWithKOP()
    {
        $dataWarga = WargaModel::with(['kartu_keluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Warga');
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
        $pdf->Cell(0, 10, 'LAPORAN DATA WARGA', 0, 1, 'C');
        $pdf->Ln(2);

        // Judul Kolom
        $pdf->SetFont('helvetica', 'B', 7);

        $headers = [
            'No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir',
            'Tanggal Lahir', 'Agama','Status Kependudukan',
            'Hub. Keluarga', 'Desa'
        ];

        // Data dalam array
        $rows = [];
        foreach ($dataWarga as $w) {
            $rows[] = [
                '',
                $w->nik ?? '-',
                optional($w->kartu_keluarga)->no_kk ?? '-',
                $w->nama ?? '-',
                $w->jenis_kelamin ?? '-',
                $w->tempat_lahir ?? '-',
                $w->tanggal_lahir ? \Carbon\Carbon::parse($w->tanggal_lahir)->format('d-m-Y') : '-',
                $w->agama ?? '-',
                $w->status_kependudukkan ?? '-',
                $w->hubungan_dalam_keluarga ?? '-',
                optional($w->kartu_keluarga)->desa ?? '-',
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

        $pdf->Output('laporan_data_warga.pdf', 'I');
    }
    public function exportExcelWithKop()
    {
        $wargas = WargaModel::with('kartu_keluarga')->get();

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
        $sheet->mergeCells('A1:K1')->setCellValue('A1', 'PEMERINTAH KABUPATEN LAMPUNG TIMUR');
        $sheet->mergeCells('A2:K2')->setCellValue('A2', 'KECAMATAN BANDAR SRIBHAWONO');
        $sheet->mergeCells('A3:K3')->setCellValue('A3', 'Jl. Ir. Sutami Km 58 Sribhawono, Kode Pos 34199');
        $sheet->mergeCells('A5:K5')->setCellValue('A5', 'LAPORAN DATA WARGA');

        for ($i = 1; $i <= 5; $i++) {
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A$i")->getFont()->setBold(true)->setSize(12);
        }

        // Header tabel 
        $header = ['No', 'NIK', 'No KK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tgl Lahir', 'Agama', 'Status', 'Hubungan', 'Desa'];
        $sheet->fromArray($header, NULL, 'A7');

        // Style header tabel
        $sheet->getStyle('A7:K7')->getFont()->setBold(true);
        $sheet->getStyle('A7:K7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data tabel 
        $row = 8;
        $no = 1;
        foreach ($wargas as $w) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $w->nik, DataType::TYPE_STRING); // NIK sebagai string
            $sheet->setCellValueExplicit("C{$row}", $w->kartu_keluarga->no_kk ?? '-', DataType::TYPE_STRING); // No KK string
            $sheet->setCellValue("D{$row}", $w->nama);
            $sheet->setCellValue("E{$row}", $w->jenis_kelamin);
            $sheet->setCellValue("F{$row}", $w->tempat_lahir);
            $sheet->setCellValue("G{$row}", \Carbon\Carbon::parse($w->tanggal_lahir)->format('d-m-Y'));
            $sheet->setCellValue("H{$row}", $w->agama);
            $sheet->setCellValue("I{$row}", $w->status_kependudukkan);
            $sheet->setCellValue("J{$row}", $w->hubungan_dalam_keluarga);
            $sheet->setCellValue("K{$row}", $w->kartu_keluarga->desa ?? '-');
            $row++;
        }

        // Border tabel 
        $sheet->getStyle("A7:K" . ($row - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Lebar kolom otomatis 
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tanda tangan
        $ttdRow = $row + 2;
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $sheet->mergeCells("J$ttdRow:K$ttdRow")->setCellValue("J$ttdRow", 'Bandar Sribhawono, ' . $tanggal);
        $sheet->mergeCells("J" . ($ttdRow + 1) . ":K" . ($ttdRow + 1))->setCellValue("J" . ($ttdRow + 1), 'Sekretaris Camat Bandar Sribhawono');
        $sheet->mergeCells("J" . ($ttdRow + 4) . ":K" . ($ttdRow + 4))->setCellValue("J" . ($ttdRow + 4), 'Aliando, S.E., M.M');
        $sheet->mergeCells("J" . ($ttdRow + 5) . ":K" . ($ttdRow + 5))->setCellValue("J" . ($ttdRow + 5), 'NIP. 198411062010011009');

        // Bold tanda tangan
        $sheet->getStyle("J$ttdRow:J" . ($ttdRow + 5))->getFont()->setBold(true);

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Data_Warga.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }



}