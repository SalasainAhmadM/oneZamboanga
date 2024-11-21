<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode incoming JSON request
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $evacuee_id = isset($data['evacuee_id']) ? intval($data['evacuee_id']) : 0;
    $admin_id = isset($data['admin_id']) ? intval($data['admin_id']) : 0;
    $center_id = isset($data['center_id']) ? intval($data['center_id']) : 0;

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

            // Fetch existing evacuee details for logging origin evacuation center
            $fetchQuery = "SELECT evacuation_center_id, first_name, last_name FROM evacuees WHERE id = ?";
            $fetchStmt = $conn->prepare($fetchQuery);
            $fetchStmt->bind_param("i", $evacuee_id);
            $fetchStmt->execute();
            $fetchResult = $fetchStmt->get_result();

            if ($fetchResult->num_rows > 0) {
                $evacuee = $fetchResult->fetch_assoc();
                $origin_evacuation_center_id = $evacuee['evacuation_center_id'];
                $evacueeFullName = $evacuee['first_name'] . ' ' . $evacuee['last_name'];

                // Fetch the name of the selected evacuation center
                $centerQuery = "SELECT name FROM evacuation_center WHERE id = ?";
                $centerStmt = $conn->prepare($centerQuery);
                $centerStmt->bind_param("i", $center_id);
                $centerStmt->execute();
                $centerResult = $centerStmt->get_result();

                if ($centerResult->num_rows > 0) {
                    $center = $centerResult->fetch_assoc();
                    $centerName = $center['name'];

                    // Update evacuee record with new admin_id, evacuation_center_id, barangay, and set status to 'Transfer'
                    $updateQuery = "UPDATE evacuees 
                                    SET admin_id = ?, evacuation_center_id = ?, origin_evacuation_center_id = ?, barangay = ?, status = 'Transfer'
                                    WHERE id = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("iiisi", $admin_id, $center_id, $origin_evacuation_center_id, $barangay, $evacuee_id);

                    if ($updateStmt->execute()) {
                        // Log the transfer with the center's name
                        $logMsg = "Requesting transfer to $centerName";
                        $status = "notify";
                        $logQuery = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, ?, ?)";
                        $logStmt = $conn->prepare($logQuery);
                        $logStmt->bind_param("ssi", $logMsg, $status, $evacuee_id);
                        $logStmt->execute();

                        // Create notification for admin
                        $notificationMsg = "$evacueeFullName is requesting to transfer to $centerName";
                        $notificationStatus = "notify";
                        $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) VALUES (?, 'admin', ?, ?)";
                        $notificationStmt = $conn->prepare($notificationQuery);
                        $notificationStmt->bind_param("iss", $admin_id, $notificationMsg, $notificationStatus);
                        $notificationStmt->execute();

                        echo json_encode([
                            'success' => true,
                            'message' => 'Evacuee successfully transferred to another barangay.'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Failed to update evacuee record.'
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