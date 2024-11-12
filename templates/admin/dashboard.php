<?php
session_start();
include("../../connection/conn.php");

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
$admin_image = !empty($admin_image) ? $admin_image : "../../assets/img/admin.png";

// Query to get the total number of admins, excluding those with a verification code
$count_sql = "SELECT COUNT(*) AS total_admins FROM admin WHERE role = 'admin' AND verification_code IS NULL OR verification_code = '' ";
$count_result = $conn->query($count_sql);
$total_admins = ($count_result->num_rows > 0) ? $count_result->fetch_assoc()['total_admins'] : 0;

// Query to get the total number of evacuation centers
$evacuation_center_sql = "SELECT COUNT(*) AS total_centers FROM evacuation_center";
$evacuation_center_result = $conn->query($evacuation_center_sql);
$total_centers = ($evacuation_center_result->num_rows > 0) ? $evacuation_center_result->fetch_assoc()['total_centers'] : 0;

// Query to get the total number of evacuees and their members
$evacuees_and_members_sql = "
    SELECT 
        (SELECT COUNT(*) FROM evacuees) +
        (SELECT COUNT(*) FROM members) AS total_evacuees_with_members
";
$evacuees_and_members_result = $conn->query($evacuees_and_members_sql);
$total_evacuees_with_members = ($evacuees_and_members_result->num_rows > 0) ? $evacuees_and_members_result->fetch_assoc()['total_evacuees_with_members'] : 0;

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

// Retrieve notifications that are not cleared
$notif_query = "SELECT * FROM notifications WHERE logged_in_id = ? AND user_type = 'admin' AND status != 'cleared'";
$notif_stmt = $conn->prepare($notif_query);
$notif_stmt->bind_param("i", $admin_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result();

$latest_admins_sql = "
    SELECT 
        a.id AS admin_id, 
        a.first_name, 
        a.middle_name, 
        a.last_name, 
        a.extension_name, 
        a.barangay, 
        COUNT(e.id) AS evacuation_center_count
    FROM admin a
    INNER JOIN evacuation_center e ON a.id = e.admin_id
    WHERE a.role = 'admin'
    GROUP BY a.id
    HAVING evacuation_center_count > 0
    ORDER BY a.id DESC
    LIMIT 4
";
$latest_admins_result = $conn->query($latest_admins_sql);

$latest_admins = [];
if ($latest_admins_result->num_rows > 0) {
    while ($row = $latest_admins_result->fetch_assoc()) {
        $latest_admins[] = $row;
    }
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
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard-mobile.css">


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

            <!-- <div class="logout">
                <div class="link">
                    <a href="../../login.php">
                        <p>Click to <b>Logout</b></p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
                
            </div>
            <a class="logout logout-icon" href="../../login.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a> -->

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
                            <h5>Admin Accounts</h5>
                            <p>Total: <?php echo $total_admins; ?></p>
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
                <div class="item spanning">
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuation Centers</h5>
                            <p>Total: <?php echo $total_centers; ?></p>
                        </div>
                    </div>
                    <i class="fa-solid fa-person-shelter"></i>
                </div>

            </div>

            <div class="separator">
                <div class="info">
                    <h3>Barangay Status Overview</h3>
                    <a href="barangayStatus.php" style="text-decoration: underline;">View All</a>
                </div>
                <!-- <input type="date" value="2024-11-09"> -->
            </div>

            <div class="ecenter">
                <?php foreach ($latest_admins as $admin): ?>
                    <div class="item">
                        <div class="left">
                            <div class="icon">
                                <i class="fa-solid fa-person-shelter"></i>
                            </div>
                            <div class="details">
                                <h5><?php echo htmlspecialchars($admin['barangay']); ?></h5>
                                <p>
                                    <?php echo htmlspecialchars($admin['evacuation_center_count']); ?>
                                    Evacuation Center<?php echo $admin['evacuation_center_count'] > 1 ? 's' : ''; ?>
                                </p>
                            </div>
                        </div>
                        <a
                            href="barangayEC.php?barangay=<?php echo urlencode($admin['barangay']); ?>&admin_id=<?php echo $admin['admin_id']; ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        </main>

        <aside class="right-section">
            <div class="top">
                <!-- <i class="fa-regular fa-bell" id="bell-icon"></i>
                

                <i class="fa-solid fa-arrow-left" id="act-icon" style="display: block;"></i> -->

                <div class="icons">
                    <!-- <i class="fa-regular fa-bell" id="bell-icon"></i> -->
                </div>

                <!-- act feed show -->

                <div class="profile">
                    <div class="left">
                        <img src="<?php echo htmlspecialchars($admin_image); ?>" alt="Profile Image">
                        <div class="user">
                            <h5><?php echo htmlspecialchars($admin_name); ?></h5>
                            <a href="myProfileAdmin.php" style="text-decoration: underline;">View</a>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right" onclick="window.location.href='myProfileAdmin.php'"></i>
                </div>
            </div>



            <div class="separator notifHeader" id="first">
                <!-- <h4>Level of Criticality</h4> -->
                <h4 style="display: block;">Notifications</h4>
            </div>

            <div class="actFeed notif" style="display: block;">
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
                        <span class="clearNotif">Clear All</span>
                    <?php else: ?>
                        <p>No new notifications.</p>
                    <?php endif; ?>
                </div>
            </div>

        </aside>


    </div>

    <script>

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

    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logot -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <!-- notif bell popup -->
    <script src="../../assets/src/utils/adminNotif.js"></script>

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