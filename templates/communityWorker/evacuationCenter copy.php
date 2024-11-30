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
validateSession('worker');

if (isset($_SESSION['user_id'])) {
    $worker_id = $_SESSION['user_id'];

    // Fetch worker details and the admin's barangay
    $sql = "
        SELECT 
            w.first_name, 
            w.middle_name, 
            w.last_name, 
            w.extension_name, 
            w.email, 
            w.image, 
            w.admin_id, 
            a.barangay AS admin_barangay 
        FROM worker w
        JOIN admin a ON w.admin_id = a.id
        WHERE w.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();
        $admin_id = $worker['admin_id'];
        $admin_barangay = $worker['admin_barangay']; // Admin's barangay

        // Fetch all evacuation centers for this admin, excluding assigned centers
        $centers_sql = "
            SELECT id, name, location, capacity, image 
            FROM evacuation_center 
            WHERE admin_id = ? 
            AND id NOT IN (
                SELECT evacuation_center_id 
                FROM assigned_worker 
                WHERE worker_id = ? AND status = 'assigned'
            )";
        $centers_stmt = $conn->prepare($centers_sql);
        $centers_stmt->bind_param("ii", $admin_id, $worker_id);
        $centers_stmt->execute();
        $centers_result = $centers_stmt->get_result();

        // Fetch only evacuation centers assigned to this worker
        $assigned_centers_sql = "
            SELECT ec.id, ec.name, ec.location, ec.capacity, ec.image 
            FROM evacuation_center ec
            JOIN assigned_worker aw ON ec.id = aw.evacuation_center_id
            WHERE aw.worker_id = ? AND aw.status = 'assigned'";
        $assigned_centers_stmt = $conn->prepare($assigned_centers_sql);
        $assigned_centers_stmt->bind_param("i", $worker_id);
        $assigned_centers_stmt->execute();
        $assigned_centers_result = $assigned_centers_stmt->get_result();
    } else {
        header("Location: ../../login.php");
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
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
                            <a id="start"
                                href="evacuationCenter.php"><?php echo htmlspecialchars("Barangay " . $admin_barangay); ?></a>


                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Near Evacuation Center</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">

                    <h2 id="start">Assigned Evacuation Centers</h2>
                    <div class="bgEc-container" style="margin-top: 1em; margin-bottom: 10px;">
                        <?php if ($assigned_centers_result->num_rows > 0): ?>
                            <?php while ($center = $assigned_centers_result->fetch_assoc()):
                                $center_id = $center['id'];
                                $capacity = (int) $center['capacity'];

                                // Fetch total families in this center
                                $family_count_sql = "SELECT COUNT(*) AS total_families FROM evacuees WHERE evacuation_center_id = ?";
                                $family_count_stmt = $conn->prepare($family_count_sql);
                                $family_count_stmt->bind_param("i", $center_id);
                                $family_count_stmt->execute();
                                $family_count_result = $family_count_stmt->get_result();
                                $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                // Fetch total evacuees (families + members)
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

                                // Determine occupancy status color
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
                                    onclick="window.location.href='viewAssignedEC.php?id=<?php echo $center['id']; ?>&worker_id=<?php echo $worker_id; ?>'">
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

                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No assigned evacuation centers found for this worker.</p>
                        <?php endif; ?>
                    </div>
                    <!-- <special-navbar></special-navbar> -->
                    <h2>Other Evacuation Centers</h2>
                    <div class="bgEc-container" style="margin-top: 1em;">
                        <?php if ($centers_result->num_rows > 0): ?>
                            <?php while ($center = $centers_result->fetch_assoc()):
                                $center_id = $center['id'];
                                $capacity = (int) $center['capacity'];

                                // Fetch total families in this center
                                $family_count_sql = "SELECT COUNT(*) AS total_families FROM evacuees WHERE evacuation_center_id = ?";
                                $family_count_stmt = $conn->prepare($family_count_sql);
                                $family_count_stmt->bind_param("i", $center_id);
                                $family_count_stmt->execute();
                                $family_count_result = $family_count_stmt->get_result();
                                $total_families = ($family_count_result->num_rows > 0) ? $family_count_result->fetch_assoc()['total_families'] : 0;

                                // Fetch total evacuees (families + members)
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

                                // Determine occupancy status color
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
                                    onclick="window.location.href='evacuationView.php?id=<?php echo $center['id']; ?>'">
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
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No evacuation centers found for this admin.</p>
                        <?php endif; ?>
                    </div>



                </div>

            </div>
    </div>

    </main>

    </div>

    <button id="scroll-btn" class="scroll-btn at-top">
        <i class="fa-solid fa-chevron-down"></i>
    </button>
    <style>
        .scroll-btn {
            position: fixed;
            right: 20px;
            background-color: var(--clr-slate600);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.3s ease-in-out, opacity 0.3s, top 0.3s, bottom 0.3s;
        }

        .scroll-btn:hover {
            background-color: #000;
            transform: scale(1.1);
        }

        .scroll-btn i {
            font-size: 20px;
        }

        .scroll-btn.at-top {
            top: 20px;
            bottom: auto;
        }

        .scroll-btn.at-bottom {
            bottom: 20px;
            top: auto;
        }
    </style>

    <script>
        const scrollBtn = document.getElementById('scroll-btn');
        let isAtBottom = false;

        scrollBtn.addEventListener('click', () => {
            if (!isAtBottom) {
                // Scroll to the bottom
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: 'smooth'
                });
                // Update button state
                scrollBtn.classList.remove('at-top');
                scrollBtn.classList.add('at-bottom');
                scrollBtn.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
            } else {
                // Scroll to the top
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                // Update button state
                scrollBtn.classList.remove('at-bottom');
                scrollBtn.classList.add('at-top');
                scrollBtn.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
            }
            // Toggle state
            isAtBottom = !isAtBottom;
        });


    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/navbarWorkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>