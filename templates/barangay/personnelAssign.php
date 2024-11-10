<?php
session_start();
include("../../connection/conn.php");

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
    <link rel="stylesheet" href="../../assets/styles/utils/viewEC.css">
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

<body>

    <div class="container">

        <aside class="left-section">
            <special-logo></special-logo>

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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Assign</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">

                    <special-personnel></special-personnel>

                    <div class="bgEc-container" style="margin-top: 1em;">
                        <?php if ($centers_result->num_rows > 0): ?>
                            <?php while ($center = $centers_result->fetch_assoc()): ?>
                                <div class="bgEc-cards"
                                    onclick="window.location.href='assignPage.php?id=<?php echo $center['id']; ?>'">
                                    <div class="bgEc-status red"></div>
                                    <img src="<?php echo !empty($center['image']) ? htmlspecialchars($center['image']) : '../../assets/img/evacuation-default.svg'; ?>"
                                        alt="" class="bgEc-img">

                                    <ul class="bgEc-info">
                                        <li><strong><?php echo htmlspecialchars($center['name']); ?></strong></li>
                                        <li>Location: <?php echo htmlspecialchars($center['location']); ?></li>
                                        <li>Capacity: <?php echo htmlspecialchars($center['capacity']); ?> Families</li>
                                        <li>Total Families: 50</li>
                                        <li>Total Evacuees: 70</li>
                                        <!-- <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 85/100</li>
                                <li>Total Evacuees: 70</li> -->
                                    </ul>
                                </div>
                            <?php endwhile; ?>
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

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import navbar -->
    <script src="../../includes/personnelpageNav.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>