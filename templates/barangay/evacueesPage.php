<?php
require_once '../../connection/conn.php';
require_once '../../connection/auth.php';
validateSession('admin');

$evacuationCenterId = $_GET['id'];  // Get the evacuation center ID from the URL parameter

// Fetch the evacuation center name
$evacuationCenterSql = "SELECT name FROM evacuation_center WHERE id = ?";
$evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
$evacuationCenterStmt->bind_param("i", $evacuationCenterId);
$evacuationCenterStmt->execute();
$evacuationCenterResult = $evacuationCenterStmt->get_result();
$evacuationCenter = $evacuationCenterResult->fetch_assoc();

// Prepare and execute the SQL query
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
    WHERE e.evacuation_center_id = ? AND e.status != 'Transfer' 
    GROUP BY e.id
    ORDER BY e.date DESC;
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evacuationCenterId);  // Bind the evacuation center ID parameter
$stmt->execute();
$result = $stmt->get_result();

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
                            <a
                                href="viewEC.php?id=<?php echo $evacuationCenterId; ?>"><?php echo $evacuationCenter['name']; ?></a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <button class="addBg-admin"
                            onclick="window.location.href='evacueesForm.php?id=<?php echo $evacuationCenterId; ?>'">
                            Admit
                            <!-- <i class="fa-solid fa-plus"></i> -->
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

                                <!-- The modal or filter popup -->
                                <div class="modal">
                                    <div class="modal-content">
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="admit"
                                                    class="filter-checkbox" data-filter="Admitted">
                                                <label for="admit">Admitted</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="moveout"
                                                    class="filter-checkbox" data-filter="Moved-out">
                                                <label for="moveout">Moved-out</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="transfer"
                                                    class="filter-checkbox" data-filter="Transferred">
                                                <label for="transfer">Transferred</label>
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
                                        <!-- <th>Damaged</th> -->
                                        <!-- <th style="text-align: center;">Cost of Damaged</th> -->
                                        <!-- <th>Status of Occupancy</th> -->
                                        <!-- <th>Action</th> -->
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
                                                    <a href="viewEvacuees.php?id=<?php echo $row['evacuee_id']; ?>"
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
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>