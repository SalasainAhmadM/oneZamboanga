<?php
session_start();
include("../../connection/conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['evacuationCenterName']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $admin_id = $_POST['admin_id'];

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
        $query = "INSERT INTO evacuation_center (name, location, image, capacity, admin_id) 
                  VALUES ('$name', '$location', '$imagePath', '$capacity', '$admin_id')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Evacuation Center created successfully!";
            $_SESSION['message_type'] = "success";
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