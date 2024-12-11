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

if (isset($_SESSION['user_id'])) {
    $worker_id = $_SESSION['user_id'];

    // Fetch the admin_id of the current worker
    $query = "SELECT admin_id FROM worker WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_id = $row['admin_id'];
    } else {
        echo "No admin assigned to this worker.";
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
}
// Validate session role
validateSession('worker');
// Fetch approved supplies (Received) for the admin's evacuation centers
$query = "
    SELECT 
        s.id AS supply_id,
        s.name AS supply_name,
        s.description,
        s.quantity,
        s.original_quantity,
        s.unit,
        s.image,
        s.date,
        s.time,
        s.`from` AS supply_from,
        s.approved,
        ec.name AS evacuation_center_name,
        ec.location AS evacuation_center_location
    FROM 
        supply AS s
    INNER JOIN 
        evacuation_center AS ec
    ON 
        s.evacuation_center_id = ec.id
    WHERE 
        ec.admin_id = ? 
        AND s.approved = 1  
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$supplies = [];
while ($row = $result->fetch_assoc()) {
    $supplies[] = $row;
}


// Fetch distributed supplies (if any) for the admin's evacuation centers
$distributed_query = "
    SELECT 
        d.id AS distribute_id,
        d.supply_name,
        d.date,
        d.quantity,
        ec.name AS evacuation_center_name,
        CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS evacuee_name,
        d.distributor_type
    FROM 
        distribute AS d
    INNER JOIN 
        evacuees AS e
    ON 
        d.evacuees_id = e.id
    INNER JOIN 
        evacuation_center AS ec
    ON 
        e.evacuation_center_id = ec.id
    WHERE 
        ec.admin_id = ?
";
$distributed_stmt = $conn->prepare($distributed_query);
$distributed_stmt->bind_param("i", $admin_id);
$distributed_stmt->execute();
$distributed_result = $distributed_stmt->get_result();

$distributed = [];
while ($row = $distributed_result->fetch_assoc()) {
    $distributed[] = $row;
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
    <link rel="stylesheet" href="../../assets/styles/utils/viewEC.css">
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css">


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
                            <a href="#">Prints Reports</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <!-- <button class="addBg-admin" onclick="window.location.href='evacueesForm.php'">
                            Admit
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-navbar></special-navbar>



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
                                                <input type="checkbox" name="evacuees" id="tetuan">
                                                <label for="tetuan">Tetuan</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="tugbungan">
                                                <label for="tugbungan">Tugbungan</label>
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
                                        <th>Family Head</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Number of members</th>
                                        <th style="text-align: center;">Barangay</th>
                                        <th style="text-align: center;">Date</th>
                                        <th>Calamity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </section>

                        <div class="no-match-message">No matching data found</div>
                    </div>

                </div>
            </div>
        </main>

    </div>


    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/printReportsWorkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>