<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = (int) $_POST['quantity'];
    $unit = $_POST['unit'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $from = $_POST['from'];
    $evacuationCenterId = $_POST['evacuation_center_id'];
    $categoryId = $_POST['category_id'];

    $evacuationCenterName = $_POST['evacuation_center_name'];
    $adminId = $_POST['admin_id'];
    $logged_in_id = $adminId;

    $uploadDir = '../../assets/uploads/supplies/';
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = $targetFilePath;
        } else {
            echo json_encode(['error' => 'File upload failed.']);
            exit;
        }
    }

    $sql = "INSERT INTO supply (name, description, quantity, original_quantity, unit, date, time, `from`, image, evacuation_center_id, category_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisssssii", $name, $description, $quantity, $quantity, $unit, $date, $time, $from, $imagePath, $evacuationCenterId, $categoryId);

    if ($stmt->execute()) {
        $feed_msg = "$quantity " . ($quantity > 1 ? $unit . "s" : $unit) . " of $name added at $evacuationCenterName";
        $feedQuery = "INSERT INTO feeds (logged_in_id, user_type, feed_msg, status) 
                      VALUES (?, 'admin', ?, 'notify')";
        $feedStmt = $conn->prepare($feedQuery);
        $feedStmt->bind_param("is", $logged_in_id, $feed_msg);

        if ($feedStmt->execute()) {
            echo json_encode(['success' => 'Supply added successfully with feed']);
        } else {
            echo json_encode(['success' => 'Supply added, but feed failed']);
        }
        $feedStmt->close();
    } else {
        echo json_encode(['error' => 'Failed to add supply']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>