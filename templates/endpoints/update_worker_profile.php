<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $worker_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get all form data
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $extension_name = $_POST['extension_name'];
        $gender = $_POST['gender'];
        $city = $_POST['city'];
        $barangay = $_POST['barangay'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $position = $_POST['position'];
        $image_path = '';
        $proof_image_path = '';

        // Handle the image file upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            // No new image, use existing image from the database
            $query = "SELECT image FROM worker WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $worker_id);
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
                $image_path = $targetFilePath;
            } else {
                echo json_encode(['success' => false, 'message' => 'Image upload failed.']);
                exit;
            }
        }

        // Handle proof_image file upload
        if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/uploads/appointments/';
            $proof_name = basename($_FILES['proof_image']['name']);
            $targetFilePath = $uploadDir . $proof_name;

            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $targetFilePath)) {
                $proof_image_path = $targetFilePath;
            } else {
                echo json_encode(['success' => false, 'message' => 'Proof of appointment upload failed.']);
                exit;
            }
        } else {
            // No new proof of appointment file, retain existing file if available
            $query = "SELECT proof_image FROM worker WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $worker_id);
            $stmt->execute();
            $stmt->bind_result($current_proof);
            $stmt->fetch();
            $stmt->close();
            $proof_image_path = $current_proof;
        }

        // Update the database with new values
        $sql = "UPDATE worker SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, gender = ?, city = ?, barangay = ?, contact = ?, email = ?, position = ?, image = ?, proof_image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssi", $first_name, $middle_name, $last_name, $extension_name, $gender, $city, $barangay, $contact, $email, $position, $image_path, $proof_image_path, $worker_id);

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