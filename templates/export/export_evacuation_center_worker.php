<?php
require_once '../../connection/conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

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

// Add Row for Header Content
$headerTable->addRow();

// Left Logo
$logoLeftPath = "../../assets/img/zambo.png"; // Replace with actual path for the left logo
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
$logoRightPath = "../../assets/img/logo5.png"; // Replace with actual path for the right logo
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
$lineTable = $section->addTable();
$lineTable->addRow();
$lineTable->addCell(9000, [
    'borderBottomSize' => 18,
    'borderBottomColor' => '000000',
    'valign' => 'bottom',
])->addText('', [], ['spaceBefore' => 200, 'spaceAfter' => 200]); // Adds spacing above and below the line

// Validate and fetch the ID from the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $section->addText("No evacuation center selected.", ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
} else {
    $evacuationCenterId = intval($_GET['id']);

    // Fetch evacuation center data from the database
    $sql = "
      SELECT 
    ec.name, 
    ec.location, 
    ec.capacity, 
    ec.image, 
    (
        SELECT COUNT(e.id) 
        FROM evacuees e 
        WHERE 
            e.evacuation_center_id = ec.id 
            AND (e.status = 'Admitted' OR (e.status = 'Transfer' AND e.origin_evacuation_center_id = ec.id))
    ) AS evacuee_count,
    (
        SELECT COUNT(m.id)
        FROM members m
        INNER JOIN evacuees e ON m.evacuees_id = e.id
        WHERE 
            e.evacuation_center_id = ec.id 
            AND (e.status = 'Admitted' OR (e.status = 'Transfer' AND e.origin_evacuation_center_id = ec.id))
    ) AS member_count,
    CASE 
        WHEN (
            SELECT COUNT(e.id) 
            FROM evacuees e 
            WHERE 
                e.evacuation_center_id = ec.id 
                AND (e.status = 'Admitted' OR (e.status = 'Transfer' AND e.origin_evacuation_center_id = ec.id))
        ) = 0 THEN 'Inactive' 
        ELSE 'Active' 
    END AS status,
    a.barangay
FROM 
    evacuation_center ec
LEFT JOIN 
    admin a ON ec.admin_id = a.id
WHERE 
    ec.id = ?
GROUP BY 
    ec.id;

";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $evacuationCenterId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $name = htmlspecialchars($row['name']);
        $location = htmlspecialchars($row['location']);
        $barangay = htmlspecialchars($row['barangay']);
        $capacity = htmlspecialchars($row['capacity']);
        $imagePath = !empty($row['image']) ? $row['image'] : '../../assets/img/aboutImg.png';
        $evacueeCount = intval($row['evacuee_count']);
        $memberCount = intval($row['member_count']);
        $totalEvacuees = $evacueeCount + $memberCount;
        $status = htmlspecialchars($row['status']);

        if (file_exists($imagePath)) {
            $section->addTextBreak(1);
            $section->addImage($imagePath, [
                'width' => 300,
                'height' => 300,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'marginTop' => 200,
                'marginBottom' => 200,
            ]);
            $section->addTextBreak(1);
        } else {
            $section->addText("Image not available", ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
        }

        $section->addText($name, ['name' => 'Arial', 'size' => 14, 'bold' => true], ['alignment' => 'center']);
        $section->addText("Address: " . $location . ", " . $barangay, ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
        $section->addText("Status: " . $status, ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
        $section->addText("Capacity: " . $capacity, ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
        $section->addText("Total Families: " . $evacueeCount, ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
        $section->addText("Total Evacuees: " . $totalEvacuees, ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
    } else {
        $section->addText("No evacuation center data available for the selected ID.", ['name' => 'Arial', 'size' => 12], ['alignment' => 'center']);
    }
}

// Save the document
if (isset($name)) {
    $fileName = str_replace(' ', '_', strtolower($name)) . ".docx";
} else {
    $fileName = "evacuation_center_details.docx"; // Default file name if no name is available
}

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
?>