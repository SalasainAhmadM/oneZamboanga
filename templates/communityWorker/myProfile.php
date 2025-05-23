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
validateSession('worker');

if (isset($_SESSION['user_id'])) {
    $worker_id = $_SESSION['user_id'];

    // Fetch the worker's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, age, birthday, email, image, proof_image, gender, city, barangay, contact, position 
    FROM worker 
    WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();
        $first_name = $worker['first_name'];
        $middle_name = $worker['middle_name'];
        $last_name = $worker['last_name'];
        $extension_name = $worker['extension_name'];
        $email = $worker['email'];
        $worker_image = $worker['image'];
        $gender = $worker['gender'];
        $birthday = $worker['birthday'];
        $age = $worker['age'];
        $city = $worker['city'];
        $barangay = $worker['barangay'];
        $contact = $worker['contact'];
        $position = $worker['position'];
        $proof_image = $worker['proof_image'];

        $worker_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $extension_name);

    } else {
        $first_name = $middle_name = $last_name = $extension_name = $email = '';
    }
} else {
    header("Location: ../../login.php");
    exit;
}
$worker_image = !empty($worker['image']) ? $worker['image'] : "../../assets/img/undraw_male_avatar_g98d.svg";
$proof_image = !empty($worker['proof_image']) ? $worker['proof_image'] : "../../assets/img/captain.webp";
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

            <!-- <div class="logout">
                <div class="link">
                    <a href="login.php">
                        <p>Click to <b>Logout</b></p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
                
            </div>
            <a class="logout logout-icon" href="login.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a> -->
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
                            <a href="myProfile.php">My Profile</a>

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

                            <div class="profileOption active" id="profile">
                                <i class="fa-regular fa-user"></i>
                                <p>My Profile</p>
                            </div>

                            <div class="profileOption" id="edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <p>Edit Profile</p>
                            </div>

                            <div class="profileOption" id="password">
                                <i class="fa-solid fa-gear"></i>
                                <p>Settings</p>
                            </div>
                        </div>
                    </div>


                    <!-- ======myProfile====== -->
                    <div class="profile-right" id="myProfile">
                        <h2><?php echo htmlspecialchars($worker_name); ?></h2>

                        <div class="right-wrapper">
                            <img src="<?php echo htmlspecialchars($worker_image); ?>" alt="">

                            <ul class="profileDetails">
                                <li>
                                    <p>Address: <span><?php echo htmlspecialchars($city); ?></span></p>
                                </li>
                                <li>
                                    <p>Gender: <span><?php echo htmlspecialchars($gender); ?></span></p>
                                </li>
                                <li>
                                    <p>Barangay: <span><?php echo htmlspecialchars($barangay); ?></span></p>
                                </li>
                                <li>
                                    <p>Contact Information: <span><?php echo htmlspecialchars($contact); ?></span></p>
                                </li>
                                <li>
                                    <p>Email: <span><?php echo htmlspecialchars($email); ?></span></p>
                                </li>
                                <li>
                                    <p>Position: <span><?php echo htmlspecialchars($position); ?></span></p>
                                </li>
                            </ul>

                            <button id="openProof">Proof of appointment</button>
                        </div>

                        <div class="proof">
                            <i class="fa-solid fa-xmark" id="closeProof"></i>
                            <img class="proof_image" src="<?php echo htmlspecialchars($proof_image); ?>" alt="">
                        </div>

                    </div>



                    <!-- ======Edit profile====== -->
                    <div class="profile-right" id="editProfile">
                        <h2>Edit Profile</h2>

                        <form action="" class="editProfile-container" enctype="multipart/form-data">
                            <div class="inputProfile-wrapper">

                                <div class="inputProfile">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" class="last_name" name="last_name"
                                        value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" class="first_name" name="first_name"
                                        value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" id="middle_name" class="middle_name" name="middle_name"
                                        value="<?php echo htmlspecialchars($middle_name); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="extension_name">Extension Name</label>
                                    <select id="extension_name" class="extension_name" name="extension_name" disabled>
                                        <option value="" <?php echo ($extension_name === "") ? 'selected' : ''; ?>>
                                            None</option>
                                        <option value="Jr." <?php echo ($extension_name === "Jr.") ? 'selected' : ''; ?>>
                                            Jr.</option>
                                        <option value="Sr." <?php echo ($extension_name === "Sr.") ? 'selected' : ''; ?>>
                                            Sr.</option>
                                        <option value="II" <?php echo ($extension_name === "II") ? 'selected' : ''; ?>>II
                                        </option>
                                        <option value="III" <?php echo ($extension_name === "III") ? 'selected' : ''; ?>>
                                            III</option>
                                        <option value="IV" <?php echo ($extension_name === "IV") ? 'selected' : ''; ?>>IV
                                        </option>
                                        <option value="V" <?php echo ($extension_name === 'V') ? 'selected' : ''; ?>>V
                                        </option>
                                    </select>
                                </div>

                                <div class="inputProfile">
                                    <label for="birthday">Birthday</label>
                                    <input type="date" id="birthday" class="birthday" name="birthday"
                                        value="<?php echo htmlspecialchars($worker['birthday']); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="age">Age</label>
                                    <input type="number" name="age" id="age" class="age"
                                        value="<?php echo htmlspecialchars($worker['age']); ?>" placeholder="Enter Age"
                                        required readonly>
                                </div>
                                <script>

                                    document.getElementById('birthday').addEventListener('change', function () {
                                        const birthday = new Date(this.value);

                                        // Get the current date in Asia/Manila timezone
                                        const now = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" }));

                                        if (birthday > now) {
                                            document.getElementById('age').value = 0;
                                            return;
                                        }

                                        let age = now.getFullYear() - birthday.getFullYear();
                                        const isBirthdayPassedThisYear =
                                            now.getMonth() > birthday.getMonth() ||
                                            (now.getMonth() === birthday.getMonth() && now.getDate() >= birthday.getDate());

                                        if (!isBirthdayPassedThisYear) {
                                            age--;
                                        }

                                        document.getElementById('age').value = age > 0 ? age : 0;
                                    });
                                </script>
                                <div class="inputProfile">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="gender">
                                        <option value="" <?php echo empty($worker['gender']) ? 'selected' : ''; ?>
                                            disabled>
                                            Select
                                        </option>
                                        <option value="Male" <?php echo ($worker['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($worker['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>

                                <div class="inputProfile">
                                    <label for="city">City/Province</label>
                                    <input type="text" id="city" class="city" name="city"
                                        value="<?php echo htmlspecialchars($worker['city']); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="barangay">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" class="barangay"
                                        value="<?php echo htmlspecialchars($worker['barangay']); ?>"
                                        placeholder="Enter Barangay" required readonly>
                                </div>


                                <div class="inputProfile">
                                    <label for="contact">Contact Information</label>
                                    <input type="number" id="contact" class="contact" name="contact"
                                        value="<?php echo htmlspecialchars($worker['contact']); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="email" name="email"
                                        value="<?php echo htmlspecialchars($email); ?>">
                                </div>

                                <div class="inputProfile">
                                    <label for="position">Position</label>
                                    <input type="text" id="position" class="position" name="position"
                                        value="<?php echo htmlspecialchars($worker['position']); ?>" readonly>
                                </div>

                                <div class="inputProfile">
                                    <label for="image">Change Photo</label>
                                    <input type="file" id="image" class="image" name="image">

                                </div>

                                <div class="inputProfile">
                                    <label for="proof_image">Proof of Appointment</label>
                                    <input type="file" id="proof_image" class="proof_image" name="proof_image">
                                </div>

                            </div>
                            <button class="mainBtn" id="save">Save</button>
                        </form>


                    </div>



                    <!-- ======change password====== -->
                    <div class="profile-right" id="passProfile">
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
                                        value="<?php echo htmlspecialchars($worker['username']); ?>">
                                </div>
                            </div>

                            <button class="mainBtn" id="save_settings" style="margin-top: 1em;">Save</button>
                        </form>

                    </div>


                </div>
            </div>
        </main>

    </div>

    <script>
        document.getElementById('save').addEventListener('click', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Save Changes?',
                text: "Are you sure you want to save your changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Collect form data
                    const formData = new FormData();
                    formData.append('first_name', document.getElementById('first_name').value);
                    formData.append('middle_name', document.getElementById('middle_name').value);
                    formData.append('last_name', document.getElementById('last_name').value);
                    formData.append('extension_name', document.getElementById('extension_name').value);
                    formData.append('gender', document.getElementById('gender').value);
                    formData.append('birthday', document.getElementById('birthday').value);
                    formData.append('age', document.getElementById('age').value);
                    formData.append('city', document.getElementById('city').value);
                    formData.append('barangay', document.getElementById('barangay').value);
                    formData.append('contact', document.getElementById('contact').value);
                    formData.append('email', document.getElementById('email').value);
                    formData.append('position', document.getElementById('position').value);

                    // Add the image file if present
                    const imageFile = document.getElementById('image').files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }

                    // Add the proof of appointment file if present
                    const proofFile = document.getElementById('proof_image').files[0];
                    if (proofFile) {
                        formData.append('proof_image', proofFile);
                    }

                    // Send AJAX request
                    fetch('../endpoints/update_worker_profile.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Saved!', 'Your changes have been saved.', 'success');
                            } else {
                                Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Failed to update profile.', 'error');
                            console.error('Error:', error);
                        });
                }
            });
        });


        document.getElementById('save_settings').addEventListener('click', function (event) {
            event.preventDefault();

            // Get the input values
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const username = document.getElementById('username').value;

            // Validate password match
            if (newPassword !== confirmPassword) {
                Swal.fire('Error!', 'Passwords do not match!', 'error');
                return;
            }

            Swal.fire({
                title: 'Save Changes?',
                text: "Are you sure you want to save your changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Collect form data
                    const formData = new FormData();
                    formData.append('username', username);
                    formData.append('new_password', newPassword);

                    // Send AJAX request
                    fetch('../endpoints/update_worker_settings.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Saved!', 'Your changes have been saved.', 'success');
                            } else {
                                Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Failed to update settings.', 'error');
                            console.error('Error:', error);
                        });
                }
            });
        });

    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <!-- pop up add form -->
    <script src="../../assets/src/utils/addEc-popup.js"></script>


    <script>
        // Select all profileOption elements
        const profileOptions = document.querySelectorAll('.profileOption');

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
        const profileBtn = document.getElementById('profile')
        const editBtn = document.getElementById('edit');
        const passBtn = document.getElementById('password');
        const myProfile = document.getElementById('myProfile');
        const editProfile = document.getElementById('editProfile');
        const passProfile = document.getElementById('passProfile');

        // show profile
        profileBtn.addEventListener('click', function () {
            myProfile.style.display = 'block';
            editProfile.style.display = 'none';
            passProfile.style.display = 'none';
        });

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











</body>

</html>