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

// Retrieve evacuee's data
$evacueeQuery = "SELECT * FROM evacuees WHERE id = ?";
$evacueeStmt = $conn->prepare($evacueeQuery);
$evacueeStmt->bind_param("i", $evacueeId);
$evacueeStmt->execute();
$evacueeResult = $evacueeStmt->get_result();

if ($evacueeResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Evacuee not found.']);
    exit;
}

$evacueeData = $evacueeResult->fetch_assoc();
$evacueeFullName = trim($evacueeData['first_name'] . ' ' . $evacueeData['middle_name'] . ' ' . $evacueeData['last_name']);

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

// Update the current evacuee's status to 'Transfer'
$updateQuery = "UPDATE evacuees SET status = 'Transfer' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $evacueeId);
$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    // Log the transfer for the current evacuee
    $logMsg = "Requesting transfer to $centerName";
    $logStatus = "notify";
    $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->bind_param("ssi", $logMsg, $logStatus, $evacueeId);
    $logStmt->execute();

    // Insert a new evacuee record with the same data, but updated evacuation_center_id
    $insertQuery = "
        INSERT INTO evacuees 
        (first_name, middle_name, last_name, extension_name, gender, position, disaster_type, barangay, birthday, age, occupation, contact, 
         monthly_income, damage, cost_damage, house_owner, admin_id, evacuation_center_id, origin_evacuation_center_id, date, status) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Transfer')
    ";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "sssssssssissssssiiis",
        $evacueeData['first_name'],
        $evacueeData['middle_name'],
        $evacueeData['last_name'],
        $evacueeData['extension_name'],
        $evacueeData['gender'],
        $evacueeData['position'],
        $evacueeData['disaster_type'],
        $evacueeData['barangay'],
        $evacueeData['birthday'],
        $evacueeData['age'],
        $evacueeData['occupation'],
        $evacueeData['contact'],
        $evacueeData['monthly_income'],
        $evacueeData['damage'],
        $evacueeData['cost_damage'],
        $evacueeData['house_owner'],
        $evacueeData['admin_id'],
        $centerId, // New center ID
        $evacueeData['evacuation_center_id'], // Origin center ID
        $evacueeData['date']
    );
    $insertStmt->execute();

    if ($insertStmt->affected_rows > 0) {
        $newEvacueeId = $conn->insert_id;

        // Log the transfer for the new evacuee
        $newLogMsg = "Requesting transfer to $centerName";
        $newLogQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
        $newLogStmt = $conn->prepare($newLogQuery);
        $newLogStmt->bind_param("ssi", $newLogMsg, $logStatus, $newEvacueeId);
        $newLogStmt->execute();

        // Check if evacuee has members
        $membersQuery = "SELECT * FROM members WHERE evacuees_id = ?";
        $membersStmt = $conn->prepare($membersQuery);
        $membersStmt->bind_param("i", $evacueeId);
        $membersStmt->execute();
        $membersResult = $membersStmt->get_result();

        if ($membersResult->num_rows > 0) {
            $insertMemberQuery = "
                INSERT INTO members 
                (first_name, middle_name, last_name, extension_name, relation, education, gender, age, occupation, evacuees_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $insertMemberStmt = $conn->prepare($insertMemberQuery);

            while ($member = $membersResult->fetch_assoc()) {
                $insertMemberStmt->bind_param(
                    "sssssssisi",
                    $member['first_name'],
                    $member['middle_name'],
                    $member['last_name'],
                    $member['extension_name'],
                    $member['relation'],
                    $member['education'],
                    $member['gender'],
                    $member['age'],
                    $member['occupation'],
                    $newEvacueeId
                );
                $insertMemberStmt->execute();
            }
        }

        // Create notification for admin
        $adminId = $evacueeData['admin_id'];
        $notificationMsg = "$evacueeFullName is requesting to transfer to $centerName";
        $notificationStatus = "notify";
        $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) VALUES (?, 'admin', ?, ?)";
        $notificationStmt = $conn->prepare($notificationQuery);
        $notificationStmt->bind_param("iss", $adminId, $notificationMsg, $notificationStatus);
        $notificationStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Evacuee and members successfully transferred with logs and notifications.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create the new evacuee record.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update the evacuee status.']);
}
exit;
?>