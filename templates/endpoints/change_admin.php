<?php
session_start();

require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
include("../../connection/conn.php");

// Check if the admin is logged in and get the admin_id from the session
if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];
} else {
    // Redirect to login if not logged in
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Update the status of the existing admin to "done"
        $current_admin_id = intval($_POST['admin_id']);
        $updateStatusQuery = "UPDATE admin SET status = 'done' WHERE id = ?";
        $updateStmt = $conn->prepare($updateStatusQuery);
        if (!$updateStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $updateStmt->bind_param("i", $current_admin_id);
        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update admin status: " . $updateStmt->error);
        }
        $updateStmt->close();

        // Retrieve and sanitize form data for the new admin
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $extension_name = mysqli_real_escape_string($conn, $_POST['extension_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = $last_name . "Admin";
        $password = bin2hex(random_bytes(4));
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
        $role = 'admin';
        $status = 'inactive';
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        // File upload directories
        $profileDir = "../../assets/uploads/profiles/";
        $appointmentDir = "../../assets/uploads/appointments/";
        $barangayLogoDir = "../../assets/uploads/barangay/";
        // Generate a verification code
        $verificationCode = bin2hex(random_bytes(5));
        if (!is_dir($profileDir))
            mkdir($profileDir, 0777, true);
        if (!is_dir($appointmentDir))
            mkdir($appointmentDir, 0777, true);
        if (!is_dir($barangayLogoDir))
            mkdir($barangayLogoDir, 0777, true);

        $profile_image = '';
        $proof_image = '';
        $barangay_logo = '';

        if ($_FILES['proof_image']['name']) {
            $proof_image = $appointmentDir . basename($_FILES['proof_image']['name']);
            move_uploaded_file($_FILES['proof_image']['tmp_name'], $proof_image);
        }
        if ($_FILES['image']['name']) {
            $profile_image = $profileDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $profile_image);
        }
        if ($_FILES['barangay_logo']['name']) {
            $barangay_logo = $barangayLogoDir . basename($_FILES['barangay_logo']['name']);
            move_uploaded_file($_FILES['barangay_logo']['tmp_name'], $barangay_logo);
        }

        // Insert the new admin into the database
        $insertAdminQuery = "
            INSERT INTO admin (
                first_name, middle_name, last_name, extension_name, email, username, password, 
                image, gender, age, birthday, position, city, barangay, barangay_logo, contact, role, proof_image, status, verification_code
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
        $insertStmt = $conn->prepare($insertAdminQuery);
        if (!$insertStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $insertStmt->bind_param(
            "sssssssssissssssssss",
            $first_name,
            $middle_name,
            $last_name,
            $extension_name,
            $email,
            $username,
            $hashedPassword,
            $profile_image,
            $gender,
            $age,
            $birthday,
            $position,
            $city,
            $barangay,
            $barangay_logo,
            $contact,
            $role,
            $proof_image,
            $status,
            $verificationCode
        );

        if (!$insertStmt->execute()) {
            throw new Exception("Failed to create new admin: " . $insertStmt->error);
        }
        // Retrieve the newly created admin ID
        $new_admin_id = $conn->insert_id;

        // Update evacuation centers' admin_id to the new admin ID
        $updateEvacuationCentersQuery = "UPDATE evacuation_center SET admin_id = ? WHERE admin_id = ?";
        $updateEvacuationStmt = $conn->prepare($updateEvacuationCentersQuery);
        if (!$updateEvacuationStmt) {
            throw new Exception("Prepare failed for evacuation center update: " . $conn->error);
        }
        $updateEvacuationStmt->bind_param("ii", $new_admin_id, $current_admin_id);
        if (!$updateEvacuationStmt->execute()) {
            throw new Exception("Failed to update evacuation centers: " . $updateEvacuationStmt->error);
        }
        $updateEvacuationStmt->close();

        // Update evacuees' admin_id to the new admin ID
        $updateEvacueesQuery = "UPDATE evacuees SET admin_id = ? WHERE admin_id = ?";
        $updateEvacueesStmt = $conn->prepare($updateEvacueesQuery);
        if (!$updateEvacueesStmt) {
            throw new Exception("Prepare failed for evacuees update: " . $conn->error);
        }
        $updateEvacueesStmt->bind_param("ii", $new_admin_id, $current_admin_id);
        if (!$updateEvacueesStmt->execute()) {
            throw new Exception("Failed to update evacuees: " . $updateEvacueesStmt->error);
        }
        $updateEvacueesStmt->close();

        // Update workers' admin_id to the new admin ID
        $updateWorkersQuery = "UPDATE worker SET admin_id = ? WHERE admin_id = ?";
        $updateWorkersStmt = $conn->prepare($updateWorkersQuery);
        if (!$updateWorkersStmt) {
            throw new Exception("Prepare failed for workers update: " . $conn->error);
        }
        $updateWorkersStmt->bind_param("ii", $new_admin_id, $current_admin_id);
        if (!$updateWorkersStmt->execute()) {
            throw new Exception("Failed to update workers: " . $updateWorkersStmt->error);
        }
        $updateWorkersStmt->close();

        $insertStmt->close();

        // Send email to the new admin
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'onezamboanga.2024@gmail.com'; // Your email address
            $mail->Password = 'llhlgsqqizbtifun'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // Recipients
            $mail->setFrom('onezamboanga.2024@gmail.com', 'One Zamboanga');
            $mail->addAddress($email); // Add the admin's email
            // Create confirmation URL
            $confirmUrl = "http://localhost/onezamboanga/templates/endpoints/verify_account.php?email=$email&code=$verificationCode";
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to One Zamboanga!';
            $mail->Body = "
                <h1>Welcome, $first_name $middle_name $last_name!</h1>
                <p>Your admin account has been created successfully.</p>
                <p><strong>Username:</strong> $username</p>
                <p><strong>Password:</strong> $password</p>
                <p>Please use the above credentials to log in. We recommend changing your password upon first login.</p>
                <p><a href='$confirmUrl' style='display:inline-block;padding:10px 20px;color:#ffffff;background-color:#475569;border-radius:5px;text-decoration:none;'>Confirm Your Account</a></p>
            ";

            $mail->send();
            $_SESSION['message'] = "Admin created successfully and email sent.";
            $_SESSION['message_type'] = "success";
        } catch (Exception $e) {
            throw new Exception("Failed to send email: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: ../admin/addAdmin.php");
    exit();
}

mysqli_close($conn);
?>