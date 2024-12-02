<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Required supply names
$requiredSupplies = ['Starting Kit', 'Starter Kit', 'Evacuation Kit', 'Supply Kit'];

// Check if evacuee has received at least one required supply
$query = "SELECT COUNT(*) as count FROM distribute 
          WHERE evacuees_id = ? AND supply_name IN ('" . implode("','", $requiredSupplies) . "')";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $evacueeId);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['count'] > 0) {
    echo json_encode(['success' => true, 'hasRequiredSupplies' => true]);
} else { // No supplies received
    echo json_encode(['success' => true, 'hasRequiredSupplies' => false]);
}
exit;
?>