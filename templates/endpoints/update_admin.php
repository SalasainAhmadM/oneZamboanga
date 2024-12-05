<?php
require_once '../../connection/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $admin_id = intval($_POST['admin_id']);
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
        $role = 'admin';

        // Directories for uploads
        $profileDir = "../../assets/uploads/profiles/";
        $appointmentDir = "../../assets/uploads/appointments/";
        $logoDir = "../../assets/uploads/barangay/";

        // Variables for file uploads
        $profile_image = $_FILES['image'] ?? null;
        $appointment_image = $_FILES['proof_image'] ?? null;
        $barangay_logo = $_FILES['barangay_logo'] ?? null;
        $profile_image_path = null;
        $appointment_image_path = null;
        $barangay_logo_path = null;

        // Validate and handle profile image upload
        if (!empty($profile_image['name'])) {
            $profile_image_name = $profileDir . time() . "_" . basename($profile_image['name']);
            if (!move_uploaded_file($profile_image['tmp_name'], $profile_image_name)) {
                throw new Exception('Failed to upload profile image.');
            }
            $profile_image_path = $profile_image_name;
        }

        // Validate and handle proof of appointment image upload
        if (!empty($appointment_image['name'])) {
            $appointment_image_name = $appointmentDir . time() . "_" . basename($appointment_image['name']);
            if (!move_uploaded_file($appointment_image['tmp_name'], $appointment_image_name)) {
                throw new Exception('Failed to upload proof of appointment image.');
            }
            $appointment_image_path = $appointment_image_name;
        }

        // Validate and handle barangay logo upload
        if (!empty($barangay_logo['name'])) {
            $barangay_logo_name = $logoDir . time() . "_" . basename($barangay_logo['name']);
            if (!move_uploaded_file($barangay_logo['tmp_name'], $barangay_logo_name)) {
                throw new Exception('Failed to upload barangay logo.');
            }
            $barangay_logo_path = $barangay_logo_name;
        }

        // Prepare SQL query
        $query = "
            UPDATE admin 
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
                position = ?,
                role = ?";

        if ($profile_image_path)
            $query .= ", image = ?";
        if ($appointment_image_path)
            $query .= ", proof_image = ?";
        if ($barangay_logo_path)
            $query .= ", barangay_logo = ?";
        $query .= " WHERE id = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

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
            $position,
            $role
        ];

        if ($profile_image_path)
            $params[] = $profile_image_path;
        if ($appointment_image_path)
            $params[] = $appointment_image_path;
        if ($barangay_logo_path)
            $params[] = $barangay_logo_path;
        $params[] = $admin_id;

        $types = str_repeat('s', count($params) - 1) . 'i';
        $stmt->bind_param($types, ...$params);

        // Execute and respond
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Admin profile updated successfully.']);
        } else {
            throw new Exception('Execution failed: ' . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>