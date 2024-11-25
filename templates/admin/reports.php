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

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, email, image FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $first_name = $admin['first_name'];
        $middle_name = $admin['middle_name'];
        $last_name = $admin['last_name'];
        $extension_name = $admin['extension_name'];
        $email = $admin['email'];
        $admin_image = $admin['image'];

        $admin_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $extension_name);
    } else {
        $first_name = $middle_name = $last_name = $extension_name = $email = '';
    }
} else {
    header("Location: ../../login.php");
    exit;
}
$admin_image = !empty($admin_image) ? $admin_image : "../../assets/img/admin.png";

// Fetch evacuation centers
$sql_centers = "SELECT id, name, location, capacity FROM evacuation_center WHERE admin_id = ?";
$stmt_centers = $conn->prepare($sql_centers);
$stmt_centers->bind_param("i", $admin_id);
$stmt_centers->execute();
$result_centers = $stmt_centers->get_result();
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

    <style>
        .status.active {
            background-color: var(--clr-green);
            color: var(--clr-white);
        }
    </style>


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
                            <a href="barangayAcc.php">Reports</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create new barangay admin account</a> -->
                        </div>

                        <!-- <button class="profile-btn" id="profile-btn">
                            <i class="fa-solid fa-user-plus"></i>
                            <img src="/assets/img/hero.jpg">
                        </button> -->
                        <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </div>
                </div>
            </header>

            <div class="table-wrapper">

                <div class="table-container">
                    <section class="tblheader">




                        <!-- <div class="filter-popup">
                            <i class="fa-solid fa-filter"></i>
                        </div> -->

                        <div class="filter-popup">
                            <label for="modal-toggle" class="modal-button">
                                <i class="fa-solid fa-filter"></i>
                            </label>
                            <input type="checkbox" name="" id="modal-toggle" class="modal-toggle">

                            <!-- the modal or filter popup-->
                            <div class="modal">
                                <div class="modal-content">
                                    <!-- <label for="modal-toggle" class="close">
                                        <i class="fa-solid fa-xmark"></i>
                                    </label> -->
                                    <div class="filter-option">
                                        <div class="option-content">
                                            <input type="checkbox" name="barangay" id="active">
                                            <label for="active">Active</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" name="barangay" id="inactive">
                                            <label for="inactive">Inactive</label>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="input_group">
                            <input type="search" placeholder="Search...">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                    </section>

                    <section class="tblbody">
                        <table id="mainTable">
                            <thead>

                                <tr>
                                    <th>Evacuation Center</th>
                                    <th>Barangay</th>
                                    <th>Status</th>
                                    <th>Capacity</th>
                                    <th>Total Families</th>
                                    <th>Total Evacuees</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <?php
                            $sql = "
SELECT 
    ec.id, 
    ec.name, 
    ec.location, 
    ec.capacity, 
    ec.image,
    COUNT(DISTINCT e.id) AS evacuee_count,
    COUNT(DISTINCT m.id) + COUNT(DISTINCT e.id) AS total_count,
    CASE 
        WHEN COUNT(DISTINCT e.id) = 0 THEN 'Inactive' 
        ELSE 'Active' 
    END AS status,
    a.barangay -- Fetch barangay from admin table
FROM 
    evacuation_center ec
LEFT JOIN 
    evacuees e ON ec.id = e.evacuation_center_id AND e.status = 'Admitted'
LEFT JOIN 
    members m ON e.id = m.evacuees_id
LEFT JOIN 
    admin a ON ec.admin_id = a.id -- Join evacuation_center with admin to get barangay
GROUP BY 
    ec.id
";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    $location = $row['location'];
                                    $capacity = $row['capacity'];
                                    $image = !empty($row['image']) ? $row['image'] : '../../assets/img/ecDefault.svg';
                                    $evacuee_count = $row['evacuee_count'];
                                    $total_count = $row['total_count'];
                                    $status = $row['status']; // 'Active' or 'Inactive'
                                    $barangay = $row['barangay']; // Fetch barangay from admin table
                                    ?>
                                    <tr onclick="confirmAction(<?= $id; ?>)">
                                        <td>
                                            <div class="relative">
                                                <img src="<?= $image; ?>" alt="Evacuation Center">
                                                <?= htmlspecialchars($name); ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($location); ?> , <?= htmlspecialchars($barangay); ?></td>

                                        <td>
                                            <p class="status role <?= strtolower($status); ?>">
                                                <?= htmlspecialchars($status); ?>
                                            </p>
                                        </td>
                                        <td><?= htmlspecialchars($capacity); ?></td>
                                        <td><?= $evacuee_count; ?></td>
                                        <td><?= $total_count; ?></td>
                                        <td><a class="view-action">Print</a></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8'>No evacuation centers found.</td></tr>";
                            }
                            ?>


                    </section>

                    <div class="no-match-message">No matching data found</div>
                </div>
            </div>


        </main>




    </div>

    <script>
        function confirmAction(evacuationCenterId) {
            Swal.fire({
                title: 'Print Report?',
                text: "Confirm to print the report for this evacuation center.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Action confirmed for ID:", evacuationCenterId);
                    // Perform action, e.g., redirect to a report page
                    window.location.href = `../export/export_evacuation_center.php?id=${evacuationCenterId}`;
                } else {
                    console.log("Action canceled.");
                }
            });
        }

    </script>

    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- filter search -->
    <script src="../../assets/src/admin/accountSearch.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>