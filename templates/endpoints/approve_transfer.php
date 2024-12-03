<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Function to find matching evacuee by name and birthday, and update their status to 'Transferred'
function transferMatchingEvacuee($conn, $evacueeId)
{
    // Fetch evacuee details
    $fetchQuery = "SELECT first_name, middle_name, last_name, birthday FROM evacuees WHERE id = ?";
    $fetchStmt = $conn->prepare($fetchQuery);
    $fetchStmt->bind_param("i", $evacueeId);
    $fetchStmt->execute();
    $result = $fetchStmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Evacuee not found.'];
    }

    $evacuee = $result->fetch_assoc();
    $firstName = $evacuee['first_name'];
    $middleName = $evacuee['middle_name'];
    $lastName = $evacuee['last_name'];
    $birthday = $evacuee['birthday'];

    // Find matching evacuee with status 'Transfer'
    $matchQuery = "SELECT id FROM evacuees 
                   WHERE first_name = ? 
                   AND middle_name = ? 
                   AND last_name = ? 
                   AND birthday = ? 
                   AND status = 'Transfer'";
    $matchStmt = $conn->prepare($matchQuery);
    $matchStmt->bind_param("ssss", $firstName, $middleName, $lastName, $birthday);
    $matchStmt->execute();
    $matchResult = $matchStmt->get_result();

    if ($matchResult->num_rows === 0) {
        return ['success' => false, 'message' => 'No matching evacuee found with status Transfer.'];
    }

    $matchedEvacuee = $matchResult->fetch_assoc();
    $matchedEvacueeId = $matchedEvacuee['id'];

    // Update the status to 'Transferred'
    $updateQuery = "UPDATE evacuees SET status = 'Transferred' WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $matchedEvacueeId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        // Log the transfer action for the matching evacuee
        $logMsg = "Transfer approved.";
        $status = "notify";
        $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("ssi", $logMsg, $status, $matchedEvacueeId);
        $logStmt->execute();

        return ['success' => true, 'message' => 'Matching evacuee successfully transferred.'];
    } else {
        return ['success' => false, 'message' => 'Failed to update matching evacuee status.'];
    }
}

// Update evacuee status to 'Admitted'
$updateQuery = "UPDATE evacuees SET status = 'Admitted' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $evacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Log the transfer action for the original evacuee
    $logMsg = "Transfer approved.";
    $status = "notify";
    $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->bind_param("ssi", $logMsg, $status, $evacueeId);
    $logStmt->execute();

    // Attempt to transfer matching evacuee
    $transferResult = transferMatchingEvacuee($conn, $evacueeId);

    echo json_encode([
        'success' => true,
        'message' => 'Evacuee successfully transferred.',
        'matchingTransfer' => $transferResult
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to transfer evacuee.']);
}

exit;
?>