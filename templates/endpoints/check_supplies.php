<?php
require_once '../../connection/conn.php';

header('Content-Type: application/json');

$evacueeId = $_GET['id'] ?? null;

if (!$evacueeId) {
    echo json_encode(['success' => false, 'message' => 'Evacuee ID is missing.']);
    exit;
}

// Step 1: Fetch category IDs for "Starting Kit" and related categories
$requiredCategoryName = 'Starting Kit';
$categoryQuery = "SELECT id FROM category WHERE name = ?";
$categoryStmt = $conn->prepare($categoryQuery);
$categoryStmt->bind_param("s", $requiredCategoryName);
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();

$requiredCategoryIds = [];
while ($row = $categoryResult->fetch_assoc()) {
    $requiredCategoryIds[] = $row['id'];
}

if (empty($requiredCategoryIds)) {
    echo json_encode(['success' => false, 'message' => 'No required supply categories found.']);
    exit;
}

// Step 2: Check if evacuee has received supplies belonging to these categories
$placeholders = implode(',', array_fill(0, count($requiredCategoryIds), '?'));
$supplyQuery = "
    SELECT COUNT(*) as count 
    FROM distribute 
    INNER JOIN supply ON distribute.supply_id = supply.id 
    WHERE distribute.evacuees_id = ? AND supply.category_id IN ($placeholders)";
$supplyStmt = $conn->prepare($supplyQuery);

// Bind parameters dynamically
$supplyStmt->bind_param(
    str_repeat('i', count($requiredCategoryIds) + 1),
    $evacueeId,
    ...$requiredCategoryIds
);

$supplyStmt->execute();
$supplyResult = $supplyStmt->get_result()->fetch_assoc();

if ($supplyResult['count'] > 0) {
    echo json_encode(['success' => true, 'hasRequiredSupplies' => true]);
} else {
    echo json_encode(['success' => true, 'hasRequiredSupplies' => false]);
}

exit;
?>