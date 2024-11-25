<?php
session_start();
include("../../connection/conn.php");
require_once '../../connection/auth.php';
validateSession('admin');

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
$admin_image = !empty($admin['image']) ? $admin['image'] : "../../assets/img/undraw_male_avatar_g98d.svg";

// Query to get the total number of workers for the logged-in admin, excluding those with a verification code
$count_sql = "SELECT COUNT(*) AS total_workers FROM worker WHERE (verification_code IS NULL OR verification_code = '') AND admin_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $admin_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_workers = ($count_result->num_rows > 0) ? $count_result->fetch_assoc()['total_workers'] : 0;

// Query to get the total number of evacuation centers for this admin
$center_count_sql = "SELECT COUNT(*) AS total_centers FROM evacuation_center WHERE admin_id = $admin_id";
$center_count_result = $conn->query($center_count_sql);
$total_centers = ($center_count_result->num_rows > 0) ? $center_count_result->fetch_assoc()['total_centers'] : 0;

// Query to get the evacuation centers for this admin
$centers_sql = "SELECT id, name FROM evacuation_center WHERE admin_id = ?";
$centers_stmt = $conn->prepare($centers_sql);
$centers_stmt->bind_param("i", $admin_id);
$centers_stmt->execute();
$centers_result = $centers_stmt->get_result();

$centers = [];
$total_evacuees_with_members = 0;  // Initialize the variable

while ($row = $centers_result->fetch_assoc()) {
    $center_id = $row['id'];
    $center_name = $row['name'];

    // Count evacuees and their members for each center, excluding 'Transfer' and 'Moved-out'
    $count_total_sql = "
        SELECT 
            (SELECT COUNT(*) 
             FROM evacuees 
             WHERE evacuation_center_id = ? 
             AND status NOT IN ('Transfer', 'Moved-out')
            ) +
            (SELECT COUNT(*) 
             FROM members 
             WHERE evacuees_id IN 
                 (SELECT id 
                  FROM evacuees 
                  WHERE evacuation_center_id = ? 
                  AND status NOT IN ('Transfer', 'Moved-out'))
            ) AS total_count
    ";
    $count_total_stmt = $conn->prepare($count_total_sql);
    $count_total_stmt->bind_param("ii", $center_id, $center_id);
    $count_total_stmt->execute();
    $total_result = $count_total_stmt->get_result();
    $total_count = ($total_result->num_rows > 0) ? $total_result->fetch_assoc()['total_count'] : 0;

    $centers[] = [
        'name' => $center_name,
        'evacuees' => $total_count
    ];

    $total_evacuees_with_members += $total_count;
}

// Query to get all evacuation centers for this admin for the chart
$all_centers_sql = "SELECT id, name FROM evacuation_center WHERE admin_id = ?";
$all_centers_stmt = $conn->prepare($all_centers_sql);
$all_centers_stmt->bind_param("i", $admin_id);
$all_centers_stmt->execute();
$all_centers_result = $all_centers_stmt->get_result();

$all_centers = [];
while ($row = $all_centers_result->fetch_assoc()) {
    $center_id = $row['id'];
    $center_name = $row['name'];

    // Count evacuees and their members for each center, excluding 'Transfer' and 'Moved-out'
    $count_total_sql = "
        SELECT 
            (SELECT COUNT(*) 
             FROM evacuees 
             WHERE evacuation_center_id = ? 
             AND status NOT IN ('Transfer', 'Moved-out')
            ) +
            (SELECT COUNT(*) 
             FROM members 
             WHERE evacuees_id IN 
                 (SELECT id 
                  FROM evacuees 
                  WHERE evacuation_center_id = ? 
                  AND status NOT IN ('Transfer', 'Moved-out'))
            ) AS total_count
    ";
    $count_total_stmt = $conn->prepare($count_total_sql);
    $count_total_stmt->bind_param("ii", $center_id, $center_id);
    $count_total_stmt->execute();
    $total_result = $count_total_stmt->get_result();
    $total_count = ($total_result->num_rows > 0) ? $total_result->fetch_assoc()['total_count'] : 0;

    $all_centers[] = [
        'id' => $center_id,
        'name' => $center_name,
        'evacuees' => $total_count
    ];
}


