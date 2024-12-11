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

// Fetch evacuation center details including members of evacuees, limited by assigned_worker
$sql = "SELECT ec.id, ec.name, ec.location, ec.capacity, 
        COUNT(DISTINCT ev.id) AS total_families,
        (
            (
                SELECT COUNT(*) 
                FROM evacuees ev_inner 
                WHERE ev_inner.evacuation_center_id = ec.id 
                AND (
                    ev_inner.status = 'Admitted' OR 
                    (ev_inner.status = 'Transfer' AND ev_inner.evacuation_center_id = ev_inner.origin_evacuation_center_id)
                )
            ) + 
            (
                SELECT COUNT(*) 
                FROM members m 
                WHERE m.evacuees_id IN (
                    SELECT ev_inner.id 
                    FROM evacuees ev_inner 
                    WHERE ev_inner.evacuation_center_id = ec.id 
                    AND (
                        ev_inner.status = 'Admitted' OR 
                        (ev_inner.status = 'Transfer' AND ev_inner.evacuation_center_id = ev_inner.origin_evacuation_center_id)
                    )
                )
            )
        ) AS total_evacuees
        FROM evacuation_center ec
        INNER JOIN assigned_worker aw ON ec.id = aw.evacuation_center_id
        LEFT JOIN evacuees ev ON ec.id = ev.evacuation_center_id 
        AND (
            ev.status = 'Admitted' OR 
            (ev.status = 'Transfer' AND ev.evacuation_center_id = ev.origin_evacuation_center_id)
        )
        WHERE ec.admin_id = ? 
        AND aw.worker_id = ? 
        AND aw.status = 'assigned'
        GROUP BY ec.id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $admin_id, $worker_id);
$stmt->execute();
$result = $stmt->get_result();

$evacuation_centers = [];
while ($row = $result->fetch_assoc()) {
    $row['status'] = ($row['total_evacuees'] > 0) ? 'Active' : 'Inactive'; // Determine status
    $evacuation_centers[] = $row;
}

$stmt->close();
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
<style>
    .status.active {
        background-color: var(--clr-green);
        color: var(--clr-white);
    }

    .status.inactive {
        background-color: var(--clr-red);
        color: var(--clr-light);
    }
</style>

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
                            <a href="#">Assigned Evacuation Reports </a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <button class="addBg-admin" onclick="confirmExport()">
                            Export
                        </button>
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
                                                <input type="checkbox" name="evacuees" id="active-filter"
                                                    onclick="filterEvacuationCenters()">
                                                <label for="active-filter">Active</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="inactive-filter"
                                                    onclick="filterEvacuationCenters()">
                                                <label for="inactive-filter">Inactive</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="input_group">
                                <input type="search" id="searchInput" placeholder="Search...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>
                                    <tr>
                                        <th>Evacuation Center</th>
                                        <th>Address</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Capacity</th>
                                        <th style="text-align: center;">Total Families</th>
                                        <th style="text-align: center;">Total Evacuees</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($evacuation_centers)): ?>
                                        <?php foreach ($evacuation_centers as $center): ?>
                                            <tr class="evacuation-row"
                                                data-status="<?php echo strtolower($center['status']); ?>">
                                                <td><?php echo htmlspecialchars($center['name']); ?></td>
                                                <td><?php echo htmlspecialchars($center['location']); ?></td>
                                                <td style="text-align: center;">
                                                    <p class="status <?php echo strtolower($center['status']); ?>">
                                                        <?php echo $center['status']; ?>
                                                    </p>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($center['total_families']); ?>/<?php echo htmlspecialchars($center['capacity']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($center['total_families']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($center['total_evacuees']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a class="view-action" href="javascript:void(0);"
                                                        onclick="confirmAction(<?php echo $center['id']; ?>)">Print</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No evacuation centers
                                                found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </section>

                        <div class="no-match-message">No matching data found</div>
                    </div>

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
        function confirmExport() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to export all assigned evacuation centers.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the PHP script for exporting all evacuation centers
                    window.location.href = '../export/export_workers_centers.php?admin_id=<?php echo $admin_id; ?>&worker_id=<?php echo $worker_id; ?>';
                }
            });
        }
        document.getElementById('searchInput').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#mainTable tbody tr');

            rows.forEach(row => {
                const familyHead = row.querySelector('td:first-child').textContent.toLowerCase();
                row.style.display = familyHead.includes(filter) ? '' : 'none';
            });
        });

        function filterEvacuationCenters() {

            const activeFilter = document.getElementById('active-filter');
            const inactiveFilter = document.getElementById('inactive-filter');

            const rows = document.querySelectorAll('.evacuation-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');

                if (
                    (activeFilter.checked && status === 'active') ||
                    (inactiveFilter.checked && status === 'inactive') ||
                    (!activeFilter.checked && !inactiveFilter.checked)
                ) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        }
    </script>

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