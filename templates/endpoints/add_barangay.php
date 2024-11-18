<?php
require_once '../../connection/conn.php';

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name']);

// Check if barangay already exists
$sql_check = "SELECT * FROM barangay WHERE name = '$name'";
$result_check = $conn->query($sql_check);

$response = ['success' => false];

if ($result_check->num_rows > 0) {
    // Barangay already exists
    $response['error'] = 'Barangay already exists';
} else {
    // Insert barangay
    $sql = "INSERT INTO barangay (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Database error';
    }
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>