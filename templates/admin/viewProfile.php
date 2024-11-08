<?php
session_start();
require_once '../../connection/conn.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Prepare and execute the query to fetch the admin details
    $query = "SELECT first_name, middle_name, last_name, barangay, city, gender, position, role, image, proof_image FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the admin exists
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
    } else {
        echo "Admin not found.";
        exit();
    }
} else {
    echo "No admin selected.";
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
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/viewProfile.css">



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
                            <a href="barangayAcc.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">View Account</a>
                        </div>




                        <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container profile-grid">

                    <!-- modal -->
                    <div class="profile-cta">
                        <label for="cta-toggle" class="cta-button">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </label>
                        <input type="checkbox" name="" id="cta-toggle" class="cta-toggle">

                        <div class="cta-modal">
                            <div class="cta-options">
                                <a href="#">Delete</a>
                            </div>
                        </div>
                    </div>

                    <!-- left content -->
                    <div class="profile-left">
                        <div class="profileInfo">

                            <div class="profileInfo-left">
                                <img class="profileImg"
                                    src="<?php echo htmlspecialchars($admin['image'] ?: '../../assets/img/default-avatar.png'); ?>"
                                    alt="">

                                <input id="fileInput" type="file" style="display:none;" />
                                <input class="cPhoto" type="button" value="Change Photo"
                                    onclick="document.getElementById('fileInput').click();" style="opacity: 0;" />
                            </div>

                            <div class="profileInfo-right">
                                <h3 profile-name>
                                    <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?>
                                </h3>

                                <div class="profile-details">
                                    <p class="details-profile">Barangay:
                                        <?php echo htmlspecialchars($admin['barangay']); ?>
                                    </p>
                                    <p class="details-profile">Address:
                                        <?php echo htmlspecialchars($admin['city']); ?>
                                    </p>
                                    <p class="details-profile">Gender: <?php echo htmlspecialchars($admin['gender']); ?>
                                    </p>
                                    <p class="details-profile">Position:
                                        <?php echo htmlspecialchars($admin['position']); ?>
                                    </p>
                                    <p class="details-profile">Role:
                                        <?php echo ucfirst(htmlspecialchars($admin['role'])); ?>
                                    </p>
                                </div>

                                <label for="proof-toggle" class="proof-button">
                                    Proof of appointment
                                </label>
                                <input type="checkbox" name="" id="proof-toggle" class="proof-toggle">

                                <div class="proof-modal">
                                    <div class="proof-attachPhoto">
                                        <label for="proof-toggle">
                                            <i class="fa-solid fa-xmark"></i>
                                        </label>
                                        <img class="proof-photo"
                                            src="<?php echo htmlspecialchars($admin['proof_image'] ?: '../../assets/img/default-avatar.png'); ?>"
                                            alt="Proof of Appointment">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="barangayEcenter">
                            <h3 class="ecHeader-title">
                                Evacuation Centers
                            </h3>

                            <div class="ecBarangay-container">
                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statActive">Active</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statActive">Active</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statActive">Active</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statActive">Active</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statActive">Active</p>
                                </div>

                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">Barangay Hall</p>
                                    <p class="ecBarangay-status statInactive">Inactive</p>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="profile-right">
                        <div class="profile-right-container">

                            <div class="right-header">
                                <div class="personel">
                                    <!-- <a href="viewProfile.php" class="personel-header active">
                                        Personel List
                                    </a> -->

                                    <p class="personel-header">
                                        Personel List
                                    </p>

                                    <div class="activeLine"></div>
                                </div>

                                <div class="activityLog">
                                    <!-- <a href="viewProfile-page2.php" class="activityLog-header">
                                        Activity Logs
                                    </a> -->

                                    <p class="activityLog-header">
                                        Activity Logs
                                    </p>

                                    <div class="activeLine"></div>
                                </div>
                            </div>

                            <!-- personel content -->
                            <div class="personel-content">
                                <p class="personal-name">Jose Manalo</p>
                                <p class="personel-role">SK</p>
                            </div>

                            <div class="personel-content">
                                <p class="personal-name">Jose Manalo</p>
                                <p class="personel-role">Kagawad</p>
                            </div>

                            <div class="personel-content">
                                <p class="personal-name">Jose Manalo</p>
                                <p class="personel-role">Volunteer</p>
                            </div>



                            <!-- activity logs content -->
                            <div class="activityLog-content">
                                <div class="log-container">
                                    <p class="logDate">11-15-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Evacuation Center created >> name of the ec</p>
                                </div>
                            </div>




                        </div>

                    </div>


                </div>
            </div>
        </main>

    </div>



    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select the elements
            const personelHeader = document.querySelector('.personel-header');
            const activityLogHeader = document.querySelector('.activityLog-header');
            const personelActiveLine = document.querySelector('.personel .activeLine');
            const activityLogActiveLine = document.querySelector('.activityLog .activeLine');

            const personelContent = document.querySelectorAll('.personel-content');
            const activityLogContent = document.querySelectorAll('.activityLog-content');

            // Function to handle toggling active class and showing active line
            function toggleActive(header, activeLine, contentToShow, contentToHide) {
                // Remove active from both headers
                personelHeader.classList.remove('active');
                activityLogHeader.classList.remove('active');

                // Hide both active lines
                personelActiveLine.style.display = 'none';
                activityLogActiveLine.style.display = 'none';

                // Add active class to clicked header and show corresponding line
                header.classList.add('active');
                activeLine.style.display = 'block';

                // Show and hide the corresponding content
                contentToShow.forEach(content => content.style.display = 'flex');
                contentToHide.forEach(content => content.style.display = 'none');
            }

            // Set personel-header as active by default on page load
            personelHeader.classList.add('active');
            personelActiveLine.style.display = 'block';

            // Display personel-content by default
            personelContent.forEach(content => content.style.display = 'flex');
            activityLogContent.forEach(content => content.style.display = 'none');

            // Add event listeners to the headers
            personelHeader.addEventListener('click', function () {
                toggleActive(personelHeader, personelActiveLine, personelContent, activityLogContent);
            });

            activityLogHeader.addEventListener('click', function () {
                toggleActive(activityLogHeader, activityLogActiveLine, activityLogContent, personelContent);
            });
        });


    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




</body>

</html>