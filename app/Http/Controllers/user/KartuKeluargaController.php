<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KartuKeluargaModel;
use TCPDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KartuKeluargaController extends Controller
{
     public function index(Request $request)
    {
        $query = KartuKeluargaModel::with('kepalaKeluarga');

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

        $kartu_keluarga = $query->paginate(10);

        return view('user.kartukeluarga.index', compact('kartu_keluarga'));
    }

    public function exportPdfWithKOP()
    {
        $dataKartuKeluarga = KartuKeluargaModel::with(['kepalaKeluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Kartu Keluarga');
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
        $pdf->Cell(0, 10, 'LAPORAN DATA KARTU KELUARGA', 0, 1, 'C');
        $pdf->Ln(2);

        // Judul Kolom
        $pdf->SetFont('helvetica', 'B', 7);

        $headers = ['No', 'No KK', 'Kepala Keluarga', 'Alamat', 'Desa'];

        // Data dalam array
        $rows = [];
        foreach ($dataKartuKeluarga as $kk) {
            $rows[] = [
                '',
                $kk->no_kk ?? '-',
                $kk->kepalaKeluarga->nama ?? '-',
                $kk->alamat ?? '-',
                $kk->desa ?? '-',                
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

        // Hitung total lebar tabel
        $totalTableWidth = array_sum($colWidths);
        // Hitung posisi X supaya tabel di tengah
        $startX = ($pdf->getPageWidth() - $totalTableWidth) / 2;

        // Cetak header tabel
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetX($startX);
        foreach ($headers as $i => $header) {
            $pdf->Cell($colWidths[$i], 8, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Cetak data
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

        $pdf->Output('laporan_data_KartuKeluarga.pdf', 'I');
    }

    public function exportExcelWithKOP()
    {
        $kks = KartuKeluargaModel::with('warga')->get();

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
        $sheet->mergeCells('A1:F1')->setCellValue('A1', 'PEMERINTAH KABUPATEN LAMPUNG TIMUR');
        $sheet->mergeCells('A2:F2')->setCellValue('A2', 'KECAMATAN BANDAR SRIBHAWONO');
        $sheet->mergeCells('A3:F3')->setCellValue('A3', 'Jl. Ir. Sutami Km 58 Sribhawono, Kode Pos 34199');
        $sheet->mergeCells('A5:F5')->setCellValue('A5', 'LAPORAN DATA KARTU KELUARGA');

        for ($i = 1; $i <= 5; $i++) {
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A$i")->getFont()->setBold(true)->setSize(12);
        }

        // Header tabel 
        $header = ['No', 'No KK', 'Kepala Keluarga', 'Jumlah Anggota', 'Alamat', 'Desa'];
        $sheet->fromArray($header, NULL, 'A7');

        // Style header tabel
        $sheet->getStyle('A7:F7')->getFont()->setBold(true);
        $sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data tabel 
        $row = 8;
        $no = 1;
        foreach ($kks as $kk) {
            // Cari kepala keluarga (relasi ke WargaModel)
            $kepalaKeluarga = $kk->warga->where('hubungan_dalam_keluarga', 'Kepala Keluarga')->first();
            $namaKepala = $kepalaKeluarga->nama ?? '-';

            // Hitung jumlah anggota
            $jumlahAnggota = $kk->warga->count();

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $kk->no_kk, DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $namaKepala);
            $sheet->setCellValue("D{$row}", $jumlahAnggota);
            $sheet->setCellValue("E{$row}", $kk->alamat);
            $sheet->setCellValue("F{$row}", $kk->desa);
            $row++;
        }

        // Border tabel 
        $sheet->getStyle("A7:F" . ($row - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Lebar kolom otomatis 
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tanda tangan
        $ttdRow = $row + 2;
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $sheet->mergeCells("E$ttdRow:F$ttdRow")->setCellValue("E$ttdRow", 'Bandar Sribhawono, ' . $tanggal);
        $sheet->mergeCells("E" . ($ttdRow + 1) . ":F" . ($ttdRow + 1))->setCellValue("E" . ($ttdRow + 1), 'Sekretaris Camat Bandar Sribhawono');
        $sheet->mergeCells("E" . ($ttdRow + 5) . ":F" . ($ttdRow + 5))->setCellValue("E" . ($ttdRow + 5), 'Aliando, S.E., M.M');
        $sheet->mergeCells("E" . ($ttdRow + 6) . ":F" . ($ttdRow + 6))->setCellValue("E" . ($ttdRow + 6), 'NIP. 198411062010011009');

        // Bold tanda tangan
        $sheet->getStyle("E$ttdRow:E" . ($ttdRow + 6))->getFont()->setBold(true);

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Data_Kartu_Keluarga.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

}
