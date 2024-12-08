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
validateSession('worker');

if (isset($_GET['id']) && isset($_GET['worker_id'])) {
    $evacuationCenterId = intval($_GET['id']);
    $workerId = intval($_GET['worker_id']);

} else {
    // Handle missing parameters
    // header("Location: ");
    // exit;
}
$workerId = $_GET['worker_id'] ?? null;
$evacuationCenterId = $_GET['id'] ?? 'All';

// Fetch admin_id associated with the evacuation center (or any valid center)
$adminId = null;
if ($evacuationCenterId !== 'All') {
    $adminQuery = "SELECT admin_id FROM evacuation_center WHERE id = ?";
    $adminStmt = $conn->prepare($adminQuery);
    $adminStmt->bind_param("i", $evacuationCenterId);
    $adminStmt->execute();
    $adminResult = $adminStmt->get_result();
    $adminData = $adminResult->fetch_assoc();
    $adminId = $adminData['admin_id'];
} else {
    // If "All" is selected, fetch admin_id of the logged-in user
    $adminQuery = "SELECT DISTINCT admin_id FROM evacuation_center";
    $adminResult = $conn->query($adminQuery);
    $adminData = $adminResult->fetch_assoc();
    $adminId = $adminData['admin_id'];
}

// Fetch evacuation centers associated with the admin
$centersQuery = "
    SELECT 
        ec.id, 
        ec.name
    FROM evacuation_center ec
    INNER JOIN assigned_worker aw ON ec.id = aw.evacuation_center_id
    WHERE aw.worker_id = ? AND aw.status = 'assigned'
    ORDER BY ec.name ASC";
$centersStmt = $conn->prepare($centersQuery);
$centersStmt->bind_param("i", $workerId);
$centersStmt->execute();
$centersResult = $centersStmt->get_result();

// Prepare evacuees query based on the selected evacuation center
if ($evacuationCenterId === 'All') {
    $evacuationCenterName = 'All Evacuees';
    $sql = "
    SELECT 
        e.id AS evacuee_id,
        CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
        e.contact,
        e.status,
        e.date,
        e.disaster_type,
        COUNT(m.id) AS member_count,
        GROUP_CONCAT(CONCAT(m.first_name, ' ', m.last_name) ORDER BY m.first_name ASC SEPARATOR ', ') AS member_names
    FROM evacuees e
    LEFT JOIN members m ON e.id = m.evacuees_id
    LEFT JOIN evacuation_center ec ON e.evacuation_center_id = ec.id
    INNER JOIN assigned_worker aw ON ec.id = aw.evacuation_center_id
    WHERE aw.worker_id = ? AND aw.status = 'assigned'
    GROUP BY e.id
    ORDER BY e.date DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $workerId);

} else {
    // Fetch evacuees for a specific evacuation center
    $evacuationCenterSql = "SELECT name FROM evacuation_center WHERE id = ?";
    $evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
    $evacuationCenterStmt->bind_param("i", $evacuationCenterId);
    $evacuationCenterStmt->execute();
    $evacuationCenterResult = $evacuationCenterStmt->get_result();
    $evacuationCenter = $evacuationCenterResult->fetch_assoc();
    $evacuationCenterName = $evacuationCenter['name'];

    $sql = "
    SELECT 
        e.id AS evacuee_id,
        CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
        e.contact,
        e.status,
        e.date,
        e.disaster_type,
        COUNT(m.id) AS member_count,
        GROUP_CONCAT(CONCAT(m.first_name, ' ', m.last_name) ORDER BY m.first_name ASC SEPARATOR ', ') AS member_names
    FROM evacuees e
    LEFT JOIN members m ON e.id = m.evacuees_id
    WHERE e.evacuation_center_id = ?
    GROUP BY e.id
    ORDER BY e.date DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $evacuationCenterId);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$capacity = 0; // Default value
$evacueesCount = 0; // Default value

