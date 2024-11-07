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
                        <input type="email" name="email" class="email" placeholder="Email" required>
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




</body>

</html>