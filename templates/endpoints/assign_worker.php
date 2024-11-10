<?php
session_start();
include("../../connection/conn.php");

header('Content-Type: application/json');

// Check if all required data is set
if (isset($_POST['selected_workers'], $_POST['evacuation_center_id'], $_SESSION['user_id'])) {
    $selected_workers = $_POST['selected_workers'];
    $evacuation_center_id = $_POST['evacuation_center_id'];
    $admin_id = $_SESSION['user_id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Query to assign or update worker status
        $sql_check = "SELECT worker_id FROM assigned_worker WHERE evacuation_center_id = ?";
        $sql_insert = "INSERT INTO assigned_worker (worker_id, evacuation_center_id, status) VALUES (?, ?, 'assigned')";
        $sql_update_status = "UPDATE assigned_worker SET status = ? WHERE worker_id = ? AND evacuation_center_id = ?";

        $stmt_check = $conn->prepare($sql_check);
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_update_status = $conn->prepare($sql_update_status);

        // First, fetch all currently assigned workers for this evacuation center
        $stmt_check->bind_param("i", $evacuation_center_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        // Store current assignments to handle unassignments later
        $currently_assigned_workers = [];
        while ($row = $result->fetch_assoc()) {
            $currently_assigned_workers[] = $row['worker_id'];
        }

        // Loop through selected workers and assign them if they’re not already assigned
        foreach ($selected_workers as $worker_id) {
            if (in_array($worker_id, $currently_assigned_workers)) {
                // Update status to 'assigned' if the worker is already in the list
                $status = 'assigned';
                $stmt_update_status->bind_param("sii", $status, $worker_id, $evacuation_center_id);
                $stmt_update_status->execute();
            } else {
                // Insert new assignment if the worker isn't currently assigned
                $stmt_insert->bind_param("ii", $worker_id, $evacuation_center_id);
                $stmt_insert->execute();
            }
        }

        // Update any workers not selected to "unassigned" status
        foreach ($currently_assigned_workers as $worker_id) {
            if (!in_array($worker_id, $selected_workers)) {
                $status = 'unassigned';
                $stmt_update_status->bind_param("sii", $status, $worker_id, $evacuation_center_id);
                $stmt_update_status->execute();
            }
        }

        // Commit the transaction
        $conn->commit();

        // Set session messages for success alert
        $_SESSION['assign_message'] = 'Workers successfully assigned!';
        $_SESSION['assign_message_title'] = 'Assignment Successful';
        $_SESSION['assign_message_type'] = 'success';

        echo json_encode(['status' => 'success', 'message' => $_SESSION['assign_message'], 'type' => $_SESSION['assign_message_type'], 'title' => $_SESSION['assign_message_title']]);
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();


        echo json_encode(['status' => 'error', 'message' => $_SESSION['assign_message'], 'type' => $_SESSION['assign_message_type'], 'title' => $_SESSION['assign_message_title']]);
    }

    // Close statements
    $stmt_check->close();
    $stmt_insert->close();
    $stmt_update_status->close();
} else {
    // Set session messages for missing data alert
    $_SESSION['assign_message'] = 'Error: Please select workers and ensure the evacuation center is specified.';
    $_SESSION['assign_message_title'] = 'Input Error';
    $_SESSION['assign_message_type'] = 'error';

    echo json_encode(['status' => 'error', 'message' => $_SESSION['assign_message'], 'type' => $_SESSION['assign_message_type']]);
}
exit;
?>