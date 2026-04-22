<?php

namespace App\Exports;

use App\Models\Timbangan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RekapDoExport implements FromCollection, WithEvents
{
    protected $tujuan_id;

    public function __construct($id)
    {
        $this->tujuan_id = $id;
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

                $tujuan = DB::table('tujuans')
                    ->where('id', $this->tujuan_id)
                    ->first();

                $produk = DB::table('tujuan_produks')
                    ->join('produks', 'produks.id', '=', 'tujuan_produks.produk_id')
                    ->where('tujuan_id', $this->tujuan_id)
                    ->select(
                        'tujuan_produks.id',
                        'produks.nama_produk',
                        'tujuan_produks.note',
                        'tujuan_produks.prod_date'
                    )
                    ->orderBy('tujuan_produks.prod_date')
                    ->get()
                    ->groupBy('prod_date');


                /*
                =============================
                HEADER
                =============================
                */

                $sheet->setCellValue('B1', 'PT Widodo Makmur Unggas Tbk.');
                $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(10);

                $sheet->mergeCells('A4:S4');
                $sheet->setCellValue('A4', 'TALLY BONELESS');

                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A5', 'Tanggal');
                $sheet->setCellValue('C5', ': ' . (formatTanggalIndo($tujuan->created_at) ?? ''));

                $pd = array_filter([$tujuan->prod_date_1, $tujuan->prod_date_2, $tujuan->prod_date_3]);

                $sheet->setCellValue('A6', 'Prod. Date');
                $sheet->setCellValue('C6', ': ' . implode(' / ', $pd));


                $sheet->setCellValue('P5', 'DO');
                $sheet->setCellValue('Q5', ': ' . ($tujuan->nama_tujuan ?? ''));
                $sheet->setCellValue('P6', 'HASIL');
                $sheet->getStyle('P6')->getFont()->setBold(true);

                $sheet->getColumnDimension('A')->setWidth(5.5);


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
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // $sheet->mergeCells('A7:A9');
                // $sheet->mergeCells('A22:A24');


                /*
                =============================
                TABEL TIMBANGAN
                =============================
                */

                $startRow = 9;
                $blockIndex = 0;
                $maxRowGroup = 0;

                foreach ($produk as $prodDate => $listProduk) {

                    if ($produk->count() > 1) {

                        $sheet->mergeCells("A" . ($startRow - 1) . ":S" . ($startRow - 1));

                        $sheet->setCellValue(
                            "A" . ($startRow - 1),
                            'PROD DATE : ' . ($prodDate ?: '-')
                        );

                        $sheet->getStyle("A" . ($startRow - 1))
                            ->getFont()
                            ->setBold(true);
                    }

                    foreach ($listProduk as $p) {


                        $timbang = Timbangan::where('tujuan_produk_id', $p->id)
                            ->orderBy('urutan')
                            ->get();

                        $chunks = $timbang->chunk($MAX_ROW);

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

                            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colPcs))->setWidth(7);
                            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colKg))->setWidth(9);

                            // ITEM
                            $sheet->mergeCellsByColumnAndRow($colPcs, $rowStart, $colKg, $rowStart);
                            $sheet->setCellValueByColumnAndRow($colPcs, $rowStart, $p->nama_produk);

                            $sheet->getStyleByColumnAndRow($colPcs, $rowStart, $colKg, $rowStart)
                                ->getFont()
                                ->setBold(true);

                            $sheet->getStyleByColumnAndRow($colPcs, $rowStart, $colKg, $rowStart)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                            // NOTE
                            $sheet->mergeCellsByColumnAndRow($colPcs, $rowStart + 1, $colKg, $rowStart + 1);
                            $sheet->setCellValueByColumnAndRow($colPcs, $rowStart + 1, $p->note);

                            $sheet->getStyleByColumnAndRow($colPcs, $rowStart + 1, $colKg, $rowStart)
                                ->getFont()
                                ->setBold(true);

                            $sheet->getStyleByColumnAndRow($colPcs, $rowStart + 1, $colKg, $rowStart + 1)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                            // HEADER
                            $sheet->mergeCellsByColumnAndRow(
                                $colNo,
                                $rowStart,
                                $colNo,
                                $rowStart + 2
                            );
                            $sheet->setCellValueByColumnAndRow($colNo, $rowStart, 'NO');
                            $sheet->getStyleByColumnAndRow($colNo, $rowStart, $colNo, $rowStart + 2)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                            $sheet->setCellValueByColumnAndRow($colPcs, $rowStart + 2, 'EKOR');
                            $sheet->setCellValueByColumnAndRow($colKg, $rowStart + 2, 'KG');

                            $row = $rowStart + 3;
                            $no = 1;

                            foreach ($chunk as $t) {

                                $sheet->setCellValueByColumnAndRow($colNo, $row, $no);
                                $sheet->setCellValueByColumnAndRow($colPcs, $row, $t->pcs);
                                $sheet->setCellValueByColumnAndRow($colKg, $row, $t->berat);

                                $row++;
                                $no++;
                            }

                            // TOTAL
                            $sheet->setCellValueByColumnAndRow($colNo, $rowStart + 13, 'TOTAL');

                            $sheet->setCellValueByColumnAndRow(
                                $colPcs,
                                $rowStart + 13,
                                "=SUM(" .
                                    Coordinate::stringFromColumnIndex($colPcs) .
                                    ($rowStart + 3) . ":" .
                                    Coordinate::stringFromColumnIndex($colPcs) .
                                    ($rowStart + 12) . ")"
                            );

                            $sheet->setCellValueByColumnAndRow(
                                $colKg,
                                $rowStart + 13,
                                "=SUM(" .
                                    Coordinate::stringFromColumnIndex($colKg) .
                                    ($rowStart + 3) . ":" .
                                    Coordinate::stringFromColumnIndex($colKg) .
                                    ($rowStart + 12) . ")"
                            );

                            // BORDER
                            $sheet->getStyleByColumnAndRow(
                                $colNo,
                                $rowStart,
                                $colKg,
                                $rowStart + 13
                            )->getBorders()->getAllBorders()
                                ->setBorderStyle(Border::BORDER_THIN);

                            // ALIGN CENTER
                            $sheet->getStyleByColumnAndRow(
                                $colNo,
                                $rowStart + 2,
                                $colKg,
                                $rowStart + 13
                            )->getAlignment()->setHorizontal('center');

                            $sheet->getStyleByColumnAndRow(
                                $colKg,
                                $rowStart + 3,
                                $colKg,
                                $rowStart + 13
                            )->getNumberFormat()->setFormatCode('0.00');

                            $blockIndex++;
                        }
                    } // end produk loop
                    $blockIndex = 0;

                    $usedHeight = ($maxRowGroup + 1) * 15;

                    $startRow += $usedHeight;

                    $maxRowGroup = 0;
                } // end prod date loop


                /*
                =============================
                REKAP
                =============================
                */

                $rekapStart = $startRow++;

                // HEADER
                $sheet->mergeCells('A' . $rekapStart . ':B' . $rekapStart);
                $sheet->setCellValue('A' . $rekapStart, 'ITEM');
                $sheet->getStyle('A' . $rekapStart)->getFont()->setBold(true);

                $sheet->setCellValue('C' . $rekapStart, 'Ekor/Pcs');
                $sheet->getStyle('C' . $rekapStart)->getFont()->setBold(true);
                $sheet->mergeCells('D' . $rekapStart . ':E' . $rekapStart);
                $sheet->setCellValue('D' . $rekapStart, 'KG');
                $sheet->getStyle('D' . $rekapStart)->getFont()->setBold(true);

                // CENTER HEADER
                $sheet->getStyle("A$rekapStart:D$rekapStart")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $rekap = DB::table('tujuan_produks')
                    ->join('produks', 'produks.id', '=', 'tujuan_produks.produk_id')
                    ->join('timbangans', 'timbangans.tujuan_produk_id', '=', 'tujuan_produks.id')
                    ->where('tujuan_produks.tujuan_id', $this->tujuan_id)
                    ->select(
                        'produks.nama_produk',
                        DB::raw('SUM(timbangans.pcs) pcs'),
                        DB::raw('SUM(timbangans.berat) berat')
                    )
                    ->groupBy('produks.nama_produk')
                    ->get();

                $row = $rekapStart + 1;

                foreach ($rekap as $r) {

                    // ITEM MERGE
                    $sheet->mergeCells("A$row:B$row");
                    $sheet->setCellValue("A$row", $r->nama_produk);
                    $sheet->getStyle("A$row")->getFont()->setBold(true);

                    // DATA
                    $sheet->setCellValue("C$row", $r->pcs);
                    $sheet->mergeCells("D$row:E$row");
                    $sheet->setCellValue("D$row", $r->berat);

                    // CENTER ITEM
                    $sheet->getStyle("A$row:B$row")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    // CENTER EKOR
                    $sheet->getStyle("C$row")
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    // CENTER KG
                    $sheet->getStyle("D$row:E$row")
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    // FORMAT 2 DESIMAL KG
                    $sheet->getStyle("D$row:E$row")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');

                    $row++;
                }

                // BORDER
                $sheet->getStyle("A$rekapStart:E" . ($row - 1))
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);


                // TTD
                $sheet->mergeCells('L' . $rekapStart . ':M' . $rekapStart);
                $sheet->mergeCells('N' . $rekapStart . ':O' . $rekapStart);
                $sheet->mergeCells('P' . $rekapStart . ':S' . $rekapStart);

                $sheet->setCellValue('L' . $rekapStart, 'Dibuat,');
                $sheet->setCellValue('N' . $rekapStart, 'Diterima,');
                $sheet->setCellValue('P' . $rekapStart, 'Mengetahui,');

                $sheet->mergeCells('L' . $rekapStart + 4 . ':M' . $rekapStart + 4);
                $sheet->setCellValue('L' . $rekapStart + 4, auth()->user()->name);

                $sheet->mergeCells('L' . $rekapStart + 5 . ':M' . $rekapStart + 5);
                $sheet->mergeCells('N' . $rekapStart + 5 . ':O' . $rekapStart + 5);
                $sheet->mergeCells('P' . $rekapStart + 5 . ':Q' . $rekapStart + 5);
                $sheet->mergeCells('R' . $rekapStart + 5 . ':S' . $rekapStart + 5);
                $sheet->setCellValue('L' . $rekapStart + 5, '(Tallyman)');
                $sheet->setCellValue('N' . $rekapStart + 5, '(Admin Produksi)');
                $sheet->setCellValue('P' . $rekapStart + 5, '(Leader Area)');
                $sheet->setCellValue('R' . $rekapStart + 5, '(SPV Produksi)');

                $sheet->getStyle('L' . $rekapStart . ':S' . ($rekapStart + 5))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                /*
                =============================
                PRINT SETTING
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
