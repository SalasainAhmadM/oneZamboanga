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

$evacuationCenterId = $_GET['id']; // Get the evacuation center ID from the URL parameter

// Fetch the evacuation center name
$evacuationCenterSql = "SELECT name FROM evacuation_center WHERE id = ?";
$evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
$evacuationCenterStmt->bind_param("i", $evacuationCenterId);
$evacuationCenterStmt->execute();
$evacuationCenterResult = $evacuationCenterStmt->get_result();
$evacuationCenter = $evacuationCenterResult->fetch_assoc();

$adminIdSql = "SELECT admin_id FROM evacuation_center WHERE id = ?";
$adminIdStmt = $conn->prepare($adminIdSql);
$adminIdStmt->bind_param("i", $evacuationCenterId);
$adminIdStmt->execute();
$adminIdResult = $adminIdStmt->get_result();
$admin = $adminIdResult->fetch_assoc();

if (!$admin) {
    echo "<p>Invalid evacuation center ID.</p>";
    exit;
}

$adminId = $admin['admin_id'];

//Fetch all evacuation centers, prioritizing those with the matching admin_id
$centersSql = "
    SELECT ec.id, ec.name, ec.location, ec.capacity, ec.admin_id, ec.image, ec.created_at, a.barangay 
    FROM evacuation_center ec
    LEFT JOIN admin a ON ec.admin_id = a.id
    ORDER BY 
        CASE WHEN ec.admin_id = ? THEN 1 ELSE 2 END, 
        ec.created_at DESC";
$centersStmt = $conn->prepare($centersSql);
$centersStmt->bind_param("i", $adminId);
$centersStmt->execute();
$centersResult = $centersStmt->get_result();
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
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .bgEc-img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
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
                            <a href="#">Transfer</a>
                        </div>



                        <select class="filter-admin" id="statusFilter" onchange="filterCenters()">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">

                    <special-navbar></special-navbar>

                    <div class="bgEc-container" style="margin-top: 1em;">
                        <?php if ($centersResult->num_rows > 0): ?>
                            <?php while ($center = $centersResult->fetch_assoc()):
                                $centerId = $center['id'];
                                // Exclude the current evacuation center
                                if ($centerId == $evacuationCenterId) {
                                    continue;
                                }
                                $capacity = (int) $center['capacity'];
                                $barangay = $center['barangay']; // Get barangay from the admin table
                        
                                // Fetch total families (with updated logic)
                                $familyCountSql = "
                        SELECT COUNT(*) AS total_families 
                        FROM evacuees 
                        WHERE evacuation_center_id = ? 
                        AND status != 'Transferred' 
                        AND status != 'Moved-out'
                        AND (status = 'Admitted' 
                            OR (status = 'Transfer' 
                                AND evacuation_center_id = origin_evacuation_center_id)
                        )
                    ";
                                $familyCountStmt = $conn->prepare($familyCountSql);
                                $familyCountStmt->bind_param("i", $centerId);
                                $familyCountStmt->execute();
                                $familyCountResult = $familyCountStmt->get_result();
                                $totalFamilies = ($familyCountResult->num_rows > 0) ? $familyCountResult->fetch_assoc()['total_families'] : 0;

                                // Fetch total evacuees (with updated logic)
                                $evacueesCountSql = "
                        SELECT 
                            (SELECT COUNT(*) FROM evacuees 
                             WHERE evacuation_center_id = ? 
                             AND status != 'Transferred' 
                             AND status != 'Moved-out'
                             AND (status = 'Admitted' 
                                 OR (status = 'Transfer' 
                                     AND evacuation_center_id = origin_evacuation_center_id)
                             )
                            ) +
                            (SELECT COUNT(*) FROM members 
                             WHERE evacuees_id IN 
                             (SELECT id FROM evacuees 
                              WHERE evacuation_center_id = ? 
                              AND status != 'Transferred' 
                              AND status != 'Moved-out'
                              AND (status = 'Admitted' 
                                  OR (status = 'Transfer' 
                                      AND evacuation_center_id = origin_evacuation_center_id)
                              )
                             )
                            ) AS total_evacuees
                    ";
                                $evacueesCountStmt = $conn->prepare($evacueesCountSql);
                                $evacueesCountStmt->bind_param("ii", $centerId, $centerId);
                                $evacueesCountStmt->execute();
                                $evacueesCountResult = $evacueesCountStmt->get_result();
                                $totalEvacuees = ($evacueesCountResult->num_rows > 0) ? $evacueesCountResult->fetch_assoc()['total_evacuees'] : 0;

                                // Determine status color based on occupancy and exclude red status centers
                                if ($totalFamilies === 0) {
                                    $statusColor = "grey";
                                    $showCenter = true;
                                } else {
                                    $occupancyPercentage = ($totalFamilies / $capacity) * 100;

                                    if ($occupancyPercentage < 70) {
                                        $statusColor = "green";
                                        $showCenter = true;
                                    } elseif ($occupancyPercentage >= 70 && $occupancyPercentage < 100) {
                                        $statusColor = "yellow";
                                        $showCenter = true;
                                    } else {
                                        $statusColor = "red";
                                        $showCenter = false;
                                    }
                                }

                                if ($showCenter):
                                    ?>
                                    <div class="bgEc-cards" data-status="<?php echo $statusColor; ?>"
                                        onclick="window.location.href='evacueesForm.php?id=<?php echo $centerId; ?>'">
                                        <div class="bgEc-status <?php echo $statusColor; ?>"></div>
                                        <img src="<?php echo !empty($center['image']) ? htmlspecialchars($center['image']) : '../../assets/img/evacuation-default.svg'; ?>"
                                            alt="" class="bgEc-img">
                                        <ul class="bgEc-info">
                                            <li><strong><?php echo htmlspecialchars($center['name']); ?></strong></li>
                                            <li>Location: <strong><?php echo htmlspecialchars($barangay); ?></strong>,
                                                <?php echo htmlspecialchars($center['location']); ?>
                                            </li>
                                            <li>Capacity: <?php echo htmlspecialchars($center['capacity']); ?> Families</li>
                                            <li>Total Families: <?php echo $totalFamilies; ?></li>
                                            <li>Total Evacuees: <?php echo $totalEvacuees; ?></li>
                                        </ul>
                                    </div>

                                    <?php
                                endif;
                            endwhile; ?>
                        <?php else: ?>
                            <p>No evacuation centers found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </main>

    </div>
    <script>
        function filterCenters() {
            const filterValue = document.getElementById('statusFilter').value;
            const centers = document.querySelectorAll('.bgEc-cards');

            centers.forEach(center => {
                const status = center.getAttribute('data-status');
                if (filterValue === 'active') {
                    // Show only centers that are not grey (active)
                    if (status === 'grey') {
                        center.style.display = 'none';
                    } else {
                        center.style.display = 'block';
                    }
                } else if (filterValue === 'inactive') {
                    // Show only centers that are grey (inactive)
                    if (status === 'grey') {
                        center.style.display = 'block';
                    } else {
                        center.style.display = 'none';
                    }
                }
            });
        }

        // Initial filter on page load
        document.addEventListener('DOMContentLoaded', function () {
            filterCenters(); // Call filter on page load to apply the default filter (active)
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