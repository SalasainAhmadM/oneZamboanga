<?php
header('Content-Type: application/json');
require_once '../../connection/conn.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['name'])) {
    $id = intval($data['id']);
    $name = trim($data['name']);

    $stmt = $conn->prepare("UPDATE barangay SET name = ? WHERE id = ?");
    $stmt->bind_param('si', $name, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update barangay']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>