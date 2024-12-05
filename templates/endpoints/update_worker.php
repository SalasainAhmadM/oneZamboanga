<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $worker_id = intval($_POST['worker_id']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $extension_name = trim($_POST['extension_name']);
    $gender = trim($_POST['gender']);
    $city = trim($_POST['city']);
    $barangay = trim($_POST['barangay']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $position = trim($_POST['position']);

    // Directories for uploads
    $profileDir = "../../assets/uploads/profiles/";
    $appointmentDir = "../../assets/uploads/appointments/";

    // Variables for file uploads
    $profile_image = $_FILES['image'];
    $appointment_image = $_FILES['proof_image'];
    $profile_image_path = null;
    $appointment_image_path = null;

    // Validate and handle profile image upload
    if (!empty($profile_image['name'])) {
        $profile_image_name = $profileDir . time() . "_" . basename($profile_image['name']); // Include directory in name
        if (!move_uploaded_file($profile_image['tmp_name'], $profile_image_name)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload profile image.']);
            exit();
        }
        $profile_image_path = $profile_image_name;
    }

    // Validate and handle proof of appointment image upload
    if (!empty($appointment_image['name'])) {
        $appointment_image_name = $appointmentDir . time() . "_" . basename($appointment_image['name']); // Include directory in name
        if (!move_uploaded_file($appointment_image['tmp_name'], $appointment_image_name)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload proof of appointment image.']);
            exit();
        }
        $appointment_image_path = $appointment_image_name;
    }

    // Prepare SQL query
    $query = "
        UPDATE worker 
        SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            extension_name = ?, 
            gender = ?, 
            city = ?, 
            barangay = ?, 
            contact = ?, 
            email = ?, 
            position = ?";

    // Append image paths if new files were uploaded
    if ($profile_image_path) {
        $query .= ", image = ?";
    }
    if ($appointment_image_path) {
        $query .= ", proof_image = ?";
    }

    $query .= " WHERE id = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($query);
    $params = [
        $first_name,
        $middle_name,
        $last_name,
        $extension_name,
        $gender,
        $city,
        $barangay,
        $contact,
        $email,
        $position
    ];

    if ($profile_image_path) {
        $params[] = $profile_image_path;
    }
    if ($appointment_image_path) {
        $params[] = $appointment_image_path;
    }

    $params[] = $worker_id;
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    // Execute and respond
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Worker profile updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update worker profile.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>