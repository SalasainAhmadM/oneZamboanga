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

                    <a href="#" class="forgot" onclick="showForgotPasswordModal()">Forgot Password?</a>

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
    <script>
        function showForgotPasswordModal() {
            Swal.fire({
                title: 'Forgot Password',
                html: `
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="resetEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="resetEmail" placeholder="Enter your registered email" required>
                    </div>
                </form>
            `,
                showCancelButton: true,
                confirmButtonText: 'Reset Password',
                preConfirm: () => {
                    const resetEmail = document.getElementById('resetEmail').value.trim();

                    if (!resetEmail) {
                        Swal.showValidationMessage('Please enter your email');
                        return false;
                    }

                    return { resetEmail };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    console.log("Sending reset email for:", result.value.resetEmail); // Log for debugging

                    // Show a loading spinner
                    Swal.fire({
                        title: 'Sending Email...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('./endpoints/forgot-password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=forgot_password&email=${encodeURIComponent(result.value.resetEmail)}`
                    })
                        .then(response => {
                            console.log(response); // Log the response
                            return response.json();
                        })
                        .then(data => {
                            console.log(data); // Log the parsed data
                            if (data.status === 'success') {
                                Swal.fire('Success', data.message, 'success').then(() => {
                                    // Reload the page after success confirmation
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error); // Log network errors
                            Swal.fire('Error', 'An error occurred while processing your request. Please try again.', 'error');
                        });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const resetToken = urlParams.get('reset_token');

            if (!resetToken) {
                console.log('No reset token found in the URL. Reset modal will not be displayed.');
                return;
            }

            Swal.fire({
                title: 'Reset Password',
                input: 'password',
                inputLabel: 'Enter your new password',
                inputAttributes: {
                    maxlength: 50,
                    autocapitalize: 'off',
                    autocorrect: 'off',
                },
                showCancelButton: true,
                confirmButtonText: 'Reset Password',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Password cannot be empty!';
                    }
                    if (value.length < 6) {
                        return 'Password must be at least 6 characters long.';
                    }
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const newPassword = result.value;

                    fetch('./endpoints/reset-password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            token: resetToken,
                            new_password: newPassword,
                        }),
                    })
                        .then((response) => response.json().then((data) => ({ status: response.status, body: data })))
                        .then(({ status, body }) => {
                            if (status === 200 && body.status === 'success') {
                                Swal.fire('Success', body.message, 'success').then(() => {
                                    window.location.href = 'http://localhost/onezamboanga/login.php';
                                });
                            } else {
                                Swal.fire('Error', body.message || 'Something went wrong.', 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
                        });
                }
            });
        });

    </script>

</body>

</html>