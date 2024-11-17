<?php
require_once '../../connection/conn.php';

$evacuationCenterId = $_GET['id'];  // Get the evacuation center ID from the URL parameter

// Fetch the evacuation center name
$evacuationCenterSql = "SELECT name FROM evacuation_center WHERE id = ?";
$evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
$evacuationCenterStmt->bind_param("i", $evacuationCenterId);
$evacuationCenterStmt->execute();
$evacuationCenterResult = $evacuationCenterStmt->get_result();
$evacuationCenter = $evacuationCenterResult->fetch_assoc();

// Fetch the admin ID associated with this evacuation center
$adminIdSql = "SELECT admin_id FROM evacuation_center WHERE id = ?";
$adminIdStmt = $conn->prepare($adminIdSql);
$adminIdStmt->bind_param("i", $evacuationCenterId);
$adminIdStmt->execute();
$adminIdResult = $adminIdStmt->get_result();
$admin_id = $adminIdResult->fetch_assoc()['admin_id'];

// Fetch evacuation centers associated with this admin, excluding the current center
$sql = "SELECT id, name, location, image, capacity 
        FROM evacuation_center 
        WHERE admin_id = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $admin_id, $evacuationCenterId);
$stmt->execute();
$centers_result = $stmt->get_result();
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
                        <?php if ($centers_result->num_rows > 0): ?>
                            <?php while ($center = $centers_result->fetch_assoc()):
                                $center_id = $center['id'];
                                $capacity = (int) $center['capacity'];

                                // Fetch total families
                                $family_count_sql = "SELECT COUNT(*) AS total_families FROM evacuees WHERE evacuation_center_id = ?";
                                $family_count_stmt = $conn->prepare($family_count_sql);
                                $family_count_stmt->bind_param("i", $center_id);
                                $family_count_stmt->execute();
                                $family_count_result = $family_count_stmt->get_result();
                                $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                // Fetch total evacuees
                                $evacuees_count_sql = "
                                SELECT 
                                (SELECT COUNT(*) FROM evacuees WHERE evacuation_center_id = ?) +
                                (SELECT COUNT(*) FROM members WHERE evacuees_id IN 
                                (SELECT id FROM evacuees WHERE evacuation_center_id = ?)
                                 ) AS total_evacuees
                                ";
                                $evacuees_count_stmt = $conn->prepare($evacuees_count_sql);
                                $evacuees_count_stmt->bind_param("ii", $center_id, $center_id);
                                $evacuees_count_stmt->execute();
                                $evacuees_count_result = $evacuees_count_stmt->get_result();
                                $total_evacuees = ($evacuees_count_result->num_rows > 0) ? $evacuees_count_result->fetch_assoc()['total_evacuees'] : 0;

                                // Determine status color based on occupancy and exclude red status centers
                                if ($total_families === 0) {
                                    $status_color = "grey";
                                    $show_center = true;
                                } else {
                                    $occupancy_percentage = ($total_families / $capacity) * 100;

                                    if ($occupancy_percentage < 70) {
                                        $status_color = "green";
                                        $show_center = true;
                                    } elseif ($occupancy_percentage >= 70 && $occupancy_percentage < 100) {
                                        $status_color = "yellow";
                                        $show_center = true;
                                    } else {
                                        $status_color = "red";
                                        $show_center = false;
                                    }
                                }

                                if ($show_center):
                                    ?>
                                    <div class="bgEc-cards"
                                        onclick="window.location.href='evacueesForm.php?id=<?php echo $center['id']; ?>'">
                                        <div class="bgEc-status <?php echo $status_color; ?>"></div>
                                        <img src="<?php echo !empty($center['image']) ? htmlspecialchars($center['image']) : '../../assets/img/evacuation-default.svg'; ?>"
                                            alt="" class="bgEc-img">

                                        <ul class="bgEc-info">
                                            <li><strong><?php echo htmlspecialchars($center['name']); ?></strong></li>
                                            <li>Location: <?php echo htmlspecialchars($center['location']); ?></li>
                                            <li>Capacity: <?php echo htmlspecialchars($center['capacity']); ?> Families</li>
                                            <li>Total Families: <?php echo $total_families; ?></li>
                                            <li>Total Evacuees: <?php echo $total_evacuees; ?></li>
                                        </ul>
                                    </div>
                                    <?php
                                endif;
                            endwhile; ?>
                        <?php else: ?>
                            <p>No evacuation centers found for this admin.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </main>

    </div>


    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

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