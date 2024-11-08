<?php
session_start();
include("../../connection/conn.php");

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $new_password = $_POST['new_password'];

        // Prepare the SQL query based on whether the password is provided
        if (!empty($new_password)) {
            // Hash the new password for security
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update both username and password
            $sql = "UPDATE admin SET username = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $hashed_password, $admin_id);
        } else {
            // Update only the username if the password is empty
            $sql = "UPDATE admin SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $username, $admin_id);
        }

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
}
?>