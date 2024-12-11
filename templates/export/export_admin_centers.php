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

// Get admin_id from session
if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];
} else {
    echo "User not logged in.";
    exit;
}

// Fetch the barangay for the given admin_id
$query = "SELECT barangay FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if admin exists and fetch barangay
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $barangay = $admin['barangay'];
    $stmt->close();
} else {
    echo "Admin not found.";
    exit;
}

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

// Add margin after header
$section->addTextBreak(2);  // Adds two empty lines for spacing

// Add Barangay Title
$section->addText("Evacuation Centers in Barangay: " . $barangay, ['name' => 'Arial', 'size' => 16, 'bold' => true], ['alignment' => 'center']);

// Fetch Data from Database based on admin_id
if (isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];

    // Modify the query to filter by admin_id and include new evacuee count condition
    $query = "
    SELECT 
        ec.id, 
        ec.name, 
        CONCAT(ec.location, ', ', ec.barangay) AS address, 
        ec.capacity, 
        (SELECT COUNT(*) 
         FROM evacuees 
         WHERE (status = 'Admitted' OR (status = 'Transfer' AND evacuation_center_id = origin_evacuation_center_id)) 
         AND evacuation_center_id = ec.id) AS evacuees_count,
        (SELECT COUNT(*) 
         FROM evacuees e 
         LEFT JOIN members m ON e.id = m.evacuees_id 
         WHERE (e.status = 'Admitted' OR (e.status = 'Transfer' AND e.evacuation_center_id = e.origin_evacuation_center_id)) 
         AND e.evacuation_center_id = ec.id) AS total_evacuees_count
    FROM evacuation_center ec
    WHERE ec.admin_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $textStyle = ['name' => 'Arial', 'size' => 10];

    // Create Table
    $table = $section->addTable([
        'borderSize' => 6,
        'borderColor' => '000000',
        'cellMargin' => 50,
        'alignment' => 'center',
    ]);

    // Table Headers
    $table->addRow(null, ['cantSplit' => true]);
    $table->addCell(3000)->addText("Evacuation Center", ['bold' => true, 'size' => 11]);
    $table->addCell(4000)->addText("Address", ['bold' => true, 'size' => 11]);
    $table->addCell(1500)->addText("Capacity", ['bold' => true, 'size' => 11]);
    $table->addCell(1500)->addText("Families", ['bold' => true, 'size' => 11]);
    $table->addCell(1600)->addText("Evacuees", ['bold' => true, 'size' => 11]);
    $table->addCell(1500)->addText("Status", ['bold' => true, 'size' => 11]);

    $totalCenters = 0;
    $totalFamilies = 0;
    $totalEvacuees = 0;

    // Populate Table
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $evacueesCount = $row['evacuees_count'];
            $totalEvacueesCount = $row['total_evacuees_count'] + $evacueesCount;
            $capacityDisplay = "{$evacueesCount}/{$row['capacity']}";
            $status = $evacueesCount > 0 ? "Active" : "Inactive";

            // Update Totals
            $totalCenters++;
            $totalFamilies += $evacueesCount;
            $totalEvacuees += $totalEvacueesCount;

            // Add Table Row
            $table->addRow(null, ['cantSplit' => true]);
            $table->addCell(3000)->addText($row['name'], $textStyle);
            $table->addCell(4000)->addText($row['address'], $textStyle);
            $table->addCell(1500)->addText($capacityDisplay, $textStyle);
            $table->addCell(1500)->addText($evacueesCount, $textStyle);
            $table->addCell(1600)->addText($totalEvacueesCount, $textStyle);
            $table->addCell(1500)->addText($status, $textStyle);
        }

        // Add Summary Section
        $section->addTextBreak(1);  // Adds a line break before summary
        $section->addText("Summary:", ['size' => 14, 'bold' => true]);
        $section->addText("Total Evacuation Centers: {$totalCenters}", ['size' => 12]);
        $section->addText("Total Families: {$totalFamilies}", ['size' => 12]);
        $section->addText("Total Evacuees (Families + Members): {$totalEvacuees}", ['size' => 12]);

    } else {
        $section->addText("No data found.", ['size' => 14, 'color' => 'FF0000']);
    }
    $footer = $section->addFooter();
    $dateTime = new DateTime("now", new DateTimeZone("Asia/Manila"));
    $currentDateTime = $dateTime->format("M d, Y h:i A");
    $footer->addText(
        $currentDateTime,
        ['name' => 'Arial', 'size' => 10],
        ['alignment' => 'right']
    );
    // Save File
    $filename = "Evacuation_Centers_Report_" . date("Ymd_His") . ".docx";
    header('Content-Type: application/msword');
    header("Content-Disposition: attachment; filename={$filename}");
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save("php://output");
    exit;
}
?>