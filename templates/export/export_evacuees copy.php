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

// Validate statuses
$allowedStatuses = ['Admitted', 'Transfer', 'Transferred', 'Moved-out'];
$statuses = array_filter($statuses, function ($status) use ($allowedStatuses) {
    return in_array($status, $allowedStatuses);
});

// Create a new PhpWord instance
$phpWord = new PhpWord();

// Define paper size (short bond paper: 8.5 x 11 inches)
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

// Add Header Content
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

// Evacuation Center Header (if specific center is selected)
$statusCondition = '';
if (!empty($statuses)) {
    $placeholders = implode(',', array_fill(0, count($statuses), '?'));
    $statusCondition = "AND e.status IN ($placeholders)";
}

if ($centerId === 'All') {
    $evacueesQuery = "
        SELECT 
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity,
            (SELECT COUNT(*) FROM members m WHERE m.evacuees_id = e.id) AS number_of_members
        FROM evacuees e
        WHERE e.admin_id = ? $statusCondition";
    $stmt = $conn->prepare($evacueesQuery);
    $params = array_merge([$admin_id], $statuses);
} else {
    $evacueesQuery = "
        SELECT 
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity,
            (SELECT COUNT(*) FROM members m WHERE m.evacuees_id = e.id) AS number_of_members
        FROM evacuees e
        WHERE e.admin_id = ? AND e.evacuation_center_id = ? $statusCondition";
    $stmt = $conn->prepare($evacueesQuery);
    $params = array_merge([$admin_id, $centerId], $statuses);
}

// Bind parameters dynamically
$stmt->bind_param(str_repeat('s', count($params)), ...$params);

$stmt->execute();
$evacueesResult = $stmt->get_result();

$section->addTextBreak(1);

// Centered title
$section->addText(
    "Evacuees List",
    ['name' => 'Arial', 'size' => 14, 'bold' => true],
    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER] // Center alignment
);

// Create the table with the required styling
$tableStyle = [
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 50,
    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER // Center align the entire table
];
$table = $section->addTable($tableStyle);

// Add the header row
$table->addRow();
$table->addCell(2000)->addText("Family Head", ['bold' => true]);
$table->addCell(1800)->addText("Contact #", ['bold' => true]);
$table->addCell(1000)->addText("Age", ['bold' => true]);
$table->addCell(1000)->addText("Sex", ['bold' => true]);
$table->addCell(1500)->addText("Members", ['bold' => true]);
$table->addCell(2000)->addText("Barangay", ['bold' => true]);
$table->addCell(1500)->addText("Date", ['bold' => true]);
$table->addCell(1500)->addText("Calamity", ['bold' => true]);
$table->addCell(1800)->addText("Status", ['bold' => true]);

// Populate the table with data
while ($evacuee = $evacueesResult->fetch_assoc()) {
    $table->addRow();
    $table->addCell(2000)->addText($evacuee['family_head']);
    $table->addCell(1800)->addText($evacuee['contact']);
    $table->addCell(1000)->addText($evacuee['age']);
    $table->addCell(1000)->addText($evacuee['sex']);
    $table->addCell(1500)->addText($evacuee['number_of_members']);
    $table->addCell(2000)->addText($evacuee['barangay']);
    $table->addCell(1500)->addText($evacuee['date']);
    $table->addCell(1500)->addText($evacuee['calamity']);
    $table->addCell(1800)->addText($evacuee['status']);
}

$stmt->close();


// Save the document
$fileName = ($centerId === 'All') ? "all_evacuation_centers.docx" : str_replace(' ', '_', strtolower($center['name'])) . ".docx";

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
?>