// Query to get the latest 4 evacuation centers for "Evacuation Center Overview"
$latest_centers_sql = "
    SELECT id, name 
    FROM evacuation_center 
    WHERE admin_id = ? 
    ORDER BY id DESC 
    LIMIT 10
";
$latest_centers_stmt = $conn->prepare($latest_centers_sql);
$latest_centers_stmt->bind_param("i", $admin_id);
$latest_centers_stmt->execute();
$latest_centers_result = $latest_centers_stmt->get_result();

$latest_centers = [];
while ($row = $latest_centers_result->fetch_assoc()) {
    $center_id = $row['id'];
    $center_name = $row['name'];

    // Count evacuees and their members for each center
    $count_total_stmt->bind_param("ii", $center_id, $center_id);
    $count_total_stmt->execute();
    $total_result = $count_total_stmt->get_result();
    $total_count = ($total_result->num_rows > 0) ? $total_result->fetch_assoc()['total_count'] : 0;

    // Only add centers with at least one evacuee
    if ($total_count > 0) {
        $latest_centers[] = [
            'id' => $center_id,
            'name' => $center_name,
            'evacuees' => $total_count
        ];
    }

    // Limit to the latest 4 centers with evacuees
    if (count($latest_centers) >= 4) {
        break;
    }
}

// Fetch the admin's notifications
$notif_sql = "SELECT notification_msg, created_at 
 FROM notifications 
 WHERE logged_in_id = ? AND user_type = 'admin' 
 ORDER BY created_at DESC";
