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

$evacuationCenterId = $_GET['id'];  // Get the evacuation center ID from the URL parameter

// Fetch the evacuation center name
$evacuationCenterSql = "SELECT name FROM evacuation_center WHERE id = ?";
$evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
$evacuationCenterStmt->bind_param("i", $evacuationCenterId);
$evacuationCenterStmt->execute();
$evacuationCenterResult = $evacuationCenterStmt->get_result();
$evacuationCenter = $evacuationCenterResult->fetch_assoc();

// Fetch assigned workers for the specified evacuation center
$assignedWorkersSql = "
    SELECT w.id, w.first_name, w.middle_name, w.last_name, w.extension_name, w.contact, w.age, w.gender, w.position 
    FROM assigned_worker aw
    JOIN worker w ON aw.worker_id = w.id
    WHERE aw.evacuation_center_id = ? AND aw.status = 'assigned'
";
$assignedWorkersStmt = $conn->prepare($assignedWorkersSql);
$assignedWorkersStmt->bind_param("i", $evacuationCenterId);
$assignedWorkersStmt->execute();
$assignedWorkersResult = $assignedWorkersStmt->get_result();
$hasWorkers = $assignedWorkersResult->num_rows > 0;

// Fetch all unique positions from the worker table
$uniquePositionsSql = "SELECT DISTINCT position FROM worker";
$uniquePositionsResult = $conn->query($uniquePositionsSql);
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
    .hidden {
        display: none;
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
                            <a
                                href="viewEC.php?id=<?php echo $evacuationCenterId; ?>"><?php echo $evacuationCenter['name']; ?></a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Team</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
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
                                        <div class="filter-option">
                                            <?php while ($position = $uniquePositionsResult->fetch_assoc()): ?>
                                                <div class="option-content">
                                                    <input type="checkbox"
                                                        id="filter-<?php echo htmlspecialchars($position['position']); ?>"
                                                        class="position-filter"
                                                        value="<?php echo htmlspecialchars($position['position']); ?>">
                                                    <label
                                                        for="filter-<?php echo htmlspecialchars($position['position']); ?>">
                                                        <?php echo htmlspecialchars($position['position']); ?>
                                                    </label>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="input_group">
                                <input type="search" id="searchInput" placeholder="Search by name...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>

                                    <!-- <tr>
                                        <th>Full Name</th>
                                        <th>Contact Info</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th style="text-align: center;">Role</th>
                                        <th>Action</th>
                                    </tr> -->

                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Full Name</th>
                                        <th>Contact Info</th>
                                        <th style="text-align: center;">Age</th>
                                        <th style="text-align: center;">Gender</th>
                                        <th style="text-align: center;">Position</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="workerTableBody">
                                    <?php if ($hasWorkers): ?>
                                        <?php while ($worker = $assignedWorkersResult->fetch_assoc()): ?>
                                            <tr class="worker-row"
                                                data-position="<?php echo htmlspecialchars($worker['position']); ?>">
                                                <td class="worker-name">
                                                    <?php echo htmlspecialchars($worker['first_name'] . ' ' . $worker['middle_name'] . ' ' . $worker['last_name'] . ' ' . $worker['extension_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($worker['contact']); ?></td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($worker['age']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($worker['gender']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($worker['position']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="workersProfile.php?id=<?php echo $worker['id']; ?>"
                                                        class="view-action">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No Assigned Worker Yet!</td>
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const positionFilters = document.querySelectorAll('.position-filter');
            const workerRows = document.querySelectorAll('.worker-row');

            // Function to filter rows by position and search query
            function filterRows() {
                const searchQuery = searchInput.value.toLowerCase();
                const selectedPositions = Array.from(positionFilters)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                workerRows.forEach(row => {
                    const name = row.querySelector('.worker-name').textContent.toLowerCase();
                    const position = row.getAttribute('data-position');

                    // Show row if it matches the search and position filter
                    const matchesSearch = name.includes(searchQuery);
                    const matchesPosition = selectedPositions.length === 0 || selectedPositions.includes(position);

                    if (matchesSearch && matchesPosition) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            }

            // Add event listeners for search and filter changes
            searchInput.addEventListener('input', filterRows);
            positionFilters.forEach(checkbox => checkbox.addEventListener('change', filterRows));
        });
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebar.js"></script>C

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>

<?php
$assignedWorkersStmt->close();
$conn->close();
?>