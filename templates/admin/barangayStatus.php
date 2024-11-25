<?php
session_start();
include("../../connection/conn.php");
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

// $query = "SELECT * FROM admin WHERE role = 'admin'";
// $result = $conn->query($query);

// Query to fetch admins with barangay, evacuation center count, and center details
$query = "
    SELECT 
        a.id AS admin_id, 
        a.barangay, 
        a.first_name, 
        a.middle_name, 
        a.last_name, 
        a.barangay_logo,
        COUNT(e.id) AS evacuation_center_count,
        GROUP_CONCAT(e.id) AS evacuation_center_ids,
        GROUP_CONCAT(e.name) AS evacuation_center_names,
        GROUP_CONCAT(e.capacity) AS evacuation_center_capacities,
        GROUP_CONCAT(e.location) AS evacuation_center_locations,
        GROUP_CONCAT(e.image) AS evacuation_center_images
    FROM admin a
    LEFT JOIN evacuation_center e ON a.id = e.admin_id
    WHERE a.role = 'admin'
    GROUP BY a.id
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Font Awesome-->
    <link rel="stylesheet" href="../../assets/fontawesome/all.css">
    <link rel="stylesheet" href="../../assets/fontawesome/fontawesome.min.css">
    <!--styles-->

    <link rel="icon" href="../../assets/img/zambo.png">

    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/utils/ecenter.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .ecList {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ecDot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .ecName {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ecDot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: grey;
        /* Default */
    }

    .ecDot.red {
        background-color: red;
    }

    .ecDot.yellow {
        background-color: yellow;
    }

    .ecDot.green {
        background-color: green;
    }

    .ecDot.grey {
        background-color: grey;
    }

    .statusCard {
        position: relative;
        /* Required for absolute positioning of ecDot */
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
                            <a href="barangayStatus.php">Barangay</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">View Account</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <div class="statusHeader">
                        <h3>Barangay in Zamboanga City</h3>
                    </div>

                    <div class="statusCard-container">
                        <?php while ($admin = $result->fetch_assoc()):
                            $admin_id = $admin['admin_id'];
                            $center_ids = $admin['evacuation_center_ids'] ? explode(',', $admin['evacuation_center_ids']) : [];
                            $center_names = $admin['evacuation_center_names'] ? explode(',', $admin['evacuation_center_names']) : [];
                            $center_capacities = $admin['evacuation_center_capacities'] ? explode(',', $admin['evacuation_center_capacities']) : [];
                            $center_images = $admin['evacuation_center_images'] ? explode(',', $admin['evacuation_center_images']) : [];

                            $overall_status_color = "grey"; // Default if no evacuation centers exist
                            $status_colors = [];

                            // Determine evacuation center statuses
                            if (!empty($center_ids)) {
                                foreach ($center_ids as $index => $center_id) {
                                    $center_id = (int) $center_id;
                                    $capacity = (int) $center_capacities[$index];

                                    // Calculate total families
                                    $family_count_sql = "SELECT COUNT(*) AS total_families FROM evacuees WHERE evacuation_center_id = ?";
                                    $family_count_stmt = $conn->prepare($family_count_sql);
                                    $family_count_stmt->bind_param("i", $center_id);
                                    $family_count_stmt->execute();
                                    $family_count_result = $family_count_stmt->get_result();
                                    $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                    // Determine status color based on occupancy
                                    if ($total_families === 0) {
                                        $status_colors[] = "grey";
                                    } else {
                                        $occupancy_percentage = ($total_families / $capacity) * 100;
                                        $status_colors[] = $occupancy_percentage < 70 ? "green" : ($occupancy_percentage < 100 ? "yellow" : "red");
                                    }
                                }

                                // Determine overall status based on evacuation center statuses
                                if (!in_array("green", $status_colors)) {
                                    if (array_unique($status_colors) === ["red"]) {
                                        $overall_status_color = "red"; // All centers are red
                                    } elseif (in_array("yellow", $status_colors)) {
                                        $overall_status_color = "yellow"; // Mix of red and yellow
                                    } else {
                                        $overall_status_color = "grey"; // All grey
                                    }
                                } else {
                                    $overall_status_color = "green"; // At least one green center
                                }
                            }
                            ?>
                            <div class="statusCard"
                                onclick="window.location.href='barangayEC.php?barangay=<?php echo urlencode($admin['barangay']); ?>&admin_id=<?php echo $admin_id; ?>'">
                                <!-- Upper-left ecDot -->
                                <div class="ecDot <?php echo $overall_status_color; ?>"
                                    style="position: absolute; top: 10px; left: 10px;"></div>

                                <img class="barangayLogo"
                                    src="<?php echo htmlspecialchars($admin['barangay_logo'] ?: '../../assets/img/zambo.png'); ?>"
                                    alt="Barangay Logo">

                                <a
                                    href="barangayEC.php?barangay=<?php echo urlencode($admin['barangay']); ?>&admin_id=<?php echo $admin_id; ?>">
                                    <h4 class="statusBgname"><?php echo htmlspecialchars($admin['barangay']); ?></h4>
                                </a>

                                <div class="modal-ecContainer">
                                    <a href="barangayEC.php?barangay=<?php echo urlencode($admin['barangay']); ?>&admin_id=<?php echo $admin_id; ?>"
                                        class="ecTitle">
                                        <h5 class="totalEc">
                                            <?php echo htmlspecialchars($admin['evacuation_center_count']); ?>
                                            Evacuation Center<?php echo $admin['evacuation_center_count'] > 1 ? 's' : ''; ?>
                                        </h5>
                                    </a>

                                    <div class="ecList-modal">
                                        <?php if ($admin['evacuation_center_count'] == 0): ?>
                                            <p class="noEcMessage">No Evacuation Center</p>
                                        <?php else: ?>
                                            <?php foreach ($center_ids as $index => $center_id):
                                                $center_name = htmlspecialchars($center_names[$index]);
                                                $status_color = $status_colors[$index];
                                                ?>
                                                <div class="ecList">
                                                    <div class="ecDot <?php echo $status_color; ?>"></div>
                                                    <p class="ecName"><?php echo $center_name; ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="bgAdmin">
                                    <h4><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name']); ?>
                                    </h4>
                                    <p>Admin</p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>


                </div>
            </div>
            <!-- <div class="main-wrapper">
                <div class="main-container">
                    <div class="statusHeader">
                        <h3>Barangay in Zamboanga City</h3>
                    </div>

                    <div class="statusCard-container">

                        <div class="statusCard" onclick="window.location.href='barangayEC.php'">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tetuan</h4>
                            </a>


                            <div class="modal-ecContainer">
                                <a href="barangayEC.php" class="ecTitle">
                                    <h5 class="totalEc">5 Evacuation Centers</h5>
                                </a>

                                <div class="ecList-modal">
                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Barangay Hall</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot yellow"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>
                                </div>
                            </div>


                            <div class="bgAdmin">
                                <h4>Mark Larenz Tabotabo</h5>
                                    <p>Admin</p>
                            </div>
                        </div>






                    </div>


                </div>
            </div> -->
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