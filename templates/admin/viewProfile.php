<?php
session_start();
require_once '../../connection/conn.php';
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
// Fetch all workers under this admin
$worker_query = "SELECT first_name, middle_name, last_name, position FROM worker WHERE admin_id = ? AND status = 'active'";
$worker_stmt = $conn->prepare($worker_query);
$worker_stmt->bind_param("i", $admin_id);
$worker_stmt->execute();
$worker_result = $worker_stmt->get_result();

// Fetch all evacuation centers for this admin and determine their status
$ec_query = "
    SELECT ec.id, ec.name, 
           (CASE WHEN COUNT(ev.id) > 0 THEN 'Active' ELSE 'Inactive' END) AS status
    FROM evacuation_center AS ec
    LEFT JOIN evacuees AS ev ON ec.id = ev.evacuation_center_id
    WHERE ec.admin_id = ?
    GROUP BY ec.id";
$ec_stmt = $conn->prepare($ec_query);
$ec_stmt->bind_param("i", $admin_id);
$ec_stmt->execute();
$ec_result = $ec_stmt->get_result();

// Fetch activity logs for this admin
$notification_query = "SELECT notification_msg, created_at FROM notifications WHERE logged_in_id = ?";
$notification_stmt = $conn->prepare($notification_query);
$notification_stmt->bind_param("i", $admin_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();

$query = "SELECT status FROM admin WHERE id = $admin_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$admin_status = $row['status'];

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
                                <?php if ($admin_status === 'inactive') { ?>
                                    <a href="javascript:void(0);"
                                        onclick="confirmDelete(<?php echo $admin_id; ?>)">Delete</a>
                                <?php } else { ?>
                                    <a href="javascript:void(0);" onclick="showActiveAdminWarning()">Delete</a>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                    <!-- left content -->
                    <div class="profile-left">
                        <div class="profileInfo">

                            <div class="profileInfo-left">
                                <img class="profileImg"
                                    src="<?php echo htmlspecialchars($admin['image'] ?: '../../assets/img/undraw_male_avatar_g98d.svg'); ?>"
                                    alt="">

                                <input id="fileInput" type="file" style="display:none;" />
                                <input class="cPhoto" type="button" value="Change Photo"
                                    onclick="document.getElementById('fileInput').click();" style="opacity: 0;" />
                            </div>

                            <div class="profileInfo-right">
                                <h3 profile-name>
                                    <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name']); ?>
                                </h3>

                                <div class="profile-details">
                                    <p class="details-profile">Barangay:
                                        <?php echo htmlspecialchars($admin['barangay']); ?>
                                    </p>
                                    <p class="details-profile">Address:
                                        <?php echo htmlspecialchars($admin['city']); ?>
                                    </p>
                                    <p class="details-profile">Sex: <?php echo htmlspecialchars($admin['gender']); ?>
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
                            <h3 class="ecHeader-title">Evacuation Centers</h3>
                            <div class="ecBarangay-container">
                                <?php if ($ec_result->num_rows > 0): ?>
                                    <?php while ($ec = $ec_result->fetch_assoc()): ?>
                                        <div class="ecBarangay-list">
                                            <p class="ecBarangay-name"><?php echo htmlspecialchars($ec['name']); ?></p>
                                            <p
                                                class="ecBarangay-status <?php echo $ec['status'] === 'Active' ? 'statActive' : 'statInactive'; ?>">
                                                <?php echo $ec['status']; ?>
                                            </p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="no-ec-message">No Evacuation Centers</p>
                                <?php endif; ?>
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

                            <div class="personnel-content">
                                <?php if ($worker_result->num_rows > 0): ?>
                                    <?php while ($worker = $worker_result->fetch_assoc()): ?>
                                        <div class="personel-content">
                                            <p class="personal-name">
                                                <?php echo htmlspecialchars($worker['first_name'] . ' ' . $worker['middle_name'] . ' ' . $worker['last_name']); ?>
                                            </p>
                                            <p class="personel-role">
                                                <?php echo htmlspecialchars($worker['position']); ?>
                                            </p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p style="padding: 28px" class="no-personnel">No personnel found under this admin.</p>
                                <?php endif; ?>
                            </div>

                            <div class="activityLog-content">
                                <?php if ($notification_result->num_rows > 0): ?>
                                    <?php while ($notification = $notification_result->fetch_assoc()): ?>
                                        <div class="log-container">
                                            <p class="logDate">
                                                <?php echo htmlspecialchars(date("m-d-Y", strtotime($notification['created_at']))); ?>
                                            </p>
                                            <p class="logInfo">
                                                <?php echo htmlspecialchars($notification['notification_msg']); ?>
                                            </p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="no-activity-log">No activity logs found for this admin.</p>
                                <?php endif; ?>
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
        // SweetAlert for active admins
        function showActiveAdminWarning() {
            Swal.fire({
                title: "Can't Delete Active Admin",
                text: "You cannot delete an active admin.",
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        // SweetAlert for confirming deletion
        function confirmDelete(adminId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the admin's profile permanently.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send an AJAX request to delete the admin
                    fetch(`../endpoints/delete_admin.php?id=${adminId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'The admin has been deleted.',
                                    'success'
                                ).then(() => {
                                    // Redirect or remove the admin from the DOM
                                    window.location.href = 'barangayAcc.php'; // Or another page
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Could not delete admin.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'An error occurred while deleting the admin.', 'error');
                        });
                }
            });
        }


        document.addEventListener('DOMContentLoaded', function () {
            const personelHeader = document.querySelector('.personel-header');
            const activityLogHeader = document.querySelector('.activityLog-header');
            const personelActiveLine = document.querySelector('.personel .activeLine');
            const activityLogActiveLine = document.querySelector('.activityLog .activeLine');

            const personelContent = document.querySelectorAll('.personel-content');
            const activityLogContent = document.querySelectorAll('.activityLog-content');
            const noPersonnelMessage = document.querySelector('.no-personnel');
            const noActivityLogMessage = document.querySelector('.no-activity-log');

            // Function to handle toggling active class and showing active line
            function toggleActive(header, activeLine, contentToShow, contentToHide, showEmptyMessage, hideEmptyMessage) {
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

                // Show/hide the empty messages accordingly
                if (showEmptyMessage) showEmptyMessage.style.display = 'block';
                if (hideEmptyMessage) hideEmptyMessage.style.display = 'none';
            }

            // Set personel-header as active by default on page load
            personelHeader.classList.add('active');
            personelActiveLine.style.display = 'block';

            // Display personel-content and hide activityLog-content by default
            personelContent.forEach(content => content.style.display = 'flex');
            activityLogContent.forEach(content => content.style.display = 'none');

            // Check if there's no personnel or activity log and display message accordingly
            if (noPersonnelMessage) noPersonnelMessage.style.display = 'block';
            if (noActivityLogMessage) noActivityLogMessage.style.display = 'none';

            // Add event listeners to the headers
            personelHeader.addEventListener('click', function () {
                toggleActive(personelHeader, personelActiveLine, personelContent, activityLogContent, noPersonnelMessage, noActivityLogMessage);
            });

            activityLogHeader.addEventListener('click', function () {
                toggleActive(activityLogHeader, activityLogActiveLine, activityLogContent, personelContent, noActivityLogMessage, noPersonnelMessage);
            });
        });

    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




</body>

</html>