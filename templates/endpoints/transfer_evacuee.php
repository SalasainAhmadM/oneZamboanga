<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$evacueeId = $data['evacuee_id'] ?? null;
$centerId = $data['center_id'] ?? null;

if (!$evacueeId || !$centerId) {
    echo json_encode(['success' => false, 'message' => 'Missing evacuee ID or center ID.']);
    exit;
}

// Retrieve the evacuation center's name
$centerQuery = "SELECT name FROM evacuation_center WHERE id = ?";
$centerStmt = $conn->prepare($centerQuery);
$centerStmt->bind_param("i", $centerId);
$centerStmt->execute();
$centerResult = $centerStmt->get_result();
if ($centerResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Evacuation center not found.']);
    exit;
}
$centerName = $centerResult->fetch_assoc()['name'];

// Update evacuee's center and status
$updateQuery = "UPDATE evacuees SET evacuation_center_id = ?, status = 'Transfer' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("ii", $centerId, $evacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Log the transfer with the center's name
    $logMsg = "Requesting transfer to $centerName";
    $status = "notify";
    $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->bind_param("ssi", $logMsg, $status, $evacueeId);
    $logStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Evacuee successfully transferred.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to transfer evacuee.']);
}
exit;
?>