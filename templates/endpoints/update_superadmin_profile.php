<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $extension_name = $_POST['extension_name'];
        $email = $_POST['email'];
        $image_path = '';

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $query = "SELECT image FROM admin WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $stmt->bind_result($current_image);
            $stmt->fetch();
            $stmt->close();

            $image_path = $current_image;
        } else {
            $uploadDir = '../../assets/uploads/profiles/';
            $image_name = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_path = '../../assets/uploads/profiles/' . $image_name;
            } else {
                echo json_encode(['success' => false, 'message' => 'File upload failed.']);
                exit;
            }
        }

        // Update the database with new values
        $sql = "UPDATE admin SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, email = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $extension_name, $email, $image_path, $admin_id);

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