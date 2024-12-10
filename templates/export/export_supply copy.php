<?php
require_once '../../connection/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

session_start();


// Create PhpWord instance
$phpWord = new PhpWord();
$section = $phpWord->addSection([
    'pageSizeW' => 12240,
    'pageSizeH' => 15840,
    'marginLeft' => 1440,
    'marginRight' => 1440,
    'marginTop' => 1440,
    'marginBottom' => 1440,
]);
// Header Section
$header = $section->addHeader();
$headerTable = $header->addTable();
$headerTable->addRow();

// Left Logo
$logoLeftPath = "../../assets/img/zambo.png";
if (file_exists($logoLeftPath)) {
    $headerTable->addCell(2500)->addImage($logoLeftPath, [
        'width' => 60,
        'height' => 60,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    ]);
} else {
    $headerTable->addCell(2500)->addText("Logo Left", ['alignment' => 'center']);
}

// Center Text
$headerCell = $headerTable->addCell(5000);
$headerCell->addText("Republica de Filipinas", ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
$headerCell->addText("Ciudad de Zamboanga", ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
$headerCell->addText("OFICINA DE ASISTENCIA SOCIAL Y DESAROLLO", ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);
// $headerCell->addText("DISASTER ASSISTANCE FAMILY ACCESS CARD (DAFAC)", ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);

// Right Logo
$logoRightPath = "../../assets/img/logo5.png";
if (file_exists($logoRightPath)) {
    $headerTable->addCell(2500)->addImage($logoRightPath, [
        'width' => 60,
        'height' => 60,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    ]);
} else {
    $headerTable->addCell(2500)->addText("Logo Right", ['alignment' => 'center']);
}

// Add Margin and Title
$section->addTextBreak(1);
$section->addText(
    $filter === 'received' ? "Received Supply Reports" : "Distributed Supply Reports",
    ['name' => 'Arial', 'size' => 14, 'bold' => true],
    ['alignment' => 'center']
);
$section->addTextBreak(1);

// Add Table Content with Borders
$tableStyle = [
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 80,
];
$phpWord->addTableStyle('ContentTable', $tableStyle);
$table = $section->addTable('ContentTable');

$textStyle = ['name' => 'Arial', 'size' => 10];
$headerTextStyle = ['name' => 'Arial', 'size' => 11, 'bold' => true];

if ($filter === 'received') {
    $table->addRow();
    $table->addCell(1500)->addText('Supply Name', $headerTextStyle);
    $table->addCell(2000)->addText('Supply', $headerTextStyle);
    $table->addCell(1700)->addText('Stocks', $headerTextStyle);
    $table->addCell(2000)->addText('Evacuation Center', $headerTextStyle);
    $table->addCell(2000)->addText('Date Received', $headerTextStyle);
    $table->addCell(2500)->addText('Supply From', $headerTextStyle);

    foreach ($data as $row) {
        $table->addRow();
        $table->addCell(1500)->addText($row['supply_name'], $textStyle);
        $table->addCell(2000)->addText($row['main_quantity'], $textStyle);

        // All stock quantities in one cell
        $table->addCell(1700)->addText(implode("\n", explode("\n", $row['stock_quantities'])), $textStyle);

        // Evacuation Center
        $table->addCell(2000)->addText($row['evacuation_center_name'], $textStyle);

        // Supplied Date with Stock Dates Below
        $suppliedDateText = $row['supply_date'] . "\n" . $row['stock_dates'];
        $table->addCell(2000)->addText($suppliedDateText, $textStyle);

        // Supply From with Stock Sources Below
        $supplyFromText = $row['supply_from'] . "\n" . $row['stock_sources'];
        $table->addCell(2500)->addText($supplyFromText, $textStyle);
    }


} elseif ($filter === 'distributed') {
    $table->addRow();
    $table->addCell(2000)->addText('Supply Name', $headerTextStyle);
    $table->addCell(2000)->addText('Distributed To', $headerTextStyle);
    $table->addCell(1000)->addText('Quantity', $headerTextStyle);
    $table->addCell(2000)->addText('Evacuation Center', $headerTextStyle);
    $table->addCell(1500)->addText('Date', $headerTextStyle);

    foreach ($data as $row) {
        $table->addRow();
        $table->addCell(2000)->addText($row['supply_name'], $textStyle);
        $table->addCell(2000)->addText($row['evacuee_name'], $textStyle);
        $table->addCell(1000)->addText($row['quantity'], $textStyle);
        $table->addCell(2000)->addText($row['evacuation_center_name'], $textStyle);
        $table->addCell(1500)->addText($row['date'], $textStyle);
    }
}

// Save Word File
header("Content-Disposition: attachment; filename=supply_report.docx");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
?>