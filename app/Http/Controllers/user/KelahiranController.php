<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelahiranModel;
use Illuminate\Support\Facades\DB;
use TCPDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class KelahiranController extends Controller
{
    public function index(Request $request)
    {
        $query = KelahiranModel::with(['ayah','ibu','kartu_keluarga']);

        // Pencarian
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_bayi', 'like', '%' . $request->cari . '%')
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
                $q->whereMonth('tanggal_lahir', $request->bulan);
            });
        }


        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $query->select('*', DB::raw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) as usia'));
        if ($request->filled('usia_min')) {
            $query->having('usia', '>=', $request->usia_min);
        }
        if ($request->filled('usia_max')) {
            $query->having('usia', '<=', $request->usia_max);
        }

        $kelahiran = $query->paginate(10);

        return view('user.kelahiran.index', compact('kelahiran'));
    }

    public function exportPdfWithKOP()
    {
        $dataKelahiran = KelahiranModel::with(['ayah', 'ibu', 'kartu_keluarga'])->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Data Kelahiran');
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
        $pdf->Cell(0, 10, 'LAPORAN DATA KELAHIRAN', 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', 'B', 7);

        $headers = [
            'No', 'No KK', 'Nama Bayi', 'Jenis Kelamin', 'Tempat Lahir',
            'Tanggal Lahir', 'NIK Ayah', 'NIK Ibu', 'Desa', 'Alamat',
        ];

        // Data array
        $rows = [];
        foreach ($dataKelahiran as $k) {
            $rows[] = [
                '',
                optional($k->kartu_keluarga)->no_kk ?? '-',
                $k->nama_bayi ?? '-',
                $k->jenis_kelamin ?? '-',
                $k->tempat_lahir ?? '-',
                $k->tanggal_lahir ? \Carbon\Carbon::parse($k->tanggal_lahir)->format('d-m-Y') : '-',
                $k->nik_ayah ?? '-',
                $k->nik_ibu ?? '-',
                optional($k->kartu_keluarga)->desa ?? '-',
                optional($k->kartu_keluarga)->alamat ?? '-',
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

        // Hitung total lebar tabel & posisi X supaya tabel di tengah
        $totalTableWidth = array_sum($colWidths);
        $startX = ($pdf->getPageWidth() - $totalTableWidth) / 2;

        // Header
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetX($startX);
        foreach ($headers as $i => $header) {
            $pdf->Cell($colWidths[$i], 8, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Data
        $pdf->SetFont('helvetica', '', 7);
        $no = 1;
        foreach ($rows as $row) {
            $row[0] = $no++;
            $pdf->SetX($startX);
            foreach ($row as $i => $cell) {
                $align = ($i == 0) ? 'C' : 'L';

                // Pakai MultiCell biar alamat ke wrap teks
                if ($i == 9) {
                    $xBefore = $pdf->GetX();
                    $yBefore = $pdf->GetY();
                    $pdf->MultiCell($colWidths[$i], 8, $cell, 1, $align, false, 0);
                    $pdf->SetXY($xBefore + $colWidths[$i], $yBefore);
                } else {
                    $pdf->Cell($colWidths[$i], 8, $cell, 1, 0, $align);
                }
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

        $pdf->Output('laporan_data_kelahiran.pdf', 'I');
    }

    public function exportExcelWithKOP()
    {
        $kelahiran = KelahiranModel::with(['ayah', 'ibu', 'kartu_keluarga'])->get();

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

        // Kop Surat
        $sheet->mergeCells('A1:J1')->setCellValue('A1', 'PEMERINTAH KABUPATEN LAMPUNG TIMUR');
        $sheet->mergeCells('A2:J2')->setCellValue('A2', 'KECAMATAN BANDAR SRIBHAWONO');
        $sheet->mergeCells('A3:J3')->setCellValue('A3', 'Jl. Ir. Sutami Km 58 Sribhawono, Kode Pos 34199');
        $sheet->mergeCells('A5:J5')->setCellValue('A5', 'LAPORAN DATA KELAHIRAN WARGA');

        for ($i = 1; $i <= 5; $i++) {
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A$i")->getFont()->setBold(true)->setSize(12);
        }

        // Header tabel
        $header = [
            'No', 'No KK', 'Nama Bayi', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'NIK Ayah',
            'NIK Ibu', 'Desa', 'Alamat'
        ];
        $sheet->fromArray($header, NULL, 'A7');

        // Style header
        $sheet->getStyle('A7:J7')->getFont()->setBold(true);
        $sheet->getStyle('A7:J7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data tabel
        $row = 8;
        $no = 1;
        foreach ($kelahiran as $k) {
            $kk = $k->kartu_keluarga ?? null;

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $kk->no_kk ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $k->nama_bayi);
            $sheet->setCellValue("D{$row}", $k->jenis_kelamin);
            $sheet->setCellValue("E{$row}", $k->tempat_lahir);
            $sheet->setCellValue("F{$row}", Carbon::parse($k->tanggal_lahir)->format('d-m-Y'));
            $sheet->setCellValueExplicit("G{$row}", $k->nik_ayah, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("H{$row}", $k->nik_ibu, DataType::TYPE_STRING);
            $sheet->setCellValue("I{$row}", $kk->desa ?? '-');
            $sheet->setCellValue("J{$row}", $kk->alamat ?? '-');
            $row++;
        }

        // Border
        $sheet->getStyle("A7:J" . ($row - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Auto width
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tanda tangan
        $ttdRow = $row + 2;
        Carbon::setLocale('id');
        $tanggal = Carbon::now()->translatedFormat('d F Y');
        $sheet->mergeCells("H$ttdRow:J$ttdRow")->setCellValue("H$ttdRow", 'Bandar Sribhawono, ' . $tanggal);
        $sheet->mergeCells("H" . ($ttdRow + 1) . ":J" . ($ttdRow + 1))->setCellValue("H" . ($ttdRow + 1), 'Sekretaris Camat Bandar Sribhawono');
        $sheet->mergeCells("H" . ($ttdRow + 5) . ":J" . ($ttdRow + 5))->setCellValue("H" . ($ttdRow + 5), 'Aliando, S.E., M.M');
        $sheet->mergeCells("H" . ($ttdRow + 6) . ":J" . ($ttdRow + 6))->setCellValue("H" . ($ttdRow + 6), 'NIP. 198411062010011009');

        $sheet->getStyle("H$ttdRow:H" . ($ttdRow + 6))->getFont()->setBold(true);

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Data_Kelahiran.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

}