if ($evacuationCenterId !== 'All') {
    // Fetch evacuation center capacity and evacuees count
    $centerDetailsQuery = "
        SELECT 
            ec.capacity,
            (SELECT COUNT(*) FROM evacuees e WHERE e.evacuation_center_id = ec.id AND e.status != 'Transfer') AS evacuees_count
        FROM evacuation_center ec
        WHERE ec.id = ?";
    $centerDetailsStmt = $conn->prepare($centerDetailsQuery);
    $centerDetailsStmt->bind_param("i", $evacuationCenterId);
    $centerDetailsStmt->execute();
    $centerDetailsResult = $centerDetailsStmt->get_result();
    $centerDetails = $centerDetailsResult->fetch_assoc();

    if ($centerDetails) {
        $capacity = intval($centerDetails['capacity']);
        $evacueesCount = intval($centerDetails['evacuees_count']);
    }
} else {
    // If "All" is selected, skip fetching specific center details
    $capacity = PHP_INT_MAX; // Arbitrarily large number to avoid conflicts
    $evacueesCount = 0; // Reset to 0 to represent no specific count
}

// Pass the data to the frontend for SweetAlert logic
echo "<script>
    const evacuationCenterCapacity = $capacity;
    const currentEvacueesCount = $evacueesCount;
