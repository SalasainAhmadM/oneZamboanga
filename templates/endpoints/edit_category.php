<?php
require_once '../../connection/conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$categoryId = $data['id'];
$newName = $data['name'];

$sql = "UPDATE category SET name = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newName, $categoryId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Update failed"]);
}
?>