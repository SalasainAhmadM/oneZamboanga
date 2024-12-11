<?php
require_once '../../connection/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

session_start();

if (!isset($_GET['supply_id'])) {
    die('Supply ID is required');
}

$supply_id = intval($_GET['supply_id']);

// Helper function to pluralize units
function pluralize_unit($quantity, $unit)
{
    return $quantity > 1 ? $unit . "s" : $unit;
}

// Fetch supply details
$query = "
    SELECT 
        s.name AS supply_name,
        s.description,
        s.quantity,
        s.original_quantity,
        s.unit,
        s.date,
        s.time,
        s.`from`,
        ec.name AS evacuation_center_name
    FROM 
        supply AS s
    INNER JOIN 
        evacuation_center AS ec
    ON 
        s.evacuation_center_id = ec.id
    WHERE 
        s.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supply_id);
$stmt->execute();
$result = $stmt->get_result();

$supply = $result->fetch_assoc();
if (!$supply) {
    die('Supply not found');
}

// Fetch stock details
$stockQuery = "
    SELECT 
        date, 
        time, 
        `from`, 
        quantity, 
        original_quantity, 
        unit 
    FROM 
        stock 
    WHERE 
        supply_id = ?
";
$stockStmt = $conn->prepare($stockQuery);
$stockStmt->bind_param("i", $supply_id);
$stockStmt->execute();
$stockResult = $stockStmt->get_result();
$stocks = $stockResult->fetch_all(MYSQLI_ASSOC);

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

$section->addTextBreak(1);
// Add Header
$section->addText(
    "Received Supply Report",
    ['name' => 'Arial', 'size' => 14, 'bold' => true],
    ['alignment' => 'center']
);
$section->addTextBreak(1);

// Add Supply Details
$textRun = $section->addTextRun();
$textRun->addText("Supply Name: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['supply_name'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Description: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['description'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Quantity: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['quantity'] . " " . pluralize_unit($supply['quantity'], $supply['unit']), ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("From: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['from'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Date Received: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['date'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Time Received: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['time'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Evacuation Center: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($supply['evacuation_center_name'], ['name' => 'Arial', 'size' => 11]);

$section->addTextBreak(1);


// Add Stock Details
$section->addText("Stock Details:", ['name' => 'Arial', 'size' => 12, 'bold' => true]);

if (count($stocks) > 0) {
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
    // Add table headers
    $table->addRow();
    $table->addCell(2000)->addText("Date", ['bold' => true]);
    $table->addCell(2000)->addText("Time", ['bold' => true]);
    $table->addCell(2000)->addText("Source", ['bold' => true]);
    $table->addCell(2000)->addText("Quantity", ['bold' => true]);
    $table->addCell(2000)->addText("Unit", ['bold' => true]);

    // Add stock rows
    foreach ($stocks as $stock) {
        $table->addRow();
        $table->addCell(2000)->addText($stock['date']);
        $table->addCell(2000)->addText($stock['time']);
        $table->addCell(2000)->addText($stock['from']);
        $table->addCell(2000)->addText($stock['quantity']);
        $table->addCell(2000)->addText(pluralize_unit($stock['quantity'], $stock['unit']));
    }
} else {
    $section->addText("No Stocks Available", ['name' => 'Arial', 'size' => 11, 'italic' => true]);
}

$footer = $section->addFooter();
$dateTime = new DateTime("now", new DateTimeZone("Asia/Manila"));
$currentDateTime = $dateTime->format("M d, Y h:i A");
$footer->addText(
    $currentDateTime,
    ['name' => 'Arial', 'size' => 10],
    ['alignment' => 'right']
);
// Save Word Document
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="Received_Supply_Report.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