</script>";
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
                            <!-- <a
                                href="viewAssignedEC.php?id=<?php echo $evacuationCenterId; ?>&worker_id=<?php echo $workerId; ?>">
                                <?php echo htmlspecialchars($evacuationCenterName); ?>
                            </a> -->
                            <a href="">
                                <?php echo htmlspecialchars($evacuationCenterName); ?>
                            </a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a>
                        </div>


                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->


                        <select id="filterBarangay" class="filter-admin">
                            <option value="All" <?php echo ($evacuationCenterId === 'All') ? 'selected' : ''; ?>>All
                            </option>
                            <?php if ($centersResult->num_rows > 0): ?>
                                <?php while ($center = $centersResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($center['id']); ?>" <?php echo ($evacuationCenterId == $center['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($center['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>No Evacuation Centers Found</option>
                            <?php endif; ?>
                        </select>

                        <script>
                            document.getElementById('filterBarangay').addEventListener('change', function () {
                                const selectedValue = this.value;
                                const currentUrl = new URL(window.location.href);

                                // Update the `id` parameter in the URL
                                currentUrl.searchParams.set('id', selectedValue);

                                // Reload the page with the new URL
                                window.location.href = currentUrl.href;
                            });
                        </script>

                        <button class="addBg-admin"
                            data-ec-id="<?php echo $evacuationCenterId; ?>&worker_id=<?php echo $workerId; ?>">
                            Admit
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-navbar></special-navbar>
                    <!-- <div class="ecNavbar">
                        <ul>
                            <div class="navList">
                                <li><a href="viewEC.php">Overview</a></li>
                                <div class="indicator"></div>
                            </div>
                            <div class="navList">
                                <li class="active"><a href="evacueesPage.php">Evacuees</a></li>
                                <div class="indicator"></div>
                            </div>
                            <div class="navList">
                                <li><a href="resources.php">Resource Management</a></li>
                                <div class="indicator long"></div>
                            </div>

                            <div class="navList">
                                <li><a href="personnel.php">Personnel</a></li>
                                <div class="indicator mid"></div>
                            </div>

                            <div class="navList">
                                <li><a href="nearEC.php">Near Evacuation Center</a></li>
                                <div class="indicator long"></div>
                            </div>
                        </ul>
                    </div> -->


                    <div class="table-container">
                        <section class="tblheader">


                            <div class="filter-popup">
                                <label for="modal-toggle" class="modal-button">
                                    <i class="fa-solid fa-filter"></i>
                                </label>
                                <input type="checkbox" name="" id="modal-toggle" class="modal-toggle">

                                <!-- The modal or filter popup -->
                                <div class="modal">
                                    <div class="modal-content">
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="admit"
                                                    class="filter-checkbox" data-filter="Admitted" checked>
                                                <label for="admit">Admitted</label>
                                            </div>
                                            <!-- <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="transfer"
                                                    class="filter-checkbox" data-filter="Transfer">
                                                <label for="transfer">Transfer</label>
                                            </div> -->
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="transferred"
                                                    class="filter-checkbox" data-filter="Transferred">
                                                <label for="transferred">Transferred</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="moveout"
                                                    class="filter-checkbox" data-filter="Moved-out">
                                                <label for="moveout">Moved-out</label>
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
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Date</th>
                                        <th>Calamity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr data-status="<?php echo htmlspecialchars($row['status']); ?>">
                                                <td><?php echo htmlspecialchars($row['family_head']); ?></td>
                                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    <?php echo $row['member_count']; ?>
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <?php
                                                        $member_names = explode(', ', $row['member_names']);
                                                        foreach ($member_names as $member_name): ?>
                                                            <li><?php echo htmlspecialchars($member_name); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($row['status']); ?>
                                                </td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($row['date']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['disaster_type']); ?></td>
                                                <td style="text-align: center;">
                                                    <a href="viewEvacuees.php?id=<?php echo $row['evacuee_id']; ?>&center_id=<?php echo $evacuationCenterId; ?>&worker_id=<?php echo $workerId; ?>"
                                                        class="view-action">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No Evacuees Yet.</td>
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
        document.addEventListener("DOMContentLoaded", function () {
            const filterCheckboxes = document.querySelectorAll(".filter-checkbox");
            const tableRows = document.querySelectorAll("#mainTable tbody tr");

            function filterTable() {
                const activeFilters = Array.from(filterCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.getAttribute("data-filter"));

                tableRows.forEach(row => {
                    const rowStatus = row.getAttribute("data-status");
                    if (activeFilters.length === 0 || activeFilters.includes(rowStatus)) {
                        row.style.display = ""; // Show row
                    } else {
                        row.style.display = "none"; // Hide row
                    }
                });
            }

            // Add event listeners to checkboxes
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", filterTable);
            });

            // Call filterTable to apply the default filter on page load
            filterTable();
        });

        document.addEventListener("DOMContentLoaded", function () {
            const filterCheckboxes = document.querySelectorAll(".filter-checkbox");
            const searchInput = document.querySelector(".input_group input[type='search']");
            const tableRows = document.querySelectorAll("#mainTable tbody tr");

            function filterTable() {
                const searchQuery = searchInput.value.toLowerCase();
                const activeFilters = Array.from(filterCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.getAttribute("data-filter"));

                tableRows.forEach(row => {
                    const rowStatus = row.getAttribute("data-status");
                    const familyHead = row.querySelector("td:first-child").textContent.toLowerCase();

                    const matchesSearch = familyHead.includes(searchQuery);
                    const matchesFilter = activeFilters.length === 0 || activeFilters.includes(rowStatus);

                    if (matchesSearch && matchesFilter) {
                        row.style.display = ""; // Show row
                    } else {
                        row.style.display = "none"; // Hide row
                    }
                });
            }

            // Add event listeners to checkboxes and search input
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", filterTable);
            });

            searchInput.addEventListener("keyup", filterTable);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(window.location.search);
            const isAll = urlParams.get('id') === 'All';

            document.querySelectorAll('.addBg-admin').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default action
                    if (isAll) {
                        Swal.fire({
                            icon: 'info',
                            text: 'Please select a specific evacuation center first.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Check if the center is full
                        if (currentEvacueesCount >= evacuationCenterCapacity) {
                            Swal.fire({
                                icon: 'error',
                                text: 'You cannot admit more evacuees because the evacuation center has reached its full capacity.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Proceed to redirect if not full
                            const evacuationCenterId = button.getAttribute('data-ec-id');
                            window.location.href = `evacueesForm.php?id=${evacuationCenterId}`;
                        }
                    }
                });
            });
        });


    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/navbarECworkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>