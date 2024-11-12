<?php
require_once '../../connection/conn.php';
if (isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];

}

// Fetch evacuation centers for this admin_id
$sql = "SELECT id, name, location, capacity, image, created_at FROM evacuation_center WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$centers_found = ($result->num_rows > 0);
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
                            <a href="barangayStatus.php">Barangay Tetuan</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuation Centers</a>
                        </div>
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
                                $family_count_sql = "SELECT COUNT(*) AS total_families FROM evacuees WHERE evacuation_center_id = ?";
                                $family_count_stmt = $conn->prepare($family_count_sql);
                                $family_count_stmt->bind_param("i", $center_id);
                                $family_count_stmt->execute();
                                $family_count_result = $family_count_stmt->get_result();
                                $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                // Get total evacuees (families + members)
                                $evacuees_count_sql = "
                                    SELECT 
                                    (SELECT COUNT(*) FROM evacuees WHERE evacuation_center_id = ?) +
                                    (SELECT COUNT(*) FROM members WHERE evacuees_id IN 
                                    (SELECT id FROM evacuees WHERE evacuation_center_id = ?)
                                    ) AS total_evacuees";
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