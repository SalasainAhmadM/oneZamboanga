<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Fetch details of the evacuee from the GET parameter
$fetchQuery = "SELECT first_name, middle_name, last_name, birthday FROM evacuees WHERE id = ?";
$fetchStmt = $conn->prepare($fetchQuery);
$fetchStmt->bind_param("i", $evacueeId);
$fetchStmt->execute();
$result = $fetchStmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No evacuee found with the given ID.']);
    exit;
}

$evacueeDetails = $result->fetch_assoc();
$firstName = $evacueeDetails['first_name'];
$middleName = $evacueeDetails['middle_name'];
$lastName = $evacueeDetails['last_name'];
$birthday = $evacueeDetails['birthday'];

// Delete the current evacuee
$deleteQuery = "DELETE FROM evacuees WHERE id = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("i", $evacueeId);
$deleteStmt->execute();

if ($deleteStmt->affected_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete evacuee.']);
    exit;
}

// Find another evacuee matching the fetched details and status "Transfer"
$findQuery = "SELECT id FROM evacuees 
              WHERE first_name = ? AND middle_name = ? AND last_name = ? AND birthday = ? 
              AND status = 'Transfer'";
$findStmt = $conn->prepare($findQuery);
$findStmt->bind_param("ssss", $firstName, $middleName, $lastName, $birthday);
$findStmt->execute();
$findResult = $findStmt->get_result();

if ($findResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No matching evacuee found with status "Transfer".']);
    exit;
}

$matchingEvacuee = $findResult->fetch_assoc();
$matchingEvacueeId = $matchingEvacuee['id'];

// Update the matching evacuee's status to "Admitted"
$updateQuery = "UPDATE evacuees SET status = 'Admitted' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $matchingEvacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Log the admission for the matching evacuee
    $logMsg = "Transfer declined";
    $status = "notify";
    $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->bind_param("ssi", $logMsg, $status, $matchingEvacueeId);
    $logStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Evacuee successfully admitted.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to admit evacuee.']);
}

exit;
?>