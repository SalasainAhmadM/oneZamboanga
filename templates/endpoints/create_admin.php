<?php
session_start();

require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
include("../../connection/conn.php");

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $extensionName = mysqli_real_escape_string($conn, $_POST['extensionName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $contact = mysqli_real_escape_string($conn, $_POST['contactInfo']);

    // Determine the role (default to 'admin')
    $role = 'admin';
    if (isset($_POST['role']) && $_POST['role'] == 'superadmin') {
        $role = 'superadmin';
    }

    // Check if email already exists in `admin` or `worker` table
    $checkEmailQuery = "SELECT email FROM admin WHERE email = '$email' UNION SELECT email FROM worker WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        $_SESSION['message'] = "Email Already Registered";
        $_SESSION['message_type'] = "error";
        header("Location: ../admin/addAdmin.php");
        exit();
    }

    // Generate username and password
    $username = $lastName . ucfirst($role);
    $password = bin2hex(random_bytes(4)); // Random 8-character password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Securely hash password

    // Define upload directories
    $profileDir = "../../assets/uploads/profiles/";
    $appointmentDir = "../../assets/uploads/appointments/";
    $barangayLogoDir = "../../assets/uploads/barangay/";

    // Ensure directories exist or create them
    if (!is_dir($profileDir))
        mkdir($profileDir, 0777, true);
    if (!is_dir($appointmentDir))
        mkdir($appointmentDir, 0777, true);
    if (!is_dir($barangayLogoDir))
        mkdir($barangayLogoDir, 0777, true);

    // Process uploaded files
    $proofImage = '';
    $profileImage = '';
    $barangayLogo = '';

    if ($_FILES['proofOfAppointment']['name']) {
        $proofImage = $appointmentDir . basename($_FILES['proofOfAppointment']['name']);
        move_uploaded_file($_FILES['proofOfAppointment']['tmp_name'], $proofImage);
    }
    if ($_FILES['photo']['name']) {
        $profileImage = $profileDir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $profileImage);
    }
    if ($_FILES['barangay_logo']['name']) {
        $barangayLogo = $barangayLogoDir . basename($_FILES['barangay_logo']['name']);
        move_uploaded_file($_FILES['barangay_logo']['tmp_name'], $barangayLogo);
    }

    // Generate a verification code
    $verificationCode = bin2hex(random_bytes(5));

    // Insert into the database
    $sql = "INSERT INTO admin (
        first_name, middle_name, last_name, extension_name, email, username, password, image, gender,
        position, city, barangay, barangay_logo, contact, role, proof_image, verification_code
    ) VALUES (
        '$firstName', '$middleName', '$lastName', '$extensionName', '$email', '$username', '$hashedPassword',
        '$profileImage', '$gender', '$position', '$city', '$barangay', '$barangayLogo', '$contact', '$role', '$proofImage', '$verificationCode'
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
            $confirmUrl = "http://localhost/onezamboanga/templates/endpoints/verify_account.php?email=$email&code=$verificationCode";

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'One Zamboanga Admin Account Registration';
            $mail->Body = "
                <h1>Welcome, $firstName $lastName!</h1>
                <p>Your admin account has been successfully created.</p>
                <p><strong>Username:</strong> $username</p>
                <p><strong>Password:</strong> $password</p>
                <p>Please use the above credentials to log in. We recommend changing your password upon first login.</p>
                <p><a href='$confirmUrl' style='display:inline-block;padding:10px 20px;color:#ffffff;background-color:#475569;border-radius:5px;text-decoration:none;'>Confirm Your Account</a></p>
            ";

            $mail->send();
            $_SESSION['message'] = "Admin registered successfully. Credentials have been emailed.";
            $_SESSION['message_type'] = "success";
        } catch (Exception $e) {
            $_SESSION['message'] = "Error: {$mail->ErrorInfo}";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = 'Error: ' . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the admin page
    header("Location: ../admin/addAdmin.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>