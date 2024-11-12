<?php
session_start();
include("../../connection/conn.php");

if (isset($_POST['user_id']) && isset($_POST['user_type'])) {
    $user_id = $_POST['user_id'];
    $user_type = $_POST['user_type'];

    // Update all notifications to "cleared"
    $update_query = "UPDATE notifications SET status = 'cleared' WHERE logged_in_id = ? AND user_type = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("is", $user_id, $user_type);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
    $stmt->close();
}
?>