<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Update evacuee status to "Transferred"
$updateQuery = "UPDATE evacuees SET status = 'Transferred' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $evacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Log the transfer action
    $logMsg = "Transfer approved.";
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