<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapBahanExport implements FromCollection, WithEvents
{
    protected $tanggal_id;

    public function __construct($id)
    {
        $this->tanggal_id = $id;
    }

    public function collection()
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $MAX_ROW = 10;
                $MAX_BLOCK_PER_ROW = 9;

                /*
                =============================
                HEADER
                =============================
                */

                $sheet->setCellValue('B1', 'PT Widodo Makmur Unggas Tbk.');
                $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(10);

                $sheet->mergeCells('A4:S4');
                $sheet->setCellValue('A4', 'TALLY BAHAN');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A5', 'Tanggal');
                $sheet->setCellValue('C5', ': ' . date('d-m-Y'));

                $sheet->setCellValue('P5', 'FORM');
                $sheet->setCellValue('Q5', ': TALLY BAHAN');

                $sheet->mergeCells('P1:Q1');
                $sheet->mergeCells('P2:Q2');
                $sheet->mergeCells('P3:Q3');
                $sheet->mergeCells('R1:S1');
                $sheet->mergeCells('R2:S2');
                $sheet->mergeCells('R3:S3');

                $sheet->setCellValue('P1', 'Kode Doc. :');
                $sheet->setCellValue('P2', 'Rev. :');
                $sheet->setCellValue('P3', 'Tgl Efektif :');

                $sheet->setCellValue('R1', 'PRD/SOP-02/FRM-03');
                $sheet->setCellValue('R2', '02');
                $sheet->setCellValue('R3', '14 Desember 2022');

                $sheet->getStyle("P1:S3")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /*
                =============================
                DATA
                =============================
                */

                $data = DB::table('timbangan_bahans')
                    ->join('bahans', 'bahans.id', '=', 'timbangan_bahans.bahan_id')
                    ->where('tanggal_bahan_id', $this->tanggal_id)
                    ->select(
                        'timbangan_bahans.*',
                        'bahans.kode'
                    )
                    ->orderBy('timbangan_bahans.id')
                    ->get();

                $chunks = $data->chunk($MAX_ROW);

                $startRow = 7;
                $blockIndex = 0;
                $maxRowGroup = 0;

                foreach ($chunks as $chunk) {

                    $rowGroup = floor($blockIndex / $MAX_BLOCK_PER_ROW);

                    if ($rowGroup > $maxRowGroup) {
                        $maxRowGroup = $rowGroup;
                    }

                    $colStart = 2 + ($blockIndex % $MAX_BLOCK_PER_ROW) * 2;
                    $rowStart = $startRow + ($rowGroup * 15);

                    $colNo = 1;
                    $colPcs = $colStart;
                    $colKg = $colStart + 1;

                    $sheet->getColumnDimension(
                        Coordinate::stringFromColumnIndex($colNo)
                    )->setWidth(4.67);

                    $sheet->getColumnDimension(
                        Coordinate::stringFromColumnIndex($colPcs)
                    )->setWidth(6.22);

                    $sheet->getColumnDimension(
                        Coordinate::stringFromColumnIndex($colKg)
                    )->setWidth(8.22);

                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colPcs))->setWidth(7);
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colKg))->setWidth(9);

                    /*
                    =============================
                    TITLE (TALLY)
                    =============================
                    */

                    $sheet->mergeCellsByColumnAndRow($colPcs, $rowStart, $colKg, $rowStart);
                    $sheet->setCellValueByColumnAndRow($colPcs, $rowStart, '');

                    $sheet->getStyleByColumnAndRow($colPcs, $rowStart, $colKg, $rowStart)
                        ->getAlignment()->setHorizontal('center');

                    /*
                    =============================
                    HEADER
                    =============================
                    */

                    $sheet->mergeCellsByColumnAndRow($colNo, $rowStart, $colNo, $rowStart + 2);
                    $sheet->setCellValueByColumnAndRow($colNo, $rowStart, 'NO');

                    $sheet->setCellValueByColumnAndRow($colPcs, $rowStart + 2, 'PCS');
                    $sheet->setCellValueByColumnAndRow($colKg, $rowStart + 2, 'KG');

                    $row = $rowStart + 3;
                    $no = 1;

                    foreach ($chunk as $t) {

                        $sheet->setCellValueByColumnAndRow($colNo, $row, $no);
                        $sheet->setCellValueByColumnAndRow($colPcs, $row, $t->pcs);
                        $sheet->setCellValueByColumnAndRow($colKg, $row, $t->berat);

                        /*
                        =============================
                        WARNA BERDASARKAN KODE
                        =============================
                        */

                        $kode = strtolower($t->kode ?? '');

                        $warna = match ($kode) {
                            'merah' => 'FFC7CE',
                            'hijau' => 'C6EFCE',
                            'kuning' => 'FFEB9C',
                            'biru' => 'BDD7EE',
                            'ungu' => 'D9D2E9',
                            'abu' => 'D9D9D9',
                            'orange' => 'FCE4D6',
                            'coklat' => 'EAD1DC',
                            default => null
                        };

                        if ($warna) {
                            $sheet->getStyleByColumnAndRow($colPcs, $row, $colKg, $row)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($warna);
                        }

                        $row++;
                        $no++;
                    }

                    /*
                    =============================
                    TOTAL
                    =============================
                    */

                    $sheet->setCellValueByColumnAndRow($colNo, $rowStart + 13, 'TOTAL');

                    $sheet->setCellValueByColumnAndRow(
                        $colPcs,
                        $rowStart + 13,
                        "=SUM(" .
                            Coordinate::stringFromColumnIndex($colPcs) . ($rowStart + 3) . ":" .
                            Coordinate::stringFromColumnIndex($colPcs) . ($rowStart + 12) . ")"
                    );

                    $sheet->setCellValueByColumnAndRow(
                        $colKg,
                        $rowStart + 13,
                        "=SUM(" .
                            Coordinate::stringFromColumnIndex($colKg) . ($rowStart + 3) . ":" .
                            Coordinate::stringFromColumnIndex($colKg) . ($rowStart + 12) . ")"
                    );

                    /*
                    BORDER
                    */

                    $sheet->getStyleByColumnAndRow(
                        $colNo,
                        $rowStart,
                        $colKg,
                        $rowStart + 13
                    )->getBorders()->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);

                    $sheet->getStyleByColumnAndRow(
                        $colNo,
                        $rowStart,
                        $colKg,
                        $rowStart + 13
                    )->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $sheet->getStyleByColumnAndRow(
                        $colKg,
                        $rowStart + 3,
                        $colKg,
                        $rowStart + 13
                    )->getNumberFormat()
                        ->setFormatCode('0.00');

                    $blockIndex++;
                }

                /*
=============================
TOTAL KESELURUHAN
=============================
*/

                $totalStartRow = $startRow + (($maxRowGroup + 1) * 15) + 1;

                $rekapTotal = DB::table('timbangan_bahans')
                    ->where('tanggal_bahan_id', $this->tanggal_id)
                    ->select(
                        DB::raw('SUM(pcs) as pcs'),
                        DB::raw('SUM(berat) as berat')
                    )->first();

                /*
TABLE TOTAL KESELURUHAN
*/

                $sheet->mergeCells("A$totalStartRow:B$totalStartRow");
                $sheet->setCellValue("A$totalStartRow", 'TOTAL');

                $sheet->setCellValue("C$totalStartRow", 'PCS');

                $sheet->mergeCells("D$totalStartRow:E$totalStartRow");
                $sheet->setCellValue("D$totalStartRow", 'KG');

                $sheet->getStyle("A$totalStartRow:E$totalStartRow")
                    ->getFont()->setBold(true);

                $rowTotal = $totalStartRow + 1;

                $sheet->mergeCells("A$rowTotal:B$rowTotal");
                $sheet->setCellValue("A$rowTotal", 'KESELURUHAN');

                $sheet->setCellValue("C$rowTotal", $rekapTotal->pcs);

                $sheet->mergeCells("D$rowTotal:E$rowTotal");
                $sheet->setCellValue("D$rowTotal", $rekapTotal->berat);

                /*
=============================
TOTAL PER BAHAN
=============================
*/

                $rekapBahan = DB::table('timbangan_bahans')
                    ->join('bahans', 'bahans.id', '=', 'timbangan_bahans.bahan_id')
                    ->where('tanggal_bahan_id', $this->tanggal_id)
                    ->select(
                        'bahans.nama',
                        'bahans.kode',
                        DB::raw('SUM(timbangan_bahans.pcs) as pcs'),
                        DB::raw('SUM(timbangan_bahans.berat) as berat')
                    )
                    ->groupBy('bahans.nama', 'bahans.kode')
                    ->get();

                /*
HEADER TOTAL BAHAN
*/

                $sheet->mergeCells("G$totalStartRow:J$totalStartRow");
                $sheet->setCellValue("G$totalStartRow", 'TOTAL PER BAHAN');

                $sheet->getStyle("G$totalStartRow:J$totalStartRow")
                    ->getFont()->setBold(true);

                $sheet->getStyle("G$totalStartRow:J$totalStartRow")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue("G" . ($totalStartRow + 1), 'BAHAN');
                $sheet->setCellValue("H" . ($totalStartRow + 1), 'PCS');
                $sheet->mergeCells("I" . ($totalStartRow + 1) . ":J" . ($totalStartRow + 1));
                $sheet->setCellValue("I" . ($totalStartRow + 1), 'KG');

                $sheet->getStyle("G" . ($totalStartRow + 1) . ":J" . ($totalStartRow + 1))
                    ->getFont()->setBold(true);

                $rowBahan = $totalStartRow + 2;

                foreach ($rekapBahan as $b) {

                    $sheet->setCellValue("G$rowBahan", $b->nama);
                    $sheet->setCellValue("H$rowBahan", $b->pcs);

                    $sheet->mergeCells("I$rowBahan:J$rowBahan");
                    $sheet->setCellValue("I$rowBahan", $b->berat);

                    $kode = strtolower($b->kode ?? '');

                    $warna = match ($kode) {
                        'merah' => 'FFC7CE',
                        'hijau' => 'C6EFCE',
                        'kuning' => 'FFEB9C',
                        'biru' => 'BDD7EE',
                        'ungu' => 'D9D2E9',
                        'abu' => 'D9D9D9',
                        'orange' => 'FCE4D6',
                        'coklat' => 'EAD1DC',
                        default => null
                    };

                    if ($warna) {

                        $sheet->getStyle("G$rowBahan:J$rowBahan")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($warna);
                    }

                    $rowBahan++;
                }

                /*
BORDER TOTAL
*/

                $sheet->getStyle("A$totalStartRow:E$rowTotal")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("G$totalStartRow:J" . ($rowBahan - 1))
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /*
CENTER
*/

                $sheet->getStyle("A$totalStartRow:E$rowTotal")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->getStyle("G$totalStartRow:J" . ($rowBahan - 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                /*
=============================
TTD
=============================
*/

                $ttdRow = $totalStartRow;

                $sheet->mergeCells("L$ttdRow:M$ttdRow");
                $sheet->mergeCells("N$ttdRow:O$ttdRow");
                $sheet->mergeCells("P$ttdRow:S$ttdRow");

                $sheet->setCellValue("L$ttdRow", 'Dibuat,');
                $sheet->setCellValue("N$ttdRow", 'Diterima,');
                $sheet->setCellValue("P$ttdRow", 'Mengetahui,');

                $sheet->mergeCells('L' . ($ttdRow + 4) . ':M' . ($ttdRow + 4));
                $sheet->setCellValue('L' . ($ttdRow + 4), auth()->user()->name);

                $sheet->mergeCells('L' . ($ttdRow + 5) . ':M' . ($ttdRow + 5));
                $sheet->mergeCells('N' . ($ttdRow + 5) . ':O' . ($ttdRow + 5));
                $sheet->mergeCells('P' . ($ttdRow + 5) . ':Q' . ($ttdRow + 5));
                $sheet->mergeCells('R' . ($ttdRow + 5) . ':S' . ($ttdRow + 5));

                $sheet->setCellValue('L' . ($ttdRow + 5), '(Tallyman)');
                $sheet->setCellValue('N' . ($ttdRow + 5), '(Admin Produksi)');
                $sheet->setCellValue('P' . ($ttdRow + 5), '(Leader Area)');
                $sheet->setCellValue('R' . ($ttdRow + 5), '(SPV Produksi)');

                $sheet->getStyle("L$ttdRow:S" . ($ttdRow + 5))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                /*
                =============================
                PRINT
                =============================
                */

                $sheet->getPageSetup()->setOrientation(
                    \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                );

                $sheet->getPageMargins()->setTop(0);
                $sheet->getPageMargins()->setRight(0);
                $sheet->getPageMargins()->setLeft(0.2);
                $sheet->getPageMargins()->setBottom(0);

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(false);
            }
        ];
    }
}
