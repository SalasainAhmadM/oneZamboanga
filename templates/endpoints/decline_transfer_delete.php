<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Update evacuee status
$updateQuery = "UPDATE evacuees SET status = 'Admitted' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $evacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Retrieve the current evacuee's details
    $selectQuery = "SELECT first_name, middle_name, last_name, birthday FROM evacuees WHERE id = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $evacueeId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();
    $evacuee = $result->fetch_assoc();

    if ($evacuee) {
        $firstName = $evacuee['first_name'];
        $middleName = $evacuee['middle_name'];
        $lastName = $evacuee['last_name'];
        $birthday = $evacuee['birthday'];

        // Find matching evacuees with status "Transfer"
        $matchQuery = "SELECT id FROM evacuees WHERE first_name = ? AND middle_name = ? AND last_name = ? AND birthday = ? AND status = 'Transfer'";
        $matchStmt = $conn->prepare($matchQuery);
        $matchStmt->bind_param("ssss", $firstName, $middleName, $lastName, $birthday);
        $matchStmt->execute();
        $result = $matchStmt->get_result();

        // Update and log for each matching evacuee
        $updateMatchQuery = "UPDATE evacuees SET status = 'Admitted' WHERE id = ?";
        $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
        $logMsg = "Transfer declined.";
        $status = "notify";

        while ($match = $result->fetch_assoc()) {
            $matchEvacueeId = $match['id'];

            // Update status for each matching evacuee
            $updateMatchStmt = $conn->prepare($updateMatchQuery);
            $updateMatchStmt->bind_param("i", $matchEvacueeId);
            $updateMatchStmt->execute();

            // Insert log for each matching evacuee
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bind_param("ssi", $logMsg, $status, $matchEvacueeId);
            $logStmt->execute();
        }

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
            echo json_encode(['success' => true, 'message' => 'Evacuee and its logs deleted successfully. Matching evacuees admitted and logged.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete evacuee record.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Evacuee details not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to admit evacuee.']);
}
exit;
?>