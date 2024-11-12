<?php
include("../../connection/conn.php");
session_start();

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Query to get the latest evacuation center created by the admin
    $sql = "SELECT MAX(id) AS latest_center_id FROM evacuation_center WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latest_center_id = ($result->num_rows > 0) ? $result->fetch_assoc()['latest_center_id'] : 0;

    // Send the ID as a response
    echo json_encode(['latest_center_id' => $latest_center_id]);
}
?>