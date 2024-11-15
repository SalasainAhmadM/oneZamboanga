<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];
} else {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $evacuation_id = mysqli_real_escape_string($conn, $_POST['evacuation_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);

    // Get the original name before updating
    $originalNameQuery = "SELECT name FROM evacuation_center WHERE id = ?";
    $originalStmt = $conn->prepare($originalNameQuery);
    $originalStmt->bind_param("i", $evacuation_id);
    $originalStmt->execute();
    $originalStmt->bind_result($originalName);
    $originalStmt->fetch();
    $originalStmt->close();

    // Prepare file upload if a new image was provided
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/uploads/evacuation_centers/';
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $imagePath = '../../assets/uploads/evacuation_centers/' . $imageName;
        } else {
            $_SESSION['message'] = "Failed to upload new image.";
            $_SESSION['message_type'] = "warning";
        }
    }

    // Update evacuation center information
    $sqlUpdate = "UPDATE evacuation_center SET name = ?, location = ?, capacity = ?";
    if ($imagePath) {
        $sqlUpdate .= ", image = ?";
    }
    $sqlUpdate .= " WHERE id = ? AND admin_id = ?";

    $stmt = $conn->prepare($sqlUpdate);
    if ($imagePath) {
        $stmt->bind_param("ssssii", $name, $location, $capacity, $imagePath, $evacuation_id, $admin_id);
    } else {
        $stmt->bind_param("ssiii", $name, $location, $capacity, $evacuation_id, $admin_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Evacuation Center updated successfully!";
        $_SESSION['message_type'] = "success";

        // Insert notification for update if it doesn't already exist
        $notification_msg = "Evacuation Center $originalName Updated to $name.";
        $checkNotificationQuery = "SELECT COUNT(*) FROM notifications WHERE logged_in_id = ? AND notification_msg = ?";
        $checkStmt = $conn->prepare($checkNotificationQuery);
        $checkStmt->bind_param("is", $admin_id, $notification_msg);
        $checkStmt->execute();
        $checkStmt->bind_result($notificationExists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($notificationExists == 0) {
            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) VALUES (?, 'admin', ?, 'notify')";
            $notifStmt = $conn->prepare($notificationQuery);
            $notifStmt->bind_param("is", $admin_id, $notification_msg);
            $notifStmt->execute();
            $notifStmt->close();
        }

        // Insert notification for superadmin
        $notification_msg = "Evacuation Center $originalName Updated to $name in Barangay " . $barangay;
        $checkNotificationQuery = "SELECT COUNT(*) FROM notifications WHERE logged_in_id = ? AND notification_msg = ?";
        $checkStmt = $conn->prepare($checkNotificationQuery);
        $checkStmt->bind_param("is", $admin_id, $notification_msg);
        $checkStmt->execute();
        $checkStmt->bind_result($notificationExists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($notificationExists == 0) {
            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) VALUES ('1', 'admin', ?, 'notify')";
            $notifStmt = $conn->prepare($notificationQuery);
            $notifStmt->bind_param("s", $notification_msg);
            $notifStmt->execute();
            $notifStmt->close();
        }



    } else {
        $_SESSION['message'] = "Error updating Evacuation Center.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: ../barangay/viewEC.php?id=" . $evacuation_id);
    exit();
}
?>