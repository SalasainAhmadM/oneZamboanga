<?php
require_once '../../connection/conn.php';

$evacuationCenterId = $_GET['id'];
$selectedYear = $_GET['year'];

// Initialize monthly evacuees array
$monthlyEvacuees = array_fill(1, 12, 0);

// Query to fetch evacuees per month for the selected year
$sqlMonthlyEvacuees = "
    SELECT 
        MONTH(e.date) as month, 
        COUNT(e.id) as evacuees_count,
        (SELECT COUNT(m.id) FROM members m WHERE m.evacuees_id = e.id) as members_count
    FROM evacuees e
    WHERE e.evacuation_center_id = ? AND YEAR(e.date) = ?
    GROUP BY MONTH(e.date)";
$stmtMonthlyEvacuees = $conn->prepare($sqlMonthlyEvacuees);
$stmtMonthlyEvacuees->bind_param("ii", $evacuationCenterId, $selectedYear);
$stmtMonthlyEvacuees->execute();
$resultMonthlyEvacuees = $stmtMonthlyEvacuees->get_result();

// Populate evacuees per month
while ($row = $resultMonthlyEvacuees->fetch_assoc()) {
    $month = $row['month'];
    $evacueesCount = $row['evacuees_count'];
    $membersCount = $row['members_count'];
    $monthlyEvacuees[$month] = $evacueesCount + $membersCount;
}

// Return data as JSON
echo json_encode(array_values($monthlyEvacuees));
?>