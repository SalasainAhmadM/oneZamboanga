<?php
session_start();
require_once '../../connection/conn.php';

$category = $_POST['category'];
$adminId = $_POST['admin_id'];
$evacuationCenterId = $_POST['evacuation_center_id']; // Assuming this is posted or retrieved

// Prepare the SQL query to insert the new category
$insertCategorySql = "INSERT INTO category (name, admin_id) VALUES (?, ?)";
$insertCategoryStmt = $conn->prepare($insertCategorySql);
$insertCategoryStmt->bind_param("si", $category, $adminId);

if ($insertCategoryStmt->execute()) {
    $_SESSION['message'] = "Category successfully added.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error adding category.";
    $_SESSION['message_type'] = "error";
}

// Redirect to resourceSupply.php with the evacuationCenterId
header("Location: ../barangay/resourceSupply.php?id=" . $evacuationCenterId);
exit();
?>