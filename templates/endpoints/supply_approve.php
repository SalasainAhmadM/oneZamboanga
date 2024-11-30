<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplyId = $_POST['supply_id'];

    if (empty($supplyId)) {
        echo json_encode(['error' => 'Invalid Supply ID']);
        exit;
    }

    $sql = "UPDATE supply SET approved = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $supplyId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to approve supply']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>