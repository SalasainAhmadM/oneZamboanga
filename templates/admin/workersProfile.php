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
    $worker_id = $_GET['id'];

    // Fetch the worker's details based on their ID
    $worker_query = "SELECT first_name, middle_name, last_name, extension_name, email, image, city, barangay, 
                     contact, gender, position, proof_image 
                     FROM worker 
                     WHERE id = ?";
    $worker_stmt = $conn->prepare($worker_query);
    $worker_stmt->bind_param("i", $worker_id);
    $worker_stmt->execute();
    $worker_result = $worker_stmt->get_result();

    // Check if the worker exists
    if ($worker_result->num_rows > 0) {
        $worker = $worker_result->fetch_assoc();

        // Define variables for worker's details
        $worker_name = trim($worker['first_name'] . ' ' . $worker['middle_name'] . ' ' . $worker['last_name'] . ' ' . $worker['extension_name']);
        $worker_image = !empty($worker['image']) ? htmlspecialchars($worker['image']) : "../../assets/img/undraw_male_avatar_g98d.svg";
        $address = htmlspecialchars($worker['city']);
        $gender = htmlspecialchars($worker['gender']);
        $barangay = htmlspecialchars($worker['barangay']);
        $contact = htmlspecialchars($worker['contact']);
        $email = htmlspecialchars($worker['email']);
        $position = htmlspecialchars($worker['position']);
        $proof_image = htmlspecialchars($worker['proof_image']);
    } else {
        echo "Worker not found.";
        exit;
    }
} else {
    echo "No worker ID provided.";
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

            <div class="logout">
                <!-- <h5>Barangay Name or idk</h5> -->
                <div class="link">
                    <a href="login.php">
                        <p>Click to <b>Logout</b></p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>

            </div>
            <a class="logout logout-icon" href="login.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>

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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="workersProfile.php">Profile</a>
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
                                <p>Profile</p>
                            </div>


                            <div class="profileOption" id="edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <p>Edit Profile</p>
                            </div>

                            <div class="profileOption" id="deleteWorker"
                                onclick="confirmDelete(<?php echo $worker_id; ?>)">
                                <i class="fa-solid fa-trash"></i>
                                <p>Delete</p>
                            </div>


                            <!-- <div class="profileOption" id="password">
                                <i class="fa-solid fa-lock"></i>
                                <p>Change Password</p>
                            </div> -->
                        </div>
                    </div>


                    <!-- ======myProfile====== -->
                    <div class="profile-right" id="myProfile">
                        <h2><?php echo $worker_name; ?></h2>

                        <div class="right-wrapper">
                            <img src="<?php echo $worker_image; ?>" alt="Profile Image">

                            <ul class="profileDetails">
                                <li>
                                    <p>City/Province: <span><?php echo $address; ?></span></p>
                                </li>
                                <li>
                                    <p>Gender: <span><?php echo $gender; ?></span></p>
                                </li>
                                <li>
                                    <p>Barangay: <span><?php echo $barangay; ?></span></p>
                                </li>
                                <li>
                                    <p>Contact Information: <span><?php echo $contact; ?></span></p>
                                </li>
                                <li>
                                    <p>Email: <span><?php echo $email; ?></span></p>
                                </li>
                                <li>
                                    <p>Position: <span><?php echo $position; ?></span></p>
                                </li>
                            </ul>

                            <button id="openProof">Proof of Appointment</button>
                        </div>

                        <div class="proof">
                            <i class="fa-solid fa-xmark" id="closeProof"></i>
                            <img src="<?php echo $proof_image; ?>" alt="Proof Image">
                        </div>
                    </div>

                    <div class="profile-right" id="editProfile">
                        <h2>Edit Worker Profile</h2>

                        <form id="editProfileForm" action="../endpoints/update_worker.php" method="POST"
                            class="editProfile-container" enctype="multipart/form-data">
                            <input type="hidden" name="worker_id" value="<?php echo $worker_id; ?>">

                            <div class="inputProfile-wrapper">
                                <div class="inputProfile">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name"
                                        value="<?php echo htmlspecialchars($worker['last_name']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name"
                                        value="<?php echo htmlspecialchars($worker['first_name']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name"
                                        value="<?php echo htmlspecialchars($worker['middle_name']); ?>">
                                </div>

                                <div class="inputProfile">
                                    <label for="extension_name">Extension Name</label>
                                    <input type="text" name="extension_name"
                                        value="<?php echo htmlspecialchars($worker['extension_name']); ?>">
                                </div>

                                <div class="inputProfile">
                                    <label for="gender">Gender</label>
                                    <select name="gender" required>
                                        <option value="Male" <?php echo $worker['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $worker['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>

                                <div class="inputProfile">
                                    <label for="city">City/Province</label>
                                    <input type="text" name="city"
                                        value="<?php echo htmlspecialchars($worker['city']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="barangay">Barangay</label>
                                    <input type="text" name="barangay"
                                        value="<?php echo htmlspecialchars($worker['barangay']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="contact">Contact Information</label>
                                    <input type="text" name="contact"
                                        value="<?php echo htmlspecialchars($worker['contact']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="email">Email</label>
                                    <input type="email" name="email"
                                        value="<?php echo htmlspecialchars($worker['email']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="position">Position</label>
                                    <input type="text" name="position"
                                        value="<?php echo htmlspecialchars($worker['position']); ?>" required>
                                </div>

                                <div class="inputProfile">
                                    <label for="image">Change Photo</label>
                                    <input type="file" name="image" accept="image/*">
                                </div>

                                <div class="inputProfile">
                                    <label for="proof_image">Proof of Appointment</label>
                                    <input type="file" name="proof_image" accept="image/*">
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
                                                    window.location.href = 'workersProfile.php?id=<?php echo $worker_id; ?>';
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
                    <div class="profile-right" id="passProfile">
                        <h2>Change Password</h2>

                        <form action="" class="passProfile-container">
                            <div class="inputProfile-wrapper">

                                <div class="inputProfile">
                                    <label for="">New Password</label>
                                    <input type="password">
                                </div>

                                <div class="inputProfile">
                                    <label for="">Confirm Password</label>
                                    <input type="text">
                                </div>


                            </div>

                            <button class="mainBtn" id="save" style="margin-top: 1em;">Save</button>

                        </form>

                    </div>


                </div>
            </div>
        </main>

    </div>

    <script>




        function confirmDelete(workerId) {
            // SweetAlert confirmation
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to delete this worker? This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, send AJAX request to delete worker
                    fetch(`../endpoints/delete_worker.php?id=${workerId}`, {
                        method: 'GET'
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "The worker has been deleted.",
                                    icon: "success",
                                    confirmButtonColor: "#3085d6"
                                }).then(() => {
                                    window.location.href = 'personnelPage.php';
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: "Failed to delete the worker.",
                                    icon: "error",
                                    confirmButtonColor: "#3085d6"
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire({
                                title: "Error",
                                text: "An error occurred while trying to delete the worker.",
                                icon: "error",
                                confirmButtonColor: "#3085d6"
                            });
                        });
                }
            });
        }
    </script>


    <!-- sidebar import js -->
    <script src="../../includes/sidebar.js"></script>C

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>


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



    <!-- sweetalert popup messagebox add form-->
    <script>

    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




</body>

</html>