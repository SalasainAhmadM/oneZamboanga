<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $admin_id = isset($data['admin_id']) ? intval($data['admin_id']) : 0;

    if ($admin_id > 0) {
        $query = "SELECT id, name FROM evacuation_center WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $centers = [];
        while ($row = $result->fetch_assoc()) {
            $centers[] = $row;
        }
        echo json_encode(['success' => true, 'centers' => $centers]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid admin_id']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>