<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $from = $_POST['from'];
    $quantity = $_POST['quantityStock'];
    $unit = $_POST['unitStock'];
    $supplyId = $_POST['supply_id'];

    $query = "INSERT INTO stock (date, time, `from`, quantity, unit, supply_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisi", $date, $time, $from, $quantity, $unit, $supplyId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Stock added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add stock."]);
    }

    $stmt->close();
    $conn->close();
}
?>