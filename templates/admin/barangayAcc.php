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


if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

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

// Fetch all admins with role 'admin'
$admin_query = "SELECT id, first_name, middle_name, last_name, extension_name, email, image, barangay, contact, role, status 
                FROM admin 
                WHERE role = 'admin' AND (verification_code IS NULL OR verification_code = '')";
$admin_stmt = $conn->prepare($admin_query);
$admin_stmt->execute();
$admin_result = $admin_stmt->get_result();
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
    <link rel="stylesheet" href="../../assets/styles/utils/barangay.css">

    <style>
        .status.active {
            background-color: var(--clr-green);
            color: var(--clr-white);
        }
    </style>


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
                            <a href="barangayAcc.php">Accounts</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create new barangay admin account</a> -->
                        </div>

                        <!-- <button class="profile-btn" id="profile-btn">
                            <i class="fa-solid fa-user-plus"></i>
                            <img src="/assets/img/hero.jpg">
                        </button> -->
                        <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </div>
                </div>
            </header>

            <div class="table-wrapper">

                <div class="table-container">
                    <section class="tblheader">




                        <!-- <div class="filter-popup">
                            <i class="fa-solid fa-filter"></i>
                        </div> -->

                        <div class="filter-popup">
                            <label for="modal-toggle" class="modal-button">
                                <i class="fa-solid fa-filter"></i>
                            </label>
                            <input type="checkbox" name="" id="modal-toggle" class="modal-toggle">

                            <!-- the modal or filter popup-->
                            <div class="modal">
                                <div class="modal-content">
                                    <div class="filter-option">
                                        <div class="option-content">
                                            <input type="checkbox" id="filterAll" checked>
                                            <label for="filterAll">All</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" id="filterActive">
                                            <label for="filterActive">Active</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" id="filterInactive">
                                            <label for="filterInactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="input_group">
                            <input type="search" id="searchInput" placeholder="Search...">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                    </section>

                    <section class="tblbody">
                        <table id="mainTable">
                            <thead>

                                <tr>
                                    <!-- <th>Id</th> -->
                                    <th>Barangay</th>
                                    <th>Full Name</th>
                                    <th>Contact Info</th>
                                    <th>Email</th>
                                    <th style="text-align: center;">Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($admin = $admin_result->fetch_assoc()): ?>
                                    <tr data-status="<?php echo htmlspecialchars($admin['status']); ?>"
                                        data-barangay="<?php echo htmlspecialchars($admin['barangay']); ?>"
                                        data-name="<?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name']); ?>"
                                        data-email="<?php echo htmlspecialchars($admin['email']); ?>"
                                        onclick="window.location.href='viewProfile.php?id=<?php echo urlencode($admin['id']); ?>'">
                                        <td><?php echo htmlspecialchars($admin['barangay']); ?></td>
                                        <td>
                                            <div class="relative">
                                                <img src="<?php echo htmlspecialchars($admin['image'] ?: '../../assets/img/undraw_male_avatar_g98d.svg'); ?>"
                                                    alt="Profile Image">
                                                <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($admin['contact']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                        <td>
                                            <p class="status role"><?php echo ucfirst(htmlspecialchars($admin['role'])); ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p
                                                class="status role <?php echo $admin['status'] == 'active' ? 'active' : 'inactive'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($admin['status'])); ?>
                                            </p>
                                        </td>
                                        <td><a href="viewProfile.php?id=<?php echo urlencode($admin['id']); ?>"
                                                class="view-action">View</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </section>

                    <div class="no-match-message">No matching data found</div>
                </div>
            </div>


        </main>

        <!-- <aside class="right-section" id="right-section">
            <div class="top">
                <i class="fa-regular fa-bell"></i>
                <div class="profile">
                    <div class="left">
                        <img src="/assets/img/hero.jpg">
                        <div class="user">
                            <h5>Mark Larenz</h5>
                            <a href="#">View</a>
                        </div>
                    </div>
                    <button class="close-profile" id="profile-btn-close">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="separator" id="first">
                <h4>Level of Criticality</h4>
            </div>

            <div class="announce">
                <div class="title">
                    <div class="line"></div>
                    <h5>High</h5>
                </div>
                <div class="title">
                    <div class="line"></div>
                    <h5>Moderate</h5>
                </div>
                <div class="title">
                    <div class="line"></div>
                    <h5>Low</h5>
                </div>   
            </div>

            <div class="separator">
                <h4>Updates</h4>
            </div>                              

            <div class="stats">
                <div class="item">
                    <div class="top">
                        <p>Fire Incident at Tetuan Alvarez Drive</p>
                    </div>
                    
                    <div class="bottom">
                        <div class="line moderate"></div>
                        <h3>Moderate</h3>
                    </div>
                </div>
                <div class="item">
                    <div class="top">
                        <p>Flood at San Jose Gusu Baliwasan</p>
                    </div>
                    <div class="bottom">
                        <div class="line high"></div>
                        <h3>High</h3>
                    </div>
                </div>
                <div class="item">
                    <div class="top">
                        <p>Flood at Guiwan</p>
                    </div>
                    <div class="bottom">
                        <div class="line low"></div>
                        <h3>Low</h3>
                    </div>
                </div>
                <div class="item">
                    <div class="top">
                        <p>Flood at Pasonanca paso pasopaso paso paos paso sadfasd </p>
                    </div>
                    <div class="bottom">
                        <div class="line moderate"></div>
                        <h3>Moderate</h3>
                    </div>
                </div>
            </div>

            
        </aside> -->





    </div>
    <script>
        document.getElementById('filterAll').addEventListener('change', () => {
            if (document.getElementById('filterAll').checked) {
                resetFilters();
            }
        });
        document.getElementById('filterActive').addEventListener('change', updateFilters);
        document.getElementById('filterInactive').addEventListener('change', updateFilters);

        function resetFilters() {
            document.getElementById('filterActive').checked = false;
            document.getElementById('filterInactive').checked = false;

            const rows = document.querySelectorAll('#mainTable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        function updateFilters() {
            if (document.getElementById('filterActive').checked || document.getElementById('filterInactive').checked) {
                document.getElementById('filterAll').checked = false;
            }

            const showActive = document.getElementById('filterActive').checked;
            const showInactive = document.getElementById('filterInactive').checked;

            if (!showActive && !showInactive) {
                document.getElementById('filterAll').checked = true;
                resetFilters();
                return;
            }

            const rows = document.querySelectorAll('#mainTable tbody tr');
            rows.forEach(row => {
                const status = row.getAttribute('data-status').toLowerCase();
                if ((status === 'active' && showActive) || (status === 'inactive' && showInactive)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search function
        document.getElementById('searchInput').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#mainTable tbody tr');

            rows.forEach(row => {
                const barangay = row.getAttribute('data-barangay').toLowerCase();
                const name = row.getAttribute('data-name').toLowerCase();
                const email = row.getAttribute('data-email').toLowerCase();

                if (barangay.includes(filter) || name.includes(filter) || email.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- filter search -->
    <script src="../../assets/src/admin/accountSearch.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>