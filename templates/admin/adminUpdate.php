<?php
session_start();
include("../../connection/conn.php");
require_once '../../connection/auth.php';
date_default_timezone_set('Asia/Manila');

// Set the timeout duration (in seconds)
define('INACTIVITY_LIMIT', 300); // 5 minutes

// Check if the user has been inactive for the defined limit
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_LIMIT)) {
    // Destroy the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: ../../login.php?message=Session expired due to inactivity.");
    exit();
}

// Update the last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Validate session role
validateSession('superadmin');

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Fetch the admin's details based on their ID
    $admin_query = "SELECT first_name, middle_name, last_name, extension_name, email, image, city, barangay, 
                     contact, gender, position, proof_image 
                     FROM admin 
                     WHERE id = ?";
    $admin_stmt = $conn->prepare($admin_query);
    $admin_stmt->bind_param("i", $admin_id);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    // Check if the admin exists
    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();

        // Define variables for admin's details
        $admin_name = trim($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name'] . ' ' . $admin['extension_name']);
        $admin_image = !empty($admin['image']) ? htmlspecialchars($admin['image']) : "../../assets/img/undraw_male_avatar_g98d.svg";
        $address = htmlspecialchars($admin['city']);
        $gender = htmlspecialchars($admin['gender']);
        $barangay = htmlspecialchars($admin['barangay']);
        $contact = htmlspecialchars($admin['contact']);
        $email = htmlspecialchars($admin['email']);
        $position = htmlspecialchars($admin['position']);
        $proof_image = htmlspecialchars($admin['proof_image']);
    } else {
        echo "admin not found.";
        exit;
    }
} else {
    echo "No admin ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../../assets/img/zambo.png">

    <!--Font Awesome-->
    <link rel="stylesheet" href="../../assets/fontawesome/all.css">
    <link rel="stylesheet" href="../../assets/fontawesome/fontawesome.min.css">
    <!--styles-->

    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/utils/ecenter.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/myProfile.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

<body>

    <div class="container">

        <aside class="left-section">
            <special-logo></special-logo>
            <!-- <div class="logo">
                <button class="menu-btn" id="menu-close">
                    <i class="fa-regular fa-circle-left"></i>
                </button>
                <img src="../../assets/img/logo5.png" alt="">
                <a href="#">One Zamboanga</a>
            </div> -->

            <special-sidebar></special-sidebar>

            <div class="pic">
                <img src="../../assets/img/zambo.png" alt="">
            </div>

            <special-logout></special-logout>

        </aside>

        <main>
            <header>
                <button class="menu-btn" id="menu-open">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <!-- <h5>Hello <b>Mark</b>, welcome back!</h5> -->
                <div class="separator">
                    <div class="info">
                        <div class="info-header">
                            <a href="#">Admin Profile</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Tetuan Evacuation Centers</a> -->
                        </div>





                        <!-- <button class="addBg-admin">
                            Create
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <div class="profile-left">
                        <div class="left-wrapper">

                            <!-- <div class="profileOption" id="profile">
                                <i class="fa-regular fa-user"></i>
                                <p>My Profile</p>
                            </div> -->

                            <div class="profileOption active" id="edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <p>Edit Admin Profile</p>
                            </div>

                            <!-- <div class="profileOption" id="password">
                                <i class="fa-solid fa-gear"></i>
                                <p>Settings</p>
                            </div> -->
                        </div>
                    </div>




                    <div class="profile-right" id="myProfile">
                        <h2>Edit Admin Profile</h2>

                        <form id="editProfileForm" action="../endpoints/update_admin.php" method="POST"
                            class="editProfile-container" enctype="multipart/form-data">
                            <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">

                            <div class="inputProfile-wrapper">
                                <div class="inputProfile">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name"
                                        value="<?php echo htmlspecialchars($admin['last_name']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name"
                                        value="<?php echo htmlspecialchars($admin['first_name']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name"
                                        value="<?php echo htmlspecialchars($admin['middle_name']); ?>">
                                </div>

                                <div class="inputProfile">
                                    <label for="extension_name">Extension Name</label>
                                    <input type="text" name="extension_name"
                                        value="<?php echo htmlspecialchars($admin['extension_name']); ?>">
                                </div>

                                <div class="inputProfile">
                                    <label for="gender">Gender</label>
                                    <select name="gender" required>
                                        <option value="Male" <?php echo $admin['gender'] === 'Male' ? 'selected' : ''; ?>>
                                            Male</option>
                                        <option value="Female" <?php echo $admin['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>

                                <div class="inputProfile">
                                    <label for="city">Full Address</label>
                                    <input type="text" name="city"
                                        value="<?php echo htmlspecialchars($admin['city']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="barangay">Barangay</label>
                                    <input type="text" name="barangay"
                                        value="<?php echo htmlspecialchars($admin['barangay']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="contact">Contact Information</label>
                                    <input type="text" name="contact"
                                        value="<?php echo htmlspecialchars($admin['contact']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="email">Email</label>
                                    <input type="email" name="email"
                                        value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="position">Position</label>
                                    <input type="text" name="position"
                                        value="<?php echo htmlspecialchars($admin['position']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="image">Change Photo</label>
                                    <input type="file" name="image" accept="image/*">
                                </div>

                                <div class="inputProfile">
                                    <label for="proof_image">Proof of Appointment</label>
                                    <input type="file" name="proof_image" accept="image/*">
                                </div>

                                <div class="inputProfile">
                                    <label for="barangay_logo">Barangay Logo</label>
                                    <input type="file" name="barangay_logo" accept="image/*">
                                </div>

                            </div>

                            <button type="button" class="mainBtn" id="save">Save</button>
                        </form>
                    </div>

                    <script>
                        document.getElementById('save').addEventListener('click', function () {
                            Swal.fire({
                                title: "Are you sure?",
                                text: "Do you want to save the changes to this profile?",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, save it!",
                                cancelButtonText: "Cancel"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const form = document.getElementById('editProfileForm');
                                    const formData = new FormData(form);

                                    fetch(form.action, {
                                        method: 'POST',
                                        body: formData
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire({
                                                    title: "Success!",
                                                    text: data.message,
                                                    icon: "success",
                                                    confirmButtonColor: "#3085d6"
                                                }).then(() => {
                                                    // Redirect or reload page
                                                    window.location.href = `viewProfile.php?id=${formData.get('admin_id')}`;
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: "Error",
                                                    text: data.message,
                                                    icon: "error",
                                                    confirmButtonColor: "#d33"
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Error:", error);
                                            Swal.fire({
                                                title: "Error",
                                                text: "An unexpected error occurred.",
                                                icon: "error",
                                                confirmButtonColor: "#d33"
                                            });
                                        });
                                }
                            });
                        });

                    </script>




                    <!-- ======change password====== -->
                    <!-- <div class="profile-right" id="passProfile">
                        <h2>Settings</h2>

                        <form action="" class="passProfile-container">
                            <div class="inputProfile-wrapper">
                                <div class="inputProfile">
                                    <label for="new_password">New Password</label>
                                    <input type="password" id="new_password">
                                </div>

                                <div class="inputProfile">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" id="confirm_password">
                                </div>

                                <div class="inputProfile">
                                    <label for="username">Change username</label>
                                    <input type="text" id="username"
                                        value="<?php echo htmlspecialchars($admin['username']); ?>">
                                </div>
                            </div>

                            <button class="mainBtn" id="save_settings" style="margin-top: 1em;">Save</button>
                        </form>


                    </div> -->


                </div>
            </div>
        </main>

    </div>
    <script>

    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>


    <!-- pop up add form -->
    <script src="../../assets/src/utils/addEc-popup.js"></script>


    <script>
        // Select all profileOption elements
        const profileOptions = document.querySelectorAll('.editProfile');

        // Function to handle click event
        profileOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove active class from all options
                profileOptions.forEach(opt => opt.classList.remove('active'));

                // Add active class to the clicked option
                option.classList.add('active');
            });
        });

    </script>


    <!-- profile options -->
    <script>
        const editBtn = document.getElementById('edit');
        const passBtn = document.getElementById('password');
        const editProfile = document.getElementById('editProfile');
        const passProfile = document.getElementById('passProfile');

        // show profile
        // profileBtn.addEventListener('click', function () {
        //     myProfile.style.display = 'block';
        //     editProfile.style.display = 'none';
        //     passProfile.style.display = 'none';
        // });

        // show edit
        editBtn.addEventListener('click', function () {
            editProfile.style.display = 'block';
            myProfile.style.display = 'none';
            passProfile.style.display = 'none';
        });

        // show pass
        passBtn.addEventListener('click', function () {
            passProfile.style.display = 'block';
            myProfile.style.display = 'none';
            editProfile.style.display = 'none';
        });
    </script>


    <!-- proof of appointment -->
    <script>
        const openProof = document.getElementById('openProof');
        const proof = document.querySelector('.proof');
        const closeProof = document.getElementById('closeProof');

        openProof.addEventListener('click', function () {
            proof.style.display = 'block';

            console.log('hello');
        });

        closeProof.addEventListener('click', function () {
            proof.style.display = 'none';
        })

    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>