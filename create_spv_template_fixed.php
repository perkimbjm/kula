<?php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers based on menu_kularakat_description.md
$headers = [
    'No',
    'Nomor Kontrak',
    'Paket',
    'Pejabat Pengadaan',
    'Waktu Pelaksanaan',
    'HPS',
    'Penawaran',
    'Aritmatik',
    'Harga SPK',
    'Penyedia/Perusahaan',
    'Tanggal Undangan',
    'Tanggal Evaluasi',
    'Tanggal Negosiasi',
    'Tanggal BA-HPL',
    'Tanggal SPPBJ',
    'Tanggal SPK',
    'Sumber Dana',
    'Tahun',
    'Nomor Addendum',
    'Tanggal',
    'Nilai',
    'BA LKPP'
];

// Set column headers
foreach ($headers as $index => $header) {
    $column = chr(65 + ($index % 26));
    if ($index >= 26) {
        $column = chr(64 + intval($index / 26)) . chr(65 + ($index % 26));
    }
    $sheet->setCellValue($column . '1', $header);
}

// Add guidance row with sample data
$guidanceRow = [
    '1',
    'SPK-001/2024',
    'Pengawasan Jalan Raya',
    '1',
    '30',
    '100000000',
    '90000000',
    '90000000',
    '85000000',
    '1',
    '2024-01-01',
    '2024-01-02',
    '2024-01-03',
    '2024-01-04',
    '2024-01-05',
    '2024-01-06',
    'APBD',
    '2024',
    'ADD-001/2024',
    '2024-02-01',
    '5000000',
    'BA-LKPP-001/2024'
];

foreach ($guidanceRow as $index => $value) {
    $column = chr(65 + ($index % 26));
    if ($index >= 26) {
        $column = chr(64 + intval($index / 26)) . chr(65 + ($index % 26));
    }
    $sheet->setCellValue($column . '2', $value);
}

// Style the headers
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF4472C4'],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$lastColumn = chr(65 + (count($headers) - 1));
$sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

// Style the guidance row
$guidanceStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFE7E6E6'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray($guidanceStyle);

// Auto size columns
foreach (range('A', $lastColumn) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$writer->save('public/data/template_spv.xlsx');

echo 'Template SpvResource berhasil dibuat: public/data/template_spv.xlsx' . PHP_EOL;
echo 'Total kolom: ' . count($headers) . PHP_EOL;
echo 'Kolom terakhir: ' . $lastColumn . PHP_EOL;
