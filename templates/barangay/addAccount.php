<?php
session_start();
require_once '../../connection/conn.php';

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Query to fetch the barangay of the logged-in admin
    $query = "SELECT barangay FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the barangay was found
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $barangay = $admin['barangay'];
    } else {
        echo "Barangay not found.";
        exit();
    }
} else {
    header("Location: ../../login.php");
    exit();
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
    <link rel="stylesheet" href="../../assets/styles/utils/barangay.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/admin-form.css">
    <link rel="stylesheet" href="../../assets/styles/utils/messagebox.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>
            Swal.fire({
                icon: '{$_SESSION['message_type']}',
                title: '{$_SESSION['message']}'
            });
          </script>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create account for community worker</a>
                        </div>




                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <h2 class="admin-reg">Registration Form</h2>

                    <form id="adminForm" action="../endpoints/create_worker.php" method="POST"
                        enctype="multipart/form-data">
                        <div class="admin-input_container">
                            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>">

                            <div class="admin-input">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="input-lastName"
                                    placeholder="Input Last Name" required>
                            </div>

                            <div class="admin-input">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="input-firstName"
                                    placeholder="Input First Name" required>
                            </div>

                            <div class="admin-input">
                                <label for="middleName">Middle Name</label>
                                <input type="text" id="middleName" name="middleName" class="input-middleName"
                                    placeholder="Input Middle Initial">
                            </div>

                            <div class="admin-input">
                                <label for="extensionName">Extension Name</label>
                                <input type="text" id="extensionName" name="extensionName" class="input-extensionName"
                                    placeholder="e.g., Jr.">
                            </div>

                            <div class="admin-input">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" class="input-gender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="admin-input">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" class="input-age" placeholder="Input Age"
                                    required>
                            </div>
                            <div class="admin-input">
                                <label for="city">City/Province</label>
                                <input type="text" id="city" name="city" class="input-city" placeholder=""
                                    value="Zamboanga City" required>
                            </div>

                            <div class="admin-input">
                                <label for="barangay">Barangay</label>
                                <input type="text" id="barangay" name="barangay" class="input-barangay"
                                    value="<?php echo htmlspecialchars($barangay); ?>" required placeholder="">
                            </div>

                            <div class="admin-input">
                                <label for="contactInfo">Contact Information</label>
                                <input type="number" id="contactInfo" name="contactInfo" class="input-contactInfo"
                                    placeholder="Input Contact Info" required>
                            </div>

                            <div class="admin-input">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="input-email"
                                    placeholder="Input Email" required>
                            </div>

                            <div class="admin-input">
                                <label for="position">Position</label>
                                <input type="text" id="position" name="position" class="input-position"
                                    placeholder="e.g., Barangay Captain" required>
                            </div>

                            <div class="admin-input">
                                <label for="proofOfAppointment">Proof of appointment</label>
                                <input type="file" id="proofOfAppointment" name="proofOfAppointment"
                                    class="input-proofOfAppointment" required>
                            </div>

                            <div class="admin-input">
                                <label for="photo">Add your photo</label>
                                <input type="file" id="photo" name="photo" class="input-photo">
                            </div>
                        </div>
                        <!-- 
                        <div class="admin-photo">
                            <label for="photo">Add your photo</label>
                            <input type="file" id="photo" name="photo" class="input-photo">
                        </div> -->

                        <div class="admin-cta_container">
                            <div class="admin-cta">
                                <button type="button" class="mainBtn adminCreate" id="create">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    </main>

    </div>

    <script>
        document.getElementById('create').addEventListener('click', function () {
            const form = document.getElementById('adminForm');

            // Check if form is valid
            if (form.checkValidity()) {
                // Trigger SweetAlert
                Swal.fire({
                    title: 'Confirm Registration',
                    text: 'Please confirm that you would like to proceed',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                form.reportValidity();
            }
        });
    </script>

    <!-- import sidebar -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>