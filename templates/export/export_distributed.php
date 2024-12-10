<?php
require_once '../../connection/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

session_start();

if (!isset($_GET['distribute_id'])) {
    die('Distribute ID is required');
}

$distribute_id = intval($_GET['distribute_id']);

// Fetch distribute details
$query = "
    SELECT 
        d.supply_name,
        d.date,
        d.quantity,
        e.first_name,
        e.middle_name,
        e.last_name,
        ec.name AS evacuation_center_name
    FROM 
        distribute AS d
    INNER JOIN 
        evacuees AS e
    ON 
        d.evacuees_id = e.id
    INNER JOIN 
        evacuation_center AS ec
    ON 
        e.evacuation_center_id = ec.id
    WHERE 
        d.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $distribute_id);
$stmt->execute();
$result = $stmt->get_result();

$distribution = $result->fetch_assoc();
if (!$distribution) {
    die('Distributed supply not found');
}

// Fetch evacuee members
$membersQuery = "
    SELECT 
        first_name, 
        middle_name, 
        last_name, 
        extension_name, 
        relation, 
        education, 
        gender, 
        age, 
        occupation 
    FROM 
        members 
    WHERE 
        evacuees_id = (SELECT evacuees_id FROM distribute WHERE id = ?)
";
$membersStmt = $conn->prepare($membersQuery);
$membersStmt->bind_param("i", $distribute_id);
$membersStmt->execute();
$membersResult = $membersStmt->get_result();
$members = $membersResult->fetch_all(MYSQLI_ASSOC);

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
$headerCell->addText("DISASTER ASSISTANCE FAMILY ACCESS CARD (DAFAC)", ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);

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
    "Distributed Supply Report",
    ['name' => 'Arial', 'size' => 14, 'bold' => true],
    ['alignment' => 'center']
);
$section->addTextBreak(1);

// Add Distribution Details
$textRun = $section->addTextRun();

// Add bold label
$textRun->addText("Supply Name: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);

// Add regular dynamic value
$textRun->addText($distribution['supply_name'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Quantity: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($distribution['quantity'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Date Distributed: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($distribution['date'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Distributed To: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($distribution['first_name'] . " " . $distribution['middle_name'] . " " . $distribution['last_name'], ['name' => 'Arial', 'size' => 11]);

$textRun = $section->addTextRun();
$textRun->addText("Evacuation Center: ", ['name' => 'Arial', 'size' => 11, 'bold' => true]);
$textRun->addText($distribution['evacuation_center_name'], ['name' => 'Arial', 'size' => 11]);

$section->addTextBreak(1);

// Add Evacuee Members
$section->addText("Evacuee Members:", ['name' => 'Arial', 'size' => 12, 'bold' => true]);

if (count($members) > 0) {
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
    // Add table headers
    $table->addRow();
    $table->addCell(1500)->addText("First Name", ['bold' => true]);
    $table->addCell(1500)->addText("Middle Name", ['bold' => true]);
    $table->addCell(1500)->addText("Last Name", ['bold' => true]);
    $table->addCell(1500)->addText("Relation", ['bold' => true]);
    $table->addCell(1500)->addText("Gender", ['bold' => true]);
    $table->addCell(1500)->addText("Age", ['bold' => true]);
    $table->addCell(1500)->addText("Occupation", ['bold' => true]);

    // Add members rows
    foreach ($members as $member) {
        $table->addRow();
        $table->addCell(1500)->addText($member['first_name']);
        $table->addCell(1500)->addText($member['middle_name']);
        $table->addCell(1500)->addText($member['last_name']);
        $table->addCell(1500)->addText($member['relation']);
        $table->addCell(1500)->addText($member['gender']);
        $table->addCell(1500)->addText($member['age']);
        $table->addCell(1500)->addText($member['occupation']);
    }
} else {
    $section->addText("No Members Available", ['name' => 'Arial', 'size' => 11, 'italic' => true]);
}

// Save Word Document
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="Distributed_Supply_Report.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
