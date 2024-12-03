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

if (isset($_GET['admin_id']) && $_GET['admin_id'] !== "all") {
    $admin_id = $_GET['admin_id'];

    // Fetch evacuation centers for this admin_id
    $sql = "SELECT id, name, location, capacity, image, created_at FROM evacuation_center WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $centers_found = ($result->num_rows > 0);

    // Fetch the barangay value for this admin_id
    $barangay_query = "SELECT barangay FROM admin WHERE id = ?";
    $barangay_stmt = $conn->prepare($barangay_query);
    $barangay_stmt->bind_param("i", $admin_id);
    $barangay_stmt->execute();
    $barangay_result = $barangay_stmt->get_result();

    $barangay = $barangay_result->num_rows > 0 ? $barangay_result->fetch_assoc()['barangay'] : 'Unknown Barangay';
} else {
    // Fetch all evacuation centers when "all" is selected
    $sql = "SELECT id, name, location, capacity, image, created_at FROM evacuation_center";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $centers_found = ($result->num_rows > 0);

    $barangay = 'All Barangays';
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
                <div class="separator">
                    <div class="info">
                        <div class="info-header">
                            <?php if ($barangay !== 'All Barangays'): ?>
                                <a href="barangayStatus.php">Barangay <?= htmlspecialchars($barangay) ?></a>
                            <?php else: ?>
                                <a href="barangayStatus.php">Zamboanga City</a>
                            <?php endif; ?>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuation Centers</a>
                        </div>
                        <?php
                        $barangay_query = "SELECT id, barangay FROM admin WHERE role != 'superadmin'";
                        $barangay_result = $conn->query($barangay_query);

                        $current_barangay = isset($_GET['barangay']) ? $_GET['barangay'] : 'all';
                        $current_admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : 'all';
                        ?>
                        <!-- Barangay Filter -->
                        <select class="filter-admin" id="barangayFilter" onchange="filterBarangay()">
                            <option value="all" data-admin-id="all" <?php echo ($current_barangay === 'all' && $current_admin_id === 'all') ? 'selected' : ''; ?>>
                                All Barangays
                            </option>
                            <?php if ($barangay_result->num_rows > 0): ?>
                                <?php while ($row = $barangay_result->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['barangay']); ?>"
                                        data-admin-id="<?php echo htmlspecialchars($row['id']); ?>" <?php echo ($current_barangay === $row['barangay'] && $current_admin_id == $row['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['barangay']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option disabled>No barangays available</option>
                            <?php endif; ?>
                        </select>


                        <select class="filter-admin" id="statusFilter" onchange="filterCenters()">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </header>
            <div class="main-wrapper bgEcWrapper">
                <div class="main-container bgEcList">
                    <div class="bgEc-container">
                        <?php if ($centers_found): ?>
                            <?php while ($row = $result->fetch_assoc()):
                                $center_id = $row['id'];
                                $capacity = (int) $row['capacity'];

                                // Get total families
                                $family_count_sql = "
                        SELECT COUNT(*) AS total_families 
                        FROM evacuees 
                        WHERE evacuation_center_id = ? 
                        AND (
                            status = 'Admitted' OR 
                            (status = 'Transfer' AND evacuation_center_id = origin_evacuation_center_id)
                        )
                    ";
                                $family_count_stmt = $conn->prepare($family_count_sql);
                                $family_count_stmt->bind_param("i", $center_id);
                                $family_count_stmt->execute();
                                $family_count_result = $family_count_stmt->get_result();
                                $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                // Get total evacuees (families + members)
                                $evacuees_count_sql = "
                        SELECT 
                            (SELECT COUNT(*) 
                             FROM evacuees 
                             WHERE evacuation_center_id = ? 
                             AND (
                                status = 'Admitted' OR 
                                (status = 'Transfer' AND evacuation_center_id = origin_evacuation_center_id)
                             )
                            ) +
                            (SELECT COUNT(*) 
                             FROM members 
                             WHERE evacuees_id IN (
                                 SELECT id 
                                 FROM evacuees 
                                 WHERE evacuation_center_id = ? 
                                 AND (
                                    status = 'Admitted' OR 
                                    (status = 'Transfer' AND evacuation_center_id = origin_evacuation_center_id)
                                 )
                             )
                            ) AS total_evacuees
                    ";
                                $evacuees_count_stmt = $conn->prepare($evacuees_count_sql);
                                $evacuees_count_stmt->bind_param("ii", $center_id, $center_id);
                                $evacuees_count_stmt->execute();
                                $evacuees_count_result = $evacuees_count_stmt->get_result();
                                $total_evacuees = ($evacuees_count_result->num_rows > 0) ? $evacuees_count_result->fetch_assoc()['total_evacuees'] : 0;

                                // Determine status color based on occupancy percentage
                                if ($total_families === 0) {
                                    $status_color = "grey";
                                } else {
                                    $occupancy_percentage = ($total_families / $capacity) * 100;
                                    if ($occupancy_percentage < 70) {
                                        $status_color = "green";
                                    } elseif ($occupancy_percentage >= 70 && $occupancy_percentage < 100) {
                                        $status_color = "yellow";
                                    } else {
                                        $status_color = "red";
                                    }
                                }
                                ?>
                                <div class="bgEc-cards"
                                    data-status="<?php echo $status_color === 'grey' ? 'inactive' : 'active'; ?>"
                                    onclick="window.location.href='viewEvacuation.php?id=<?php echo $row['id']; ?>'">
                                    <div class="bgEc-status <?php echo $status_color; ?>"></div>
                                    <img src="<?php echo !empty($row['image']) ? '../../assets/img/' . $row['image'] : '../../assets/img/ecDefault.svg'; ?>"
                                        alt="" class="bgEc-img">
                                    <ul class="bgEc-info">
                                        <li><strong><?php echo htmlspecialchars($row['name']); ?></strong></li>
                                        <li>Location: <?php echo htmlspecialchars($row['location']); ?></li>
                                        <li>Capacity: <?php echo htmlspecialchars($row['capacity']); ?> Families</li>
                                        <li>Total Families: <?php echo $total_families; ?></li>
                                        <li>Total Evacuees: <?php echo $total_evacuees; ?></li>
                                    </ul>
                                </div>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="no-centers-message">
                                <p>No registered evacuation centers for this barangay.</p>
                            </div>
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

                // Show or hide centers based on the selected filter
                if (filterValue === 'active' && status === 'inactive') {
                    center.style.display = 'none';
                } else if (filterValue === 'inactive' && status === 'active') {
                    center.style.display = 'none';
                } else {
                    center.style.display = 'block';
                }
            });
        }


        document.addEventListener('DOMContentLoaded', filterCenters);

        function filterBarangay() {
            const barangaySelect = document.getElementById('barangayFilter');
            const selectedOption = barangaySelect.options[barangaySelect.selectedIndex];

            if (selectedOption) {
                const barangay = selectedOption.value;
                const adminId = selectedOption.getAttribute('data-admin-id');

                // Check if the selected option is "All Barangays"
                if (adminId === "all") {
                    // Redirect to the URL without filtering
                    const url = `http://localhost/onezamboanga/templates/admin/barangayEC.php?barangay=all&admin_id=all`;
                    window.location.href = url;
                } else {
                    // Redirect to the URL with the selected barangay and admin_id
                    const url = `http://localhost/onezamboanga/templates/admin/barangayEC.php?barangay=${barangay}&admin_id=${adminId}`;
                    window.location.href = url;
                }
            }
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>