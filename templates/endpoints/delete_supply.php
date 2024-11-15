<?php
require_once '../../connection/conn.php';

// Check if supply ID is set
if (isset($_POST['id'])) {
    $supplyId = $_POST['id'];

    // Prepare the delete query
    $query = "DELETE FROM supply WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $supplyId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No supply ID provided.']);
}

$conn->close();
?>