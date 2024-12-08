<?php
session_start();
require_once '../../connection/conn.php';
require_once '../../connection/auth.php';
date_default_timezone_set('Asia/Manila');

// Set the timeout duration (in seconds)
// define('INACTIVITY_LIMIT', 300); // 5 minutes

// // Check if the user has been inactive for the defined limit
// if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_LIMIT)) {
//     // Destroy the session and redirect to the login page
//     session_unset();
//     session_destroy();
//     header("Location: ../../login.php?message=Session expired due to inactivity.");
//     exit();
// }

// // Update the last activity time
// $_SESSION['LAST_ACTIVITY'] = time();

// Validate session role
validateSession('admin');

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

} else {
    header("Location: ../../login.php");
    exit;
}

// Fetch evacuation centers for the current admin
$query = "SELECT id, name FROM evacuation_center WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$centersResult = $stmt->get_result();
$stmt->close();


// Determine selected evacuation center if any
$evacuationCenterId = $_GET['center_id'] ?? 'All';

// Fetch evacuees based on the selected evacuation center
if ($evacuationCenterId === 'All') {
    $evacueesQuery = "
        SELECT 
            e.id AS evacuee_id,
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.position AS number_of_members,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity,
            (SELECT COUNT(*) FROM members m WHERE m.evacuees_id = e.id) AS member_count,
            (SELECT GROUP_CONCAT(CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name, ' ', m.extension_name) SEPARATOR ', ') FROM members m WHERE m.evacuees_id = e.id) AS member_names
        FROM evacuees e
        WHERE e.admin_id = ?";
    $stmt = $conn->prepare($evacueesQuery);
    $stmt->bind_param("i", $admin_id);
} else {
    $evacueesQuery = "
        SELECT 
            e.id AS evacuee_id,
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.contact,
            e.age,
            e.gender AS sex,
            e.position AS number_of_members,
            e.barangay,
            e.`date`,
            e.status,
            e.disaster_type AS calamity,
            (SELECT COUNT(*) FROM members m WHERE m.evacuees_id = e.id) AS member_count,
            (SELECT GROUP_CONCAT(CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name, ' ', m.extension_name) SEPARATOR ', ') FROM members m WHERE m.evacuees_id = e.id) AS member_names
        FROM evacuees e
        WHERE e.admin_id = ? AND e.evacuation_center_id = ?";
    $stmt = $conn->prepare($evacueesQuery);
    $stmt->bind_param("ii", $admin_id, $evacuationCenterId);
}

$stmt->execute();
$evacueesResult = $stmt->get_result();
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

                        <label for="startDate">Start:</label>
                        <input type="date" id="startDate" class="filter-admin" onchange="filterTableByDate()">

                        <label for="endDate">End:</label>
                        <input type="date" id="endDate" class="filter-admin" onchange="filterTableByDate()">

                        <select id="filterBarangay" class="filter-admin" onchange="filterEvacuationCenter()">
                            <option value="All" <?php echo ($evacuationCenterId === 'All') ? 'selected' : ''; ?>>All
                            </option>
                            <?php if ($centersResult->num_rows > 0): ?>
                                <?php while ($center = $centersResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($center['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($evacuationCenterId == $center['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($center['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>No Evacuation Centers Found</option>
                            <?php endif; ?>
                        </select>

                        <button class="addBg-admin" onclick="exportEvacuees()">
                            Export
                        </button>


                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-personnel></special-personnel>



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
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="admit"
                                                    class="filter-checkbox" data-filter="Admitted" checked>
                                                <label for="admit">Admitted</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="transfer"
                                                    class="filter-checkbox" data-filter="Transfer" checked>
                                                <label for="transfer">Transfer</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="transferred"
                                                    class="filter-checkbox" data-filter="Transferred" checked>
                                                <label for="transferred">Transferred</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="moveout"
                                                    class="filter-checkbox" data-filter="Moved-out" checked>
                                                <label for="moveout">Moved-out</label>
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
                                        <th>Family Head</th>
                                        <th>Contact #</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th style="text-align: center;">Number of members</th>
                                        <th style="text-align: center;">Barangay</th>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;">Calamity</th>
                                        <th style="text-align: center;">Status</th>
                                        <!-- <th style="text-align: center;">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($evacueesResult->num_rows > 0): ?>
                                        <?php while ($row = $evacueesResult->fetch_assoc()): ?>
                                            <tr data-status="<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-date="<?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <td><?php echo htmlspecialchars($row['family_head'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['contact'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row['sex'], ENT_QUOTES, 'UTF-8'); ?></td>
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
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($row['barangay'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['calamity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" style="text-align: center;">No evacuees found.</td>
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
        function exportEvacuees() {
            const centerId = document.getElementById('filterBarangay').value; // Get selected center
            const checkboxes = document.querySelectorAll('.filter-checkbox:checked'); // Get checked statuses
            const selectedStatuses = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-filter')); // Map to data-filter values

            // Get selected date range (default to empty if no date selected)
            const startDate = document.getElementById('startDate').value || '';
            const endDate = document.getElementById('endDate').value || '';

            // SweetAlert confirmation before proceeding with export
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to export the evacuees' data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Generate the export URL
                    const url = `../export/export_evacuees.php?center_id=${centerId}&statuses=${encodeURIComponent(selectedStatuses.join(','))}&start_date=${startDate}&end_date=${endDate}`;
                    window.location.href = url; // Navigate to the export endpoint
                }
            });
        }

        function filterTableByDate() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const rows = document.querySelectorAll('#mainTable tbody tr');

            rows.forEach(row => {
                const dateStr = row.getAttribute('data-date');
                const rowDate = new Date(dateStr);

                // Show or hide the row based on the date range
                if ((!isNaN(startDate) && rowDate < startDate) || (!isNaN(endDate) && rowDate > endDate)) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
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


        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll('.filter-checkbox');
            const tableRows = document.querySelectorAll('#mainTable tbody tr');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    // Collect all checked filter values
                    const activeFilters = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.dataset.filter);

                    // Show/Hide rows based on active filters
                    tableRows.forEach(row => {
                        const status = row.dataset.status;
                        if (activeFilters.includes(status)) {
                            row.style.display = ""; // Show row
                        } else {
                            row.style.display = "none"; // Hide row
                        }
                    });
                });
            });

            // Trigger the change event on page load to apply default filters
            checkboxes.forEach(checkbox => checkbox.dispatchEvent(new Event('change')));
        });

        function filterEvacuationCenter() {
            const centerId = document.getElementById('filterBarangay').value;
            window.location.href = `?center_id=${centerId}`;
        }
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>
    <script src="../../includes/printReports.js"></script>
    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>