<?php
session_start();
require_once '../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$username || !$password) {
        $_SESSION['error_message'] = 'Please enter both username and password.';
        header("Location: ../login.php");
        exit();
    }

    // Check for admin user
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_success'] = true;

            // Update last_login and set status to 'active' for the current user
            $updateQuery = "UPDATE admin SET last_login = NOW(), status = 'active' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();

            // Set other users as 'inactive' if they haven't logged in for over a week
            $updateInactiveQuery = "UPDATE admin SET status = 'inactive' WHERE last_login < DATE_SUB(NOW(), INTERVAL 7 DAY) AND id != ?";
            $updateInactiveStmt = $conn->prepare($updateInactiveQuery);
            $updateInactiveStmt->bind_param("i", $user['id']);
            $updateInactiveStmt->execute();

            // Redirect based on the user role
            if ($user['role'] === 'admin') {
                header("Location: ../templates/barangay/dashboard_barangay.php");
            } elseif ($user['role'] === 'superadmin') {
                header("Location: ../templates/admin/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error_message'] = 'Invalid password.';
            header("Location: ../login.php");
            exit();
        }
    } else {
        // Check for worker user if no admin found
        $query = "SELECT * FROM worker WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $worker = $result->fetch_assoc();

            if (password_verify($password, $worker['password'])) {
                $_SESSION['user_id'] = $worker['id'];
                $_SESSION['user_role'] = 'worker';
                $_SESSION['login_success'] = true;

                // Update last_login and set status to 'active' for the current worker
                $updateQuery = "UPDATE worker SET last_login = NOW(), status = 'active' WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $worker['id']);
                $updateStmt->execute();

                // Set other workers as 'inactive' if they haven't logged in for over a week
                $updateInactiveQuery = "UPDATE worker SET status = 'inactive' WHERE last_login < DATE_SUB(NOW(), INTERVAL 7 DAY) AND id != ?";
                $updateInactiveStmt = $conn->prepare($updateInactiveQuery);
                $updateInactiveStmt->bind_param("i", $worker['id']);
                $updateInactiveStmt->execute();

                header("Location: ../templates/communityWorker/dashboard_communityWorker.php");
                exit();
            } else {
                $_SESSION['error_message'] = 'Invalid password.';
                header("Location: ../login.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'No account found with that username.';
            header("Location: ../login.php");
            exit();
        }
    }
} else {
    $_SESSION['error_message'] = 'Please submit the form using the POST method.';
    header("Location: ../login.php");
}
?>