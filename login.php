<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Font Awesome-->
    <link rel="stylesheet" href="assets//fontawesome/all.css">
    <link rel="stylesheet" href="assets/fontawesome/fontawesome.min.css">
    <!--styles-->
    <link rel="stylesheet" href="assets/styles/style.css">
    <link rel="stylesheet" href="assets/styles/utils/login.css">

    <link rel="icon" href="assets/img/zambo.png">

    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

<body>

    <div class="login-container">
        <a href="index.php" class="back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="login-left">
            <img src="assets/img/loginIcon.png" alt="">
        </div>

        <div class="login-right">
            <div class="login-wrapper">
                <div class="header">

                    <h2>Login <span>to</span></h2>
                    <h2 class="start">to get started</h2>
                </div>

                <form action="./endpoints/login.php" method="POST">
                    <div class="loginInput">
                        <input type="text" name="username" class="username" placeholder="Username" required>
                    </div>

                    <div class="loginInput">
                        <input type="password" name="password" class="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="loginBtn">Login</button>

                    <a href="#" class="forgot">Forgot Password?</a>
                </form>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{$_SESSION['error_message']}'
            });
          </script>";
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageType = $_SESSION['message_type'];
        echo "<script>
            Swal.fire({
                icon: '$messageType',
                title: '" . ($messageType == "success" ? "Success" : "Oops...") . "',
                text: '$message'
            });
        </script>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

</body>

</html>