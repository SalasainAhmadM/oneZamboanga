<?php
require_once '../../connection/conn.php';

$barangay = $_POST['barangay'];

$query = "SELECT id, name FROM evacuation_center WHERE admin_id IN (SELECT id FROM admin WHERE barangay = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $barangay);
$stmt->execute();
$result = $stmt->get_result();

$centers = [];
while ($row = $result->fetch_assoc()) {
    $centers[] = $row;
}

echo json_encode(['success' => true, 'centers' => $centers]);
?>