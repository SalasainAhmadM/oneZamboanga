<?php
require_once '../../connection/conn.php';

// Fetch barangays
$sql = "SELECT id, name FROM barangay";
$result = $conn->query($sql);

$barangays = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barangays[] = $row;
    }
}

// Return barangays as JSON
header('Content-Type: application/json');
echo json_encode($barangays);

$conn->close();
?>