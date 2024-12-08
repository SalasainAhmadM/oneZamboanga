<?php
require_once '../../connection/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];
$centerId = $_GET['center_id'] ?? 'All';
$statuses = isset($_GET['statuses']) ? explode(',', $_GET['statuses']) : [];
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

// Validate statuses
$allowedStatuses = ['Admitted', 'Transfer', 'Transferred', 'Moved-out']; // Define allowed statuses
$statuses = array_filter($statuses, function ($status) use ($allowedStatuses) {
    return in_array($status, $allowedStatuses);
});

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
// Add a horizontal line with spacing
// $lineTable = $section->addTable();
// $lineTable->addRow();
// $lineTable->addCell(9000, [
//     'borderBottomSize' => 18,
//     'borderBottomColor' => '000000',
//     'valign' => 'bottom',
// ])->addText('', [], ['spaceBefore' => 200, 'spaceAfter' => 200]);
// Evacuation Center Header (if specific center is selected)
if ($centerId !== 'All') {
    $centerQuery = "SELECT name FROM evacuation_center WHERE id = ? AND admin_id = ?";
    $stmt = $conn->prepare($centerQuery);
    $stmt->bind_param("ii", $centerId, $admin_id);
    $stmt->execute();
    $centerResult = $stmt->get_result();
    if ($centerResult->num_rows > 0) {
        $center = $centerResult->fetch_assoc();
        $section->addTextBreak(1);
        $section->addText($center['name'], ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);
    }
    $stmt->close();
}

// Construct evacuees query with dynamic conditions for date range and statuses
$statusCondition = '';
$dateCondition = '';
$params = [$admin_id];

if (!empty($statuses)) {
    $statusPlaceholders = implode(',', array_fill(0, count($statuses), '?'));
    $statusCondition = "AND e.status IN ($statusPlaceholders)";
    $params = array_merge($params, $statuses);
}

if (!empty($startDate) && !empty($endDate)) {
    $dateCondition = "AND e.date BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
}

if ($centerId !== 'All') {
    $evacueesQuery = "
        SELECT 
            e.id AS evacuees_id,
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity
        FROM evacuees e
        WHERE e.admin_id = ? AND e.evacuation_center_id = ? $statusCondition $dateCondition";
    array_splice($params, 1, 0, $centerId); // Insert centerId into params
} else {
    $evacueesQuery = "
        SELECT 
            e.id AS evacuees_id,
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity
        FROM evacuees e
        WHERE e.admin_id = ? $statusCondition $dateCondition";
}

// Prepare and execute query
$stmt = $conn->prepare($evacueesQuery);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$evacueesResult = $stmt->get_result();

// Populate table in Word document
$section->addTextBreak(1);
$section->addText("Evacuees List", ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);
$textStyle = ['name' => 'Arial', 'size' => 10];

$table = $section->addTable([
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 50,
    'alignment' => 'center',
]);

$table->addRow(null, ['cantSplit' => true]); // Prevents the row from splitting
$table->addCell(2000)->addText("Family Head", ['bold' => true, 'size' => 11]);
$table->addCell(1800)->addText("Contact", ['bold' => true, 'size' => 11]);
$table->addCell(1000)->addText("Age", ['bold' => true, 'size' => 11]);
$table->addCell(1000)->addText("Sex", ['bold' => true, 'size' => 11]);
$table->addCell(3000)->addText("Members", ['bold' => true, 'size' => 11]);
$table->addCell(1850)->addText("Barangay", ['bold' => true, 'size' => 11]);
$table->addCell(1500)->addText("Date", ['bold' => true, 'size' => 11]);
$table->addCell(1800)->addText("Calamity", ['bold' => true, 'size' => 11]);
$table->addCell(1800)->addText("Status", ['bold' => true, 'size' => 11]);

while ($evacuee = $evacueesResult->fetch_assoc()) {
    $table->addRow(null, ['cantSplit' => true]);
    $table->addCell(2000)->addText($evacuee['family_head'], $textStyle);
    $table->addCell(1800)->addText($evacuee['contact'], $textStyle);
    $table->addCell(1000)->addText($evacuee['age'], $textStyle);
    $table->addCell(1000)->addText($evacuee['sex'], $textStyle);

    // Fetch member details for the evacuee
    $memberQuery = "SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name, ' ', extension_name) AS full_name 
FROM members WHERE evacuees_id = ?";
    $memberStmt = $conn->prepare($memberQuery);
    $memberStmt->bind_param("i", $evacuee['evacuees_id']);
    $memberStmt->execute();
    $memberResult = $memberStmt->get_result();

    if ($memberResult->num_rows > 0) {
        $members = [];
        while ($member = $memberResult->fetch_assoc()) {
            $members[] = $member['full_name'];
        }
        // Join members with a new line and add to the Word document
        $table->addCell(3000)->addText(implode("\n", $members), $textStyle); // Use "\n" for line breaks
    } else {
        $table->addCell(3000)->addText("No member", $textStyle); // Default text when no members found
    }

    $memberStmt->close();


    $table->addCell(1850)->addText($evacuee['barangay'], $textStyle);
    $table->addCell(1500)->addText($evacuee['date'], $textStyle);
    $table->addCell(1800)->addText($evacuee['calamity'], $textStyle);
    $table->addCell(1800)->addText($evacuee['status'], $textStyle);
}

$stmt->close();

// Save document
$fileName = ($centerId === 'All') ? "all_evacuation_centers.docx" : str_replace(' ', '_', strtolower($center['name'])) . ".docx";

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
?>