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
validateSession('admin');

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the barangay of the logged-in admin
    $query = "SELECT id AS admin_id, barangay FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the admin is found
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $barangay = $admin['barangay'];
    } else {
        echo "Admin details not found.";
        exit();
    }

    // Fetch evacuees with status 'Transfer' and 'Admit' for the logged-in admin
    $sql = "
    SELECT 
        e.id AS evacuee_id,
        CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
        e.contact,
        e.barangay,
        e.status,
        e.date,
        e.disaster_type,
        COUNT(m.id) AS member_count,
        GROUP_CONCAT(CONCAT(m.first_name, ' ', m.last_name) ORDER BY m.first_name ASC SEPARATOR ', ') AS member_names
    FROM evacuees e
    LEFT JOIN members m ON e.id = m.evacuees_id
    WHERE e.admin_id = ? 
      AND e.status IN ('Transfer', 'Admit') 
      AND (e.status != 'Transfer' OR e.evacuation_center_id != e.origin_evacuation_center_id)
    GROUP BY e.id
    ORDER BY e.date DESC;
    ";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $evacueesResult = $stmt->get_result();
} else {
    header("Location: ../../login.php");
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
                            <a href="#">Request Admission Transfer</a>

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
                    <!-- <special-navbar></special-navbar> -->



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
                                                <input type="checkbox" name="evacuees" id="transfer">
                                                <label for="transfer">For Transfer</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="admit">
                                                <label for="admit">For Admission</label>
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
                                        <th style="text-align: center;">Address</th>
                                        <th style="text-align: center;">Date</th>
                                        <th style="text-align: center;">Calamity</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="evacueesTableBody">
                                    <?php if ($evacueesResult->num_rows > 0): ?>
                                        <?php while ($row = $evacueesResult->fetch_assoc()): ?>
                                            <tr class="evacuee-row"
                                                data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                                onclick="window.location.href='viewEvacueesDetails.php?id=<?php echo $row['evacuee_id']; ?>'">
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
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($row['barangay']); ?>
                                                </td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($row['date']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo htmlspecialchars($row['disaster_type']); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php echo ($row['status'] === 'Transfer') ? 'Request Transfer' : 'For Approval'; ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="viewEvacueesDetails.php?id=<?php echo $row['evacuee_id']; ?>"
                                                        class="view-action">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" style="text-align: center;">No evacuees found.</td>
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
        document.addEventListener('DOMContentLoaded', () => {
            // Get filter checkboxes
            const transferCheckbox = document.getElementById('transfer');
            const admitCheckbox = document.getElementById('admit');
            const evacueeRows = document.querySelectorAll('.evacuee-row');

            // Function to filter evacuees based on selected filters
            const filterEvacuees = () => {
                const showTransfer = transferCheckbox.checked;
                const showAdmit = admitCheckbox.checked;

                evacueeRows.forEach(row => {
                    const status = row.dataset.status;

                    if ((showTransfer && status === 'Transfer') || (showAdmit && status === 'Admit') || (!showTransfer && !showAdmit)) {
                        row.style.display = ''; // Show row
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });
            };

            // Add event listeners to filter checkboxes
            transferCheckbox.addEventListener('change', filterEvacuees);
            admitCheckbox.addEventListener('change', filterEvacuees);
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