<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database, including barangay
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, email, image, proof_image, gender, city, barangay, contact, position 
            FROM admin 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_name = trim($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name'] . ' ' . $admin['extension_name']);
        $barangayAdmin = $admin['barangay'];
    } else {
        header("Location: ../../login.php");
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['evacuationCenterName']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $admin_id = $_POST['admin_id'];
    $logged_in_id = $admin_id;

    // Check if evacuation center with same name, location, and admin_id already exists
    $checkQuery = "SELECT * FROM evacuation_center WHERE name = '$name' AND location = '$location' AND admin_id = '$admin_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Evacuation center already exists
        $_SESSION['message'] = "Evacuation Center Already Created!";
        $_SESSION['message_type'] = "error";
    } else {
        // Handle file upload if available
        $uploadDir = '../../assets/uploads/evacuation_centers/';
        $imagePath = '';

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $imageName = $_FILES['photo']['name'];
            $imageTmpName = $_FILES['photo']['tmp_name'];
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $imagePath = '../../assets/uploads/evacuation_centers/' . $imageName;
            } else {
                $_SESSION['message'] = "Failed to upload image.";
                $_SESSION['message_type'] = "warning";
            }
        }

        // Insert new evacuation center if not already created
        $query = "INSERT INTO evacuation_center (name, location, barangay, image, capacity, admin_id) 
                  VALUES ('$name', '$location', '$barangay', '$imagePath', '$capacity', '$admin_id')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Evacuation Center created successfully!";
            $_SESSION['message_type'] = "success";

            // Insert notification
            $notification_msg = "New Evacuation Center Added: " . $name;
            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                                  VALUES ('$logged_in_id', 'admin', '$notification_msg', 'notify')";

            if (!mysqli_query($conn, $notificationQuery)) {
                $_SESSION['message'] = "Evacuation Center created, but notification failed.";
                $_SESSION['message_type'] = "warning";
            }

            // Insert notification for superadmin
            $notification_msg = "New Evacuation Center Added: " . $name . " at Barangay: " . $barangayAdmin;
            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                                  VALUES ('1', 'admin', '$notification_msg', 'notify')";

            if (!mysqli_query($conn, $notificationQuery)) {
                $_SESSION['message'] = "Evacuation Center created, but notification failed.";
                $_SESSION['message_type'] = "warning";
            }
        } else {
            $_SESSION['message'] = "Error creating Evacuation Center.";
            $_SESSION['message_type'] = "error";
        }
    }

    // Redirect back to evacuation centers page
    header("Location: ../barangay/evacuation.php");
    exit();
}
?>