$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param("i", $admin_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();

$notif_count = $notif_result->num_rows;

// Check if there are notifications with status 'notify' for the admin
$notif_check_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE logged_in_id = ? AND user_type = 'admin' AND status = 'notify'";
$stmt = $conn->prepare($notif_check_query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$has_unread_notifications = $row['unread_count'] > 0;

$bell_icon_class = $has_unread_notifications ? "bell-icon-red" : "bell-icon-gray";

// Retrieve notifications that are not cleared
$notif_query = "SELECT * FROM notifications 
                WHERE logged_in_id = ? 
                AND user_type = 'admin' 
                AND status != 'cleared' 
                ORDER BY created_at DESC";
$notif_stmt = $conn->prepare($notif_query);
$notif_stmt->bind_param("i", $admin_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();

// Retrieve feeds
$feeds_sql = "SELECT feed_msg, created_at FROM feeds WHERE logged_in_id = ? AND user_type = 'admin' ORDER BY created_at DESC";
$feeds_stmt = $conn->prepare($feeds_sql);
$feeds_stmt->bind_param("i", $admin_id);
$feeds_stmt->execute();
$feeds_result = $feeds_stmt->get_result();

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
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard-mobile.css">


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .bell-icon-red {
        color: var(--clr-red);
    }

    .bell-icon-gray {
        color: var(--clr-grey);
    }

    .container .right-section .top #bell-icon.bell-icon-red::after {
        content: "";
        width: 8px;
        height: 8px;
        position: absolute;
        background: var(--clr-red);
        border-radius: 50%;
        right: 0;
    }
</style>

<body>

    <div class="container">

        <aside class="left-section">
            <special-logo></special-logo>

            <!-- <div class="logo">
                <button class="menu-btn open" id="menu-close">
                    <i class="fa-regular fa-circle-left"></i>
                </button>
                <img src="../../assets/img/logo5.png" alt="">
                <a href="dahsboard_barangay.php">One Zamboanga</a>
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
                <h5><b>Evacuation Center Management System</b></h5>
            </header>

            <div class="separator">
                <div class="info">
                    <h3>Dashboard</h3>
                    <!-- <a href="#">View All</a> -->
                </div>
                <div class="search">
                    <!-- <button type="submit">

                    </button> -->
                    <a href="#">

                        <!-- <i class="fa-solid fa-plus"></i> -->
                    </a>
                    <!-- <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search..."> -->
                </div>
            </div>

            <div class="analytics">
                <div class="item">
                    <div class="progress">
                        <div class="info">
                            <h5>Community Workers</h5>
                            <p>Total: <?php echo $total_workers; ?></p>
                        </div>
                    </div>
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="item">
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuees</h5>
                            <p>Total: <?php echo $total_evacuees_with_members; ?></p>
                        </div>
                    </div>
                    <i class="fa-solid fa-children"></i>
                </div>

                <div class="item spanning"> <!--spanning-->
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuation Centers</h5>
                            <p>Total: <?php echo $total_centers; ?></p>
                        </div>
                    </div>
                    <i class="fa-solid fa-person-shelter"></i>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {

                        const items = document.querySelectorAll(".item");

                        items.forEach((item) => {
                            item.addEventListener("click", () => {
                                if (item.classList.contains("spanning")) {
                                    window.location.href = "evacuation.php";
                                } else if (item.querySelector("h5").textContent === "Community Workers") {
                                    window.location.href = "personnelPage.php";
                                } else if (item.querySelector("h5").textContent === "Evacuees") {
                                    window.location.href = "evacueesPage.php";
                                }
                            });
                        });
                    });

                </script>
                <!-- <div class="item">
                    <div class="progress">
                        <div class="info">
                            <h5>Distributions</h5>
                            <p>Total: 4</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-person-shelter"></i>
                </div> -->
            </div>

            <div class="separator">
                <div class="info">
                    <h3>Evacuation Center Overview</h3>
                    <a href="evacuation.php" style="text-decoration: underline;">View All</a>
                </div>
                <!-- <input type="date" value="2024-11-09"> -->
            </div>
            <div class="ecenter">
                <?php if (!empty($latest_centers)): ?>
                    <?php foreach ($latest_centers as $center): ?>
                        <div class="item">
                            <div class="left">
                                <div class="icon">
                                    <i class="fa-solid fa-person-shelter"></i>
                                </div>
                                <div class="details">
                                    <h5><?php echo htmlspecialchars($center['name']); ?></h5>
                                    <p><?php echo $center['evacuees']; ?> Evacuees</p>
                                </div>
                            </div>
                            <a href="viewEC.php?id=<?php echo $center['id']; ?>"><i class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-message">No evacuation center with evacuees</p>
                <?php endif; ?>
            </div>

        </main>

        <div class="ecGraphs-container">

            <div class="ecGraph-content">
                <div class="profileHeader">


                    <h2>Demographics</h2>
                    <div class="sideProfile">
                        <i class="fa-solid fa-bars feedShow"></i>



                    </div>

                </div>


                <div class="ecGraph">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

        </div>

        <aside class="right-section feed">
            <div class="top">
                <div class="icons">
                    <i class="fa-regular fa-bell margin <?php echo $bell_icon_class; ?>" id="bell-icon"></i>
                    <i class="fa-solid fa-chart-pie chartShow"></i>
                </div>

                <i class="fa-solid fa-arrow-left" id="act-icon" style="display: none;"></i>


                <div class="profile">
                    <div class="left">
                        <img src="<?php echo htmlspecialchars($admin_image); ?>" alt="Profile Image">
                        <div class="user">
                            <h5><?php echo htmlspecialchars($admin_name); ?></h5>
                            <a href="myProfile.php" style="text-decoration: underline;">View</a>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right" onclick="window.location.href='myProfile.php'"></i>
                </div>

            </div>

            <!--level of criticality-->
            <div class="separator" id="first">
                <!-- <h4>Level of Criticality</h4> -->
                <h4 class="actHeader">Acvity Feed</h4>
                <!-- <h4 class="notifHeader" style="display: none;">Notifications</h4> -->
            </div>

            <div class="actFeed">
                <div class="feed-content">
                    <?php if ($feeds_result->num_rows > 0): ?>
                        <?php
                        while ($feed = $feeds_result->fetch_assoc()) {
                            // Format the date as 'm-d-Y' for consistency
                            $feed_date = date("m-d-Y", strtotime($feed['created_at']));
                            ?>
                            <div class="feeds">
                                <div class="feeds-date">
                                    <p><?php echo $feed_date; ?></p>
                                    <div class="linee"></div>
                                </div>
                                <p class="feed"><?php echo htmlspecialchars($feed['feed_msg']); ?></p>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <!-- Display this message if no feeds are available -->
                        <div class="feeds">
                            <p class="feed">No activity feeds available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <div class="separator notifHeader" id="first">
                <!-- <h4>Level of Criticality</h4> -->
                <h4>Notifications</h4>
            </div>

            <div class="actFeed notif">
                <div class="feed-content notf">
                    <?php if ($notif_result->num_rows > 0): ?>
                        <?php while ($notif = $notif_result->fetch_assoc()): ?>
                            <div class="feeds">
                                <div class="feeds-date">
                                    <p><?php echo date("m-d-Y", strtotime($notif['created_at'])); ?></p>
                                    <div class="linee"></div>
                                </div>
                                <p class="feed"><?php echo htmlspecialchars($notif['notification_msg']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No new notifications.</p>
                    <?php endif; ?>
                    <span class="clearNotif">Clear All</span>
                </div>
            </div>

        </aside>


    </div>
    <script>
        document.getElementById('bell-icon').addEventListener('click', function () {
            // Send AJAX request to mark 'notify' notifications as "viewed"
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../endpoints/update_notifications.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Change bell icon color to gray if notifications were successfully marked as "viewed"
                        document.getElementById('bell-icon').classList.remove('bell-icon-red');
                        document.getElementById('bell-icon').classList.add('bell-icon-gray');
                    }
                }
            };

            xhr.send("user_id=<?php echo $admin_id; ?>&user_type=admin");
        });


        document.querySelector('.clearNotif').addEventListener('click', function () {
            // Trigger SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "This will clear all your notifications.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, clear all'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to update notifications to 'cleared'
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "../endpoints/clear_notifications.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                Swal.fire(
                                    'Cleared!',
                                    'Your notifications have been cleared.',
                                    'success'
                                );

                                // Optionally, clear the notifications from the UI
                                document.querySelector('.feed-content.notf').innerHTML = '<p>No new notifications.</p>';
                                document.getElementById('bell-icon').classList.remove('bell-icon-red');
                                document.getElementById('bell-icon').classList.add('bell-icon-gray');
                            }
                        }
                    };

                    xhr.send("user_id=<?php echo $admin_id; ?>&user_type=admin");
                }
            });
        });
    </script>
    <!-- ====== scripts ======== -->

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- notif bell popup -->
    <script src="../../assets/src/utils/notifBell.js"></script>

    <!-- graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js"></script>


    <script>
        const ctx = document.getElementById('myChart');

        // PHP data to JavaScript
        const centerNames = <?php echo json_encode(array_column($all_centers, 'name')); ?>;
        const evacueesCounts = <?php echo json_encode(array_column($all_centers, 'evacuees')); ?>;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: centerNames,
                datasets: [{
                    label: 'Total Evacuees',
                    data: evacueesCounts,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        // Get the elements
        const chartShowIcon = document.querySelector('.icons .chartShow');
        const rightSection = document.querySelector('.right-section');
        const ecGraphsContainer = document.querySelector('.ecGraphs-container');
        const feedShow = document.querySelector('.sideProfile .feedShow');


        // Add event listener to the chartShow icon
        chartShowIcon.addEventListener('click', function () {
            // Hide the right-section 
            rightSection.style.display = 'none';

            // Show the ecGraphs-container
            ecGraphsContainer.style.display = 'block';
        });

        feedShow.addEventListener('click', function () {
            ecGraphsContainer.style.display = 'none';

            rightSection.style.display = 'block';
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    if (isset($_SESSION['login_success'])) {
        unset($_SESSION['login_success']);
        ?>
        <script>
            Swal.fire({
                title: 'Login Successful!',
                text: 'Welcome to the dashboard.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
        <?php
    }
    ?>
</body>

</html>