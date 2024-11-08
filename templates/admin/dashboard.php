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
                            <p>Total: 20</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-users"></i>
                </div>

                <div class="item">
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuees</h5>
                            <p>Total: 35</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-children"></i>
                </div>
                <div class="item spanning">
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuation Centers</h5>
                            <p>Total: 4</p>
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
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Barangay Tetuan</h5>
                            <p>5 Evacuation Centers</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="barangayEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Barangay Tugbungan</h5>
                            <p>3 Evacuation Centers</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="barangayEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Barangay Putik</h5>
                            <p>2 Evacuation Centers</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="barangayEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Barangay Guiwan</h5>
                            <p>8 Evacuation Centers</p>
                        </div>
                    </div>
                    <a href="barangayEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
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
                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">Notification info here</p>

                    </div>
                    <span class="clearNotif">Clear All</span>



                </div>
            </div>




        </aside>


    </div>



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