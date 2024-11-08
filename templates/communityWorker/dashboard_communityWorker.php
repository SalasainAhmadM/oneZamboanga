<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $worker_id = $_SESSION['user_id'];

    // Fetch the worker's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, email, image FROM worker WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();
        $first_name = $worker['first_name'];
        $middle_name = $worker['middle_name'];
        $last_name = $worker['last_name'];
        $extension_name = $worker['extension_name'];
        $email = $worker['email'];
        $worker_image = $worker['image'];

        $worker_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $extension_name);
    } else {
        $first_name = $middle_name = $last_name = $extension_name = $email = '';
    }
} else {
    header("Location: ../../login.php");
    exit;
}
$worker_image = !empty($worker['image']) ? $worker['image'] : "../../assets/img/undraw_male_avatar_g98d.svg";
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
                            <h5>Evacuation Centers</h5>
                            <p>Total: 2</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="item">
                    <div class="progress">
                        <div class="info">
                            <h5>Evacuees</h5>
                            <p>Total: 100</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-children"></i>
                </div>

                <div class="item spanning"> <!--spanning-->
                    <div class="progress">
                        <div class="info">
                            <h5>Assigned Evacuation Centers</h5>
                            <p>Total: 1</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-person-shelter"></i>
                </div>

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
                    <a href="evacuationCenter.php" style="text-decoration: underline;">View All</a>
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
                            <h5>Barangay Hall</h5>
                            <p>53 Evacuees</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="assignedEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Tetuan Central School</h5>
                            <p>22 Evacuees</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="assignedEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Barangay Hall</h5>
                            <p>53 Evacuees</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="assignedEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
                <div class="item">
                    <div class="left">
                        <div class="icon">
                            <i class="fa-solid fa-person-shelter"></i>
                        </div>
                        <div class="details">
                            <h5>Tetuan Central School</h5>
                            <p>23 Evacuees</p>
                        </div>
                    </div>
                    <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
                    <a href="assignedEC.php"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
        </main>

        <div class="ecGraphs-container">

            <div class="ecGraph-content">
                <div class="profileHeader">


                    <h2>Demographics</h2>
                    <div class="sideProfile">
                        <i class="fa-solid fa-bars feedShow"></i>
                        <!-- <img src="../../assets/img/hero.jpg" alt="">  -->



                    </div>

                    <!-- <div class="sideProfile">
                        <i class="fa-solid fa-bars feedShow"></i>
                        <h2>Demographics</h2>
                    </div>
                    <div class="profilecontain">
                        <img src="../../assets/img/hero.jpg" alt=""> 
                    </div> -->
                </div>


                <div class="ecGraph">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <!-- <div class="sideProfile">
                <i class="fa-solid fa-bars feedShow"></i>

                <img src="../../assets/img/hero.jpg" alt=""> 
                
                
            </div> -->
        </div>

        <aside class="right-section feed">
            <div class="top">
                <div class="icons">
                    <i class="fa-regular fa-bell margin" id="bell-icon"></i>

                    <i class="fa-solid fa-chart-pie chartShow"></i>
                </div>

                <!-- act feed show -->
                <i class="fa-solid fa-arrow-left" id="act-icon" style="display: none;"></i>


                <!-- <div class="notif">
                    <h4>Notifications</h4>
                    <div class="notif-box">
                        <h5 class="notif-title">Notif Title</h5>
                        <p class="notif-info">Description ..  Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit repudiandae accusantium nam. Similique vel iste rerum, ratione placeat et suscipit accusantium natus quibusdam a molestiae amet tempore sapiente, necessitatibus sunt.</p>
                    </div>

                    <div class="notif-box">
                        <h5 class="notif-title">Notif Title</h5>
                        <p class="notif-info">Description ..  Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit repudiandae accusantium nam. Similique vel iste rerum, ratione placeat et suscipit accusantium natus quibusdam a molestiae amet tempore sapiente, necessitatibus sunt.</p>
                    </div>

                    <div class="notif-clear">
                        <button>Clear All</button>
                    </div>
                </div> -->
                <div class="profile">
                    <div class="left">
                        <img src="<?php echo htmlspecialchars($worker_image); ?>" alt="Profile Image">
                        <div class="user">
                            <h5><?php echo htmlspecialchars($worker_name); ?></h5>
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
                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distributed to the family</p>
                    </div>

                    <div class="feeds">

                        <div class="feeds-date">
                            <p>11-15-2024</p>
                            <div class="linee"></div>
                        </div>

                        <p class="feed">50pcs food distribsdf dfad dsadd dsasdf dfdfduted to the familsfdy</p>
                    </div>

                </div>
            </div>

            <div class="separator notifHeader" id="first">
                <!-- <h4>Level of Criticality</h4> -->
                <h4>Notifications</h4>
            </div>

            <div class="actFeed notif">
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

    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

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

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Barangay Hall', 'City High Covered Court', 'Tetuan Central School', 'Children', 'ICAS'],
                datasets: [{
                    label: 'Total Evacuees',
                    data: [8, 46, 31, 10, 4],

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