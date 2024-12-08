<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $evacuee_id = isset($data['evacuee_id']) ? intval($data['evacuee_id']) : 0;
    $admin_id = isset($data['admin_id']) ? intval($data['admin_id']) : 0;
    $center_id = isset($data['center_id']) ? intval($data['center_id']) : 0;

    // Retrieve evacuee's data
    $evacueeQuery = "SELECT * FROM evacuees WHERE id = ?";
    $evacueeStmt = $conn->prepare($evacueeQuery);
    $evacueeStmt->bind_param("i", $evacuee_id);
    $evacueeStmt->execute();
    $evacueeResult = $evacueeStmt->get_result();

    if ($evacueeResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Evacuee not found.']);
        exit;
    }

    $evacueeData = $evacueeResult->fetch_assoc();
    $evacueeFullName = trim($evacueeData['first_name'] . ' ' . $evacueeData['middle_name'] . ' ' . $evacueeData['last_name']);

    if ($evacuee_id > 0 && $admin_id > 0 && $center_id > 0) {
        // Fetch the barangay of the selected admin
        $barangayQuery = "SELECT barangay FROM admin WHERE id = ?";
        $barangayStmt = $conn->prepare($barangayQuery);
        $barangayStmt->bind_param("i", $admin_id);
        $barangayStmt->execute();
        $barangayResult = $barangayStmt->get_result();

        if ($barangayResult->num_rows > 0) {
            $admin = $barangayResult->fetch_assoc();
            $barangay = $admin['barangay'];

            // Fetch existing evacuee details
            $fetchQuery = "SELECT evacuation_center_id, first_name, middle_name, last_name, extension_name, gender, position, disaster_type, barangay, contact, birthday, age, occupation, monthly_income, damage, cost_damage, house_owner, date, status, admin_id 
                           FROM evacuees WHERE id = ?";
            $fetchStmt = $conn->prepare($fetchQuery);
            $fetchStmt->bind_param("i", $evacuee_id);
            $fetchStmt->execute();
            $fetchResult = $fetchStmt->get_result();

            if ($fetchResult->num_rows > 0) {
                $evacuee = $fetchResult->fetch_assoc();
                $origin_evacuation_center_id = $evacuee['evacuation_center_id'];

                // Fetch the name of the selected evacuation center
                $centerQuery = "SELECT name FROM evacuation_center WHERE id = ?";
                $centerStmt = $conn->prepare($centerQuery);
                $centerStmt->bind_param("i", $center_id);
                $centerStmt->execute();
                $centerResult = $centerStmt->get_result();

                if ($centerResult->num_rows > 0) {
                    $center = $centerResult->fetch_assoc();
                    $centerName = $center['name'];

                    // Update the status of the current evacuee to "Transfer"
                    $updateQuery = "UPDATE evacuees SET status = 'Transfer' WHERE id = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("i", $evacuee_id);

                    if ($updateStmt->execute()) {
                        // Log the transfer for the current evacuee
                        $logMsg = "Requesting transfer to $centerName";
                        $status = "notify";
                        $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
                        $logStmt = $conn->prepare($logQuery);
                        $logStmt->bind_param("ssi", $logMsg, $status, $evacuee_id);
                        $logStmt->execute();

                        // Insert a new evacuee record
                        $insertQuery = "INSERT INTO evacuees 
                                        (first_name, middle_name, last_name, extension_name, gender, 
                                        position, disaster_type, contact, birthday, age, 
                                        occupation, monthly_income, damage, cost_damage, house_owner,
                                         evacuation_center_id, origin_evacuation_center_id, barangay, admin_id, status) 
                                        VALUES (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,  ?, ?, ?, ?, 'Transfer')";
                        $insertStmt = $conn->prepare($insertQuery);
                        $insertStmt->bind_param(
                            "sssssssssisssssiisi",
                            $evacuee['first_name'],
                            $evacuee['middle_name'],
                            $evacuee['last_name'],
                            $evacuee['extension_name'],
                            $evacuee['gender'],
                            $evacuee['position'],
                            $evacuee['disaster_type'],
                            $evacuee['contact'],
                            $evacuee['birthday'],
                            $evacuee['age'],
                            $evacuee['occupation'],
                            $evacuee['monthly_income'],
                            $evacuee['damage'],
                            $evacuee['cost_damage'],
                            $evacuee['house_owner'],
                            $center_id,
                            $origin_evacuation_center_id,
                            $barangay,
                            $admin_id
                        );

                        if ($insertStmt->execute()) {
                            $newEvacueeId = $conn->insert_id;

                            // Duplicate members associated with the evacuee
                            $fetchMembersQuery = "SELECT first_name, middle_name, last_name, extension_name, relation, education, gender, age, occupation 
                                                  FROM members WHERE evacuees_id = ?";
                            $fetchMembersStmt = $conn->prepare($fetchMembersQuery);
                            $fetchMembersStmt->bind_param("i", $evacuee_id);
                            $fetchMembersStmt->execute();
                            $fetchMembersResult = $fetchMembersStmt->get_result();

                            if ($fetchMembersResult->num_rows > 0) {
                                $insertMemberQuery = "INSERT INTO members (first_name, middle_name, last_name, extension_name, relation, education, gender, age, occupation, evacuees_id)
                                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $insertMemberStmt = $conn->prepare($insertMemberQuery);

                                while ($member = $fetchMembersResult->fetch_assoc()) {
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

                            // Log the transfer for the new evacuee
                            $newLogMsg = "Requesting transfer to $centerName";
                            $newLogQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
                            $newLogStmt = $conn->prepare($newLogQuery);
                            $newLogStmt->bind_param("ssi", $newLogMsg, $status, $newEvacueeId);
                            $newLogStmt->execute();

                            // Create notification for admin
                            $notificationMsg = "$evacueeFullName is requesting to transfer to $centerName";
                            $notificationStatus = "notify";
                            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) VALUES (?, 'admin', ?, ?)";
                            $notificationStmt = $conn->prepare($notificationQuery);
                            $notificationStmt->bind_param("iss", $admin_id, $notificationMsg, $notificationStatus);
                            $notificationStmt->execute();

                            echo json_encode([
                                'success' => true,
                                'message' => 'Evacuee transfer process completed successfully.'
                            ]);
                        } else {
                            echo json_encode([
                                'success' => false,
                                'message' => 'Failed to insert new evacuee record.'
                            ]);
                        }

                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Failed to update evacuee status.'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Selected evacuation center not found.'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Evacuee not found.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Target admin not found.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid input. Ensure all fields are filled correctly.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>