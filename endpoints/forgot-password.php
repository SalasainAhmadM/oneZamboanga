<?php
session_start();
require_once('../connection/conn.php');
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // Validate email input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        http_response_code(400);
        exit;
    }

    // Check if the email exists in the database
    $query = "
        SELECT email, 'admin' AS role FROM admin WHERE email = ? 
        UNION 
        SELECT email, 'worker' AS role FROM worker WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        http_response_code(500);
        exit;
    }

    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check for a recent token
        $tokenCheckQuery = "
            SELECT expires_at 
            FROM password_reset_tokens 
            WHERE email = ? AND expires_at > NOW()";
        $tokenStmt = $conn->prepare($tokenCheckQuery);
        $tokenStmt->bind_param("s", $email);
        $tokenStmt->execute();
        $tokenResult = $tokenStmt->get_result();

        if ($tokenResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Password reset email already sent. Please check your inbox.']);
            http_response_code(429); // Too Many Requests
            exit;
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Asia/Manila time

        // Insert or overwrite token
        $insertTokenQuery = "
            INSERT INTO password_reset_tokens (email, token, expires_at) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)";
        $stmt = $conn->prepare($insertTokenQuery);
        $stmt->bind_param("sss", $email, $token, $expiresAt);
        $stmt->execute();

        // Send email
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'onezamboanga.2024@gmail.com'; // SMTP username
            $mail->Password = 'llhlgsqqizbtifun';   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom('onezamboanga.2024@gmail.com', 'One Zamboanga');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset your password';
            $mail->Body = "
            <h3>Password Reset Request</h3>
            <p>Click the link below to reset your password:</p>
            <a href='http://localhost/onezamboanga/login.php?reset_token=$token'>Reset Password</a>
            <p>If you did not request this, please ignore this email.</p>
        ";
            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Password reset email sent successfully. Please check your inbox.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email. Please try again later.']);
            http_response_code(500);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email not found in our records.']);
        http_response_code(404);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    http_response_code(405);
}
?>