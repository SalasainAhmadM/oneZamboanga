<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

try {
    // Delete evacuee logs first
    $deleteLogsQuery = "DELETE FROM evacuees_log WHERE evacuees_id = ?";
    $deleteLogsStmt = $conn->prepare($deleteLogsQuery);
    $deleteLogsStmt->bind_param("i", $evacueeId);
    $deleteLogsStmt->execute();

    // Delete evacuee record
    $deleteQuery = "DELETE FROM evacuees WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $evacueeId);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Evacuee record successfully deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Evacuee not found or already removed.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
exit;
?>