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
validateSession('admin');

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, email, image, proof_image, gender, city, barangay, contact, position 
            FROM admin 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_name = trim($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name'] . ' ' . $admin['extension_name']);
        $barangay = $admin['barangay'];
    } else {
        header("Location: ../../login.php");
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
}
// Fetch the evacuation centers associated with this admin
$sql = "SELECT id, name, location, image, capacity 
FROM evacuation_center 
WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
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
    <link rel="stylesheet" href="../../assets/styles/utils/addEC.css">
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">
    <!-- <link rel="stylesheet" href="../../assets/styles/utils/messagebox.css"> -->

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>
            Swal.fire({
                icon: '{$_SESSION['message_type']}',
                title: '{$_SESSION['message']}'
            });
          </script>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
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
                            <a href="evacuation.php">Barangay <?php echo htmlspecialchars($barangay); ?> Evacuation
                                Centers</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Tetuan Evacuation Centers</a> -->
                        </div>




                        <select class="filter-admin" id="statusFilter" onchange="filterCenters()">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>

                        <button class="addBg-admin">
                            Create
                        </button>

                        <!-- <button class="deleteBg-admin">
                            <i class="fa-solid fa-building-circle-xmark"></i>
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper bgEcWrapper">
                <div class="main-container bgEcList">

                    <style>
                        .bgEc-img {
                            display: block;
                            margin: 0 auto;
                            max-width: 100%;
                        }
                    </style>
                    <!-- <div class="statusHeader">
                        <h3>Barangay Tetuan</h3>
                    </div> -->

                    <div class="main-wrapper bgEcWrapper">
                        <div class="main-container bgEcList">
                            <div class="bgEc-container" id="centerList">
                                <?php if ($centers_result->num_rows > 0): ?>
                                    <?php while ($center = $centers_result->fetch_assoc()):
                                        $center_id = $center['id'];
                                        $capacity = (int) $center['capacity'];

                                        // Count total families in the evacuation center with the updated logic
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

                                        // Count total evacuees and their members with the updated logic
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
                         WHERE evacuees_id IN 
                             (SELECT id 
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
                                        <div class="bgEc-cards" data-status="<?php echo $status_color; ?>"
                                            onclick="window.location.href='viewEC.php?id=<?php echo $center['id']; ?>'">
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


                    <script>
                        function filterCenters() {
                            const filterValue = document.getElementById('statusFilter').value;
                            const centers = document.querySelectorAll('.bgEc-cards');

                            centers.forEach(center => {
                                const status = center.getAttribute('data-status');
                                if (filterValue === 'active') {
                                    // Show only centers that are not grey
                                    if (status === 'grey') {
                                        center.style.display = 'none';
                                    } else {
                                        center.style.display = 'block';
                                    }
                                } else if (filterValue === 'inactive') {
                                    // Show only centers that are grey
                                    if (status === 'grey') {
                                        center.style.display = 'block';
                                    } else {
                                        center.style.display = 'none';
                                    }
                                }
                            });
                        }

                        // Initial filter on page load
                        document.addEventListener('DOMContentLoaded', filterCenters);
                    </script>


                    <!-- add evacuaton form -->
                    <div class="addEC-container">
                        <div class="addEC-form">
                            <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>

                            <h3 class="addEC-header">
                                Create Evacuation Center
                            </h3>
                            <!-- Form -->
                            <form id="createEvacuationForm" action="../endpoints/create_evacuation_center.php"
                                method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>">
                                <input type="hidden" name="barangay" value="<?php echo htmlspecialchars($barangay); ?>">

                                <div class="addEC-input">
                                    <label for="evacuationCenterName">Name of Evacuation Center</label>
                                    <input type="text" id="evacuationCenterName" class="evacuation-center-name"
                                        name="evacuationCenterName" required>
                                </div>

                                <div class="addEC-input">
                                    <label for="location">Street</label>
                                    <input type="text" id="location" class="evacuation-center-location" name="location"
                                        required>
                                </div>
                                <div class="addEC-input">
                                    <label for="barangay">Barangay</label>
                                    <input type="text" id="barangay" class="evacuation-center-barangay" name="barangay"
                                        value=" <?php echo htmlspecialchars($barangay); ?>" required>
                                </div>


                                <div class="addEC-input">
                                    <label for="capacity">Capacity (per family)</label>
                                    <input type="number" id="capacity" class="evacuation-center-capacity"
                                        name="capacity" required>
                                </div>

                                <div class="addEC-input">
                                    <label for="fileInput">Add photo</label>
                                    <input id="fileInput" type="file" class="evacuation-center-photo noBorder"
                                        name="photo" required>
                                </div>

                                <div class="addEC-input">
                                    <button type="button" class="mainBtn" id="create"
                                        onclick="confirmSubmission()">Create</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </main>

    </div>

    <script>
        function confirmSubmission() {
            // Check if all required fields are filled
            const name = document.getElementById("evacuationCenterName").value;
            const location = document.getElementById("location").value;
            const capacity = document.getElementById("capacity").value;

            if (name && location && capacity) {
                // If all fields are filled, show the confirmation alert
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to create a new evacuation center.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if confirmed
                        document.getElementById("createEvacuationForm").submit();
                    }
                });
            } else {
                // Alert if any required fields are missing
                Swal.fire({
                    title: 'Incomplete form',
                    text: "Please fill in all required fields.",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                });
            }
        }
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <!-- pop up add form -->
    <script src="../../assets/src/utils/addEc-popup.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- sweetalert popup messagebox add form-->








</body>

</html>