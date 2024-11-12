<?php
require_once '../../connection/conn.php';

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];
    $query = "DELETE FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting admin."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No admin ID provided."]);
}
?>