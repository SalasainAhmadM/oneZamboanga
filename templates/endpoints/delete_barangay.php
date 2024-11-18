<?php
header('Content-Type: application/json');
require_once '../../connection/conn.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = intval($data['id']);

    $stmt = $conn->prepare("DELETE FROM barangay WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete barangay']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>