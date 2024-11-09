<?php
include("../../connection/conn.php");

if (isset($_GET['id'])) {
    $worker_id = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM worker WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $worker_id);

    if ($delete_stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}
?>