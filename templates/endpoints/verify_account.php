<?php
session_start();
include("../../connection/conn.php");

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $code = mysqli_real_escape_string($conn, $_GET['code']);

    $query = "SELECT * FROM admin WHERE email = '$email' AND verification_code = '$code'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $updateQuery = "UPDATE admin SET verification_code = NULL WHERE email = '$email'";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['message'] = "Account confirmed successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating account: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid verification link.";
        $_SESSION['message_type'] = "error";
    }
}

header("Location: ../../login.php");
exit();
?>