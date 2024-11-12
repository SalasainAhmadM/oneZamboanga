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

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $extensionName = mysqli_real_escape_string($conn, $_POST['extensionName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $contact = mysqli_real_escape_string($conn, $_POST['contactInfo']);

    // Check if email already exists in `admin` or `worker` table
    $checkEmailQuery = "SELECT email FROM admin WHERE email = '$email' UNION SELECT email FROM worker WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        $_SESSION['message'] = "Email Already Registered";
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/addAccount.php");
        exit();
    }

    // Generate username and password
    $positionFormatted = str_replace(' ', '', $position); // Remove all spaces from position
    $username = $lastName . ucfirst($positionFormatted);
    $password = bin2hex(random_bytes(4)); // Generate a random 8-character password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);// Securely hash password

    // Define upload directories
    $profileDir = "../../assets/uploads/profiles/";
    $appointmentDir = "../../assets/uploads/appointments/";

    // Ensure directories exist or create them
    if (!is_dir($profileDir))
        mkdir($profileDir, 0777, true);
    if (!is_dir($appointmentDir))
        mkdir($appointmentDir, 0777, true);

    // Process uploaded files
    $proofImage = '';
    $profileImage = '';

    if ($_FILES['proofOfAppointment']['name']) {
        $proofImage = $appointmentDir . basename($_FILES['proofOfAppointment']['name']);
        move_uploaded_file($_FILES['proofOfAppointment']['tmp_name'], $proofImage);
    }
    if ($_FILES['photo']['name']) {
        $profileImage = $profileDir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $profileImage);
    }

    // Generate a verification code
    $verificationCode = bin2hex(random_bytes(5));

    // Insert into the database, including the admin_id
    $sql = "INSERT INTO worker (
        first_name, middle_name, last_name, extension_name, email, username, password, image, gender, age,
        position, city, barangay, contact, proof_image, verification_code, admin_id
    ) VALUES (
        '$firstName', '$middleName', '$lastName', '$extensionName', '$email', '$username', '$hashedPassword',
        '$profileImage', '$gender', '$age', '$position', '$city', '$barangay', '$contact', 
        '$proofImage', '$verificationCode', '$admin_id'
    )";

    if (mysqli_query($conn, $sql)) {
        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
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
            // Recipients
            $mail->setFrom('onezamboanga.2024@gmail.com', 'One Zamboanga');
            $mail->addAddress($email);

            // Create confirmation URL
            $confirmUrl = "http://localhost/onezamboanga/templates/endpoints/verify_account_worker.php?email=$email&code=$verificationCode";

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'One Zamboanga Community Account Registration';
            $mail->Body = "
                <h1>Welcome, $firstName $middleName $lastName!</h1>
                <p>Your community account has been successfully created.</p>
                <p><strong>Username:</strong> $username</p>
                <p><strong>Password:</strong> $password</p>
                <p>Please use the above credentials to log in. We recommend changing your password upon first login.</p>
                <p><a href='$confirmUrl' style='display:inline-block;padding:10px 20px;color:#ffffff;background-color:#475569;border-radius:5px;text-decoration:none;'>Confirm Your Account</a></p>
            ";

            $mail->send();
            $_SESSION['message'] = "Community account registered successfully. Credentials have been emailed.";
            $_SESSION['message_type'] = "success";

            // Insert notification
            $notification_msg = "New Worker Account Added: $firstName $middleName $lastName";
            $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                                  VALUES ('$admin_id', 'admin', '$notification_msg', 'notify')";

            if (!mysqli_query($conn, $notificationQuery)) {
                $_SESSION['message'] = "Worker account created, but notification failed.";
                $_SESSION['message_type'] = "warning";
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Error: {$mail->ErrorInfo}";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = 'Error: ' . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the worker page
    header("Location: ../barangay/addAccount.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>