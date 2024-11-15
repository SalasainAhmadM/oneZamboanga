<?php
require_once '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplyId = $_POST['id'];
    $name = $_POST['name'];
    $categoryId = $_POST['category_id'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity']; // Matches JavaScript field name
    $unit = $_POST['unit']; // Matches JavaScript field name

    $uploadDir = "../../assets/uploads/supplies/";

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $uploadDir . $imageName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
            exit;
        }
    } else {
        // Use existing image if no new image is uploaded
        $imageQuery = "SELECT image FROM supply WHERE id = ?";
        $stmt = $conn->prepare($imageQuery);
        $stmt->bind_param("i", $supplyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $image = $row['image'];
        $stmt->close();
    }

    // Fetch current quantity to determine if it has changed
    $currentQuantityQuery = "SELECT quantity, original_quantity FROM supply WHERE id = ?";
    $stmt = $conn->prepare($currentQuantityQuery);
    $stmt->bind_param("i", $supplyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentData = $result->fetch_assoc();
    $currentQuantity = $currentData['quantity'];
    $originalQuantity = $currentData['original_quantity'];
    $stmt->close();

    // Check if quantity increased, and update original_quantity if needed
    if ($quantity > $currentQuantity) {
        $originalQuantity = $quantity; // Update original quantity to new quantity
    }

    // Update the supply table with new values
    $query = "UPDATE supply SET name = ?, category_id = ?, description = ?, quantity = ?, original_quantity = ?, unit = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisisssi", $name, $categoryId, $description, $quantity, $originalQuantity, $unit, $image, $supplyId);

    if ($stmt->execute()) {
        // If the unit has changed, update the stock table's unit as well
        $updateStockUnitQuery = "UPDATE stock SET unit = ? WHERE supply_id = ?";
        $updateStockStmt = $conn->prepare($updateStockUnitQuery);
        $updateStockStmt->bind_param("si", $unit, $supplyId);
        $updateStockStmt->execute();
        $updateStockStmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>