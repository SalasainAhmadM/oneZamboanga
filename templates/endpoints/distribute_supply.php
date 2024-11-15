<?php
require_once '../../connection/conn.php';

$data = json_decode(file_get_contents("php://input"), true);
$evacueeData = $data['evacuee_data'] ?? [];
$supplyId = $data['supply_id'] ?? 0;

if (empty($evacueeData) || $supplyId == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$conn->begin_transaction();

try {
    // Fetch the supply details including name, unit, and quantity
    $supplyQuery = $conn->prepare("SELECT name, unit, quantity FROM supply WHERE id = ?");
    $supplyQuery->bind_param("i", $supplyId);
    $supplyQuery->execute();
    $supplyResult = $supplyQuery->get_result();
    $supplyRow = $supplyResult->fetch_assoc();

    $supplyName = $supplyRow['name'] ?? "Unknown Supply";
    $supplyUnit = $supplyRow['unit'] ?? "piece";
    $supplyQuantity = $supplyRow['quantity'] ?? 0;

    $totalRequestedQuantity = array_sum(array_column($evacueeData, 'quantity'));

    // Check total available quantity (supply and stock)
    $stockQuery = $conn->prepare("SELECT SUM(quantity) AS stock_quantity FROM stock WHERE supply_id = ?");
    $stockQuery->bind_param("i", $supplyId);
    $stockQuery->execute();
    $stockResult = $stockQuery->get_result();
    $totalStockQuantity = $stockResult->fetch_assoc()['stock_quantity'] ?? 0;

    $totalAvailableQuantity = $supplyQuantity + $totalStockQuantity;

    // If requested quantity exceeds available quantity, return an error
    if ($totalRequestedQuantity > $totalAvailableQuantity) {
        echo json_encode(['success' => false, 'message' => 'Not Enough Supplies!']);
        $conn->rollback();
        exit;
    }

    foreach ($evacueeData as $evacuee) {
        $evacueeId = $evacuee['evacuee_id'];
        $requestedQuantity = $evacuee['quantity'];
        $distributedQuantity = 0;

        // Fetch evacuee details for feed message
        $evacueeQuery = $conn->prepare("SELECT admin_id, last_name FROM evacuees WHERE id = ?");
        $evacueeQuery->bind_param("i", $evacueeId);
        $evacueeQuery->execute();
        $evacueeResult = $evacueeQuery->get_result();
        $evacueeInfo = $evacueeResult->fetch_assoc();

        $adminId = $evacueeInfo['admin_id'];
        $lastName = $evacueeInfo['last_name'];

        if ($requestedQuantity <= $supplyQuantity) {
            // Deduct directly from supply
            $updateSupplyStmt = $conn->prepare("UPDATE supply SET quantity = quantity - ? WHERE id = ?");
            $updateSupplyStmt->bind_param("ii", $requestedQuantity, $supplyId);
            $updateSupplyStmt->execute();
            $supplyQuantity -= $requestedQuantity;
            $distributedQuantity = $requestedQuantity;
        } else {
            // Partially deduct from supply, rest from stock
            if ($supplyQuantity > 0) {
                $distributedQuantity += $supplyQuantity;
                $requestedQuantity -= $supplyQuantity;
                $updateSupplyStmt = $conn->prepare("UPDATE supply SET quantity = 0 WHERE id = ?");
                $updateSupplyStmt->bind_param("i", $supplyId);
                $updateSupplyStmt->execute();
                $supplyQuantity = 0;
            }

            $stockStmt = $conn->prepare("SELECT id, quantity FROM stock WHERE supply_id = ? AND quantity > 0 ORDER BY date ASC, time ASC");
            $stockStmt->bind_param("i", $supplyId);
            $stockStmt->execute();
            $stockResult = $stockStmt->get_result();

            while ($requestedQuantity > 0 && $stockRow = $stockResult->fetch_assoc()) {
                $stockId = $stockRow['id'];
                $stockQuantity = $stockRow['quantity'];

                if ($requestedQuantity <= $stockQuantity) {
                    $updateStockStmt = $conn->prepare("UPDATE stock SET quantity = quantity - ? WHERE id = ?");
                    $updateStockStmt->bind_param("ii", $requestedQuantity, $stockId);
                    $updateStockStmt->execute();
                    $distributedQuantity += $requestedQuantity;
                    $requestedQuantity = 0;
                } else {
                    $updateStockStmt = $conn->prepare("UPDATE stock SET quantity = 0 WHERE id = ?");
                    $updateStockStmt->bind_param("i", $stockId);
                    $updateStockStmt->execute();
                    $distributedQuantity += $stockQuantity;
                    $requestedQuantity -= $stockQuantity;
                }
            }
        }

        $distributeStmt = $conn->prepare("INSERT INTO distribute (supply_name, date, evacuees_id, supply_id, quantity, distributor_id, distributor_type) VALUES (?, NOW(), ?, ?, ?, ?, 'admin')");
        $distributeStmt->bind_param("siiii", $supplyName, $evacueeId, $supplyId, $distributedQuantity, $adminId);
        $distributeStmt->execute();


        $unit = ($distributedQuantity > 1) ? $supplyUnit . 's' : $supplyUnit;

        $feedMessage = "{$distributedQuantity} {$unit} of {$supplyName} distributed to {$lastName}.";

        $feedStmt = $conn->prepare("INSERT INTO feeds (logged_in_id, user_type, feed_msg, status) VALUES (?, 'admin', ?, 'notify')");
        $feedStmt->bind_param("is", $adminId, $feedMessage);
        $feedStmt->execute();

    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Supplies distributed successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error distributing supplies']);
}
?>