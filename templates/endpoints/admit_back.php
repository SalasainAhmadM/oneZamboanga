<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Begin transaction to ensure all operations are atomic
$conn->begin_transaction();

try {
    // Update evacuee status
    $updateQuery = "UPDATE evacuees SET status = 'Admitted' WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $evacueeId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        // Delete all distribute records for this evacuee
        $deleteDistributeQuery = "DELETE FROM distribute WHERE evacuees_id = ?";
        $deleteDistributeStmt = $conn->prepare($deleteDistributeQuery);
        $deleteDistributeStmt->bind_param("i", $evacueeId);
        $deleteDistributeStmt->execute();

        // Delete all evacuees log records except for the new one
        $deleteLogsQuery = "DELETE FROM evacuees_log WHERE evacuees_id = ?";
        $deleteLogsStmt = $conn->prepare($deleteLogsQuery);
        $deleteLogsStmt->bind_param("i", $evacueeId);
        $deleteLogsStmt->execute();

        // Log the admission (insert only new evacuee log)
        $logMsg = "Admitted back";
        $status = "notify";
        $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("ssi", $logMsg, $status, $evacueeId);
        $logStmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Evacuee successfully admitted, distribute records deleted, and logs updated.']);
    } else {
        throw new Exception('Failed to admit evacuee.');
    }
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

exit;


?>