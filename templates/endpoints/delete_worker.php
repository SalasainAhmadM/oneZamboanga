<?php
session_start();
include("../../connection/conn.php");

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $worker_id = $_GET['id'];
    $logged_in_id = $_SESSION['user_id'];  // admin_id for notification tracking

    // Retrieve worker's name for the notification message
    $workerQuery = "SELECT first_name, last_name FROM worker WHERE id = ?";
    $workerStmt = $conn->prepare($workerQuery);
    $workerStmt->bind_param("i", $worker_id);
    $workerStmt->execute();
    $workerResult = $workerStmt->get_result();
    $worker = $workerResult->fetch_assoc();
    $worker_name = $worker ? $worker['first_name'] . ' ' . $worker['last_name'] : 'Unknown';

    // Delete query
    $delete_query = "DELETE FROM worker WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $worker_id);

    if ($delete_stmt->execute()) {
        // Insert notification
        $notification_msg = "Worker Account Has Been Deleted: " . $worker_name;
        $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                              VALUES (?, 'admin', ?, 'notify')";
        $notificationStmt = $conn->prepare($notificationQuery);
        $notificationStmt->bind_param("is", $logged_in_id, $notification_msg);

        if ($notificationStmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Worker deleted, but notification failed."]);
        }
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>