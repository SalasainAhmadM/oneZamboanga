<?php
session_start();
include("../../connection/conn.php");
require_once '../../connection/auth.php';
validateSession('admin');

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, email, image FROM admin WHERE id = ?";
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

// Query to fetch workers under the logged-in admin
$worker_query = "SELECT id, first_name, middle_name, last_name, extension_name, email, contact, position, image, status 
 FROM worker 
 WHERE admin_id = ? AND (verification_code IS NULL OR verification_code = '')";
$worker_stmt = $conn->prepare($worker_query);
$worker_stmt->bind_param("i", $admin_id);
$worker_stmt->execute();
$worker_result = $worker_stmt->get_result();

// Fetch unique positions for filter options
$position_query = "SELECT DISTINCT position FROM worker WHERE admin_id = ? AND (verification_code IS NULL OR verification_code = '')";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("i", $admin_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();
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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <button class="addBg-admin" onclick="window.location.href='addAccount.php'">
                            Create
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-personnel></special-personnel>
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
                                            <!-- "All" option to clear all filters -->
                                            <div class="option-content">
                                                <input type="checkbox" name="positionFilter" id="position-all"
                                                    value="all" class="position-filter">
                                                <label for="position-all">All</label>
                                            </div>
                                            <?php while ($position = $position_result->fetch_assoc()): ?>
                                                <div class="option-content">
                                                    <input type="checkbox" name="positionFilter"
                                                        id="position-<?php echo htmlspecialchars($position['position']); ?>"
                                                        value="<?php echo htmlspecialchars($position['position']); ?>"
                                                        class="position-filter">
                                                    <label
                                                        for="position-<?php echo htmlspecialchars($position['position']); ?>">
                                                        <?php echo htmlspecialchars($position['position']); ?>
                                                    </label>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input_group">
                                <input type="search" id="searchInput" placeholder="Search by Name or Email...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>

                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Position</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="workerTableBody">
                                    <?php if ($worker_result->num_rows > 0): ?>
                                        <?php while ($worker = $worker_result->fetch_assoc()): ?>
                                            <tr class="worker-row"
                                                data-position="<?php echo htmlspecialchars($worker['position']); ?>">
                                                <td><?php echo htmlspecialchars($worker['first_name'] . ' ' . $worker['middle_name'] . ' ' . $worker['last_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($worker['email']); ?></td>
                                                <td><?php echo htmlspecialchars($worker['contact']); ?></td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($worker['position']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="workersProfile.php?id=<?php echo urlencode($worker['id']); ?>"
                                                        class="view-action">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center;">No Community Workers Yet</td>
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
        function filterWorkers() {
            // Check if "All" is selected
            const allCheckbox = document.getElementById('position-all');
            const selectedPositions = Array.from(document.querySelectorAll('.position-filter:checked'))
                .map(checkbox => checkbox.value.toLowerCase());

            // If "All" is selected, clear other selections and show all rows
            if (allCheckbox.checked) {
                document.querySelectorAll('.position-filter').forEach(checkbox => {
                    if (checkbox !== allCheckbox) checkbox.checked = false;
                });
                selectedPositions.length = 0; // Clear selected positions
            }

            const searchInput = document.getElementById('searchInput').value.toLowerCase();

            // Filter rows based on selected positions and search term
            document.querySelectorAll('.worker-row').forEach(row => {
                const position = row.getAttribute('data-position').toLowerCase();
                const name = row.cells[0].innerText.toLowerCase();
                const email = row.cells[1].innerText.toLowerCase();

                const matchesPosition = selectedPositions.length === 0 || selectedPositions.includes(position);
                const matchesSearch = name.includes(searchInput) || email.includes(searchInput);

                row.style.display = matchesPosition && matchesSearch ? '' : 'none';
            });

            // Show or hide "No matching data found" message
            const hasVisibleRow = Array.from(document.querySelectorAll('.worker-row')).some(row => row.style.display === '');
            document.querySelector('.no-match-message').style.display = hasVisibleRow ? 'none' : 'block';
        }

        // Event listeners for position checkboxes
        document.querySelectorAll('.position-filter').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (this.id !== 'position-all') {
                    document.getElementById('position-all').checked = false; // Uncheck "All" if specific position is selected
                }
                filterWorkers();
            });
        });

        // Event listener for search input
        document.getElementById('searchInput').addEventListener('input', filterWorkers);
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/personnelpageNav.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>