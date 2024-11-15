<?php
require_once '../../connection/conn.php';

// Get the JSON payload from the request
$data = json_decode(file_get_contents('php://input'), true);
$supplyId = $data['supply_id'];
$evacueeData = $data['evacuee_data'];

// Initialize response array
$response = ['success' => false];

// Start a database transaction
$conn->begin_transaction();

try {
    foreach ($evacueeData as $evacuee) {
        $distributeId = $evacuee['distribute_id'];
        $redistributeQty = (int) $evacuee['quantity'];

        // Get the existing quantity for the distribute entry by distribute_id
        $distQuery = "SELECT quantity FROM distribute WHERE id = ?";
        $distStmt = $conn->prepare($distQuery);
        $distStmt->bind_param("i", $distributeId);
        $distStmt->execute();
        $distResult = $distStmt->get_result();

        if ($distResult->num_rows > 0) {
            $distRow = $distResult->fetch_assoc();
            $currentQty = $distRow['quantity'];

            if ($redistributeQty > $currentQty) {
                throw new Exception("Redistribution quantity exceeds current available quantity for distribute ID $distributeId.");
            }

            // Deduct quantity in distribute table
            $newQty = $currentQty - $redistributeQty;
            $updateDistQuery = "UPDATE distribute SET quantity = ? WHERE id = ?";
            $updateDistStmt = $conn->prepare($updateDistQuery);
            $updateDistStmt->bind_param("ii", $newQty, $distributeId);
            $updateDistStmt->execute();

            if ($newQty == 0) {
                $deleteDistQuery = "DELETE FROM distribute WHERE id = ?";
                $deleteDistStmt = $conn->prepare($deleteDistQuery);
                $deleteDistStmt->bind_param("i", $distributeId);
                $deleteDistStmt->execute();
            }

            // Fetch supply current and original quantity
            $supplyQuery = "SELECT quantity, original_quantity FROM supply WHERE id = ?";
            $supplyStmt = $conn->prepare($supplyQuery);
            $supplyStmt->bind_param("i", $supplyId);
            $supplyStmt->execute();
            $supplyResult = $supplyStmt->get_result();

            if ($supplyResult->num_rows > 0) {
                $supplyRow = $supplyResult->fetch_assoc();
                $currentSupplyQty = $supplyRow['quantity'];
                $originalQtyLimit = $supplyRow['original_quantity'];

                // Calculate how much can go into supply and if there is any excess
                $transferToSupply = min($originalQtyLimit - $currentSupplyQty, $redistributeQty);
                $excessQty = max(0, $redistributeQty - $transferToSupply);

                // Update supply table with quantity up to original limit
                $newSupplyQty = $currentSupplyQty + $transferToSupply;
                $updateSupplyQuery = "UPDATE supply SET quantity = ? WHERE id = ?";
                $updateSupplyStmt = $conn->prepare($updateSupplyQuery);
                $updateSupplyStmt->bind_param("ii", $newSupplyQty, $supplyId);
                $updateSupplyStmt->execute();

                // If there is excess, distribute to stock entries
                while ($excessQty > 0) {
                    $stockQuery = "SELECT id, quantity, original_quantity FROM stock WHERE supply_id = ? ORDER BY date ASC, time ASC LIMIT 1";
                    $stockStmt = $conn->prepare($stockQuery);
                    $stockStmt->bind_param("i", $supplyId);
                    $stockStmt->execute();
                    $stockResult = $stockStmt->get_result();

                    if ($stockResult->num_rows > 0) {
                        $stockRow = $stockResult->fetch_assoc();
                        $stockId = $stockRow['id'];
                        $currentStockQty = $stockRow['quantity'];
                        $stockOriginalQtyLimit = $stockRow['original_quantity'];

                        $stockTransferQty = min($stockOriginalQtyLimit - $currentStockQty, $excessQty);
                        $newStockQty = $currentStockQty + $stockTransferQty;
                        $excessQty -= $stockTransferQty;

                        // Update stock entry with the transferred quantity
                        $updateStockQuery = "UPDATE stock SET quantity = ? WHERE id = ?";
                        $updateStockStmt = $conn->prepare($updateStockQuery);
                        $updateStockStmt->bind_param("ii", $newStockQty, $stockId);
                        $updateStockStmt->execute();
                    } else {
                        // If no matching stock entry found, exit loop
                        break;
                    }
                }
            }
        }
    }

    // Commit transaction if all updates succeed
    $conn->commit();
    $response['success'] = true;
} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>