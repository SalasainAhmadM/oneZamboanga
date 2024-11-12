<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, email
    FROM admin 
    WHERE id = ?";
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

        $admin_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $extension_name);

    } else {
        $first_name = $middle_name = $last_name = $extension_name = $email = '';
    }
} else {
    header("Location: ../../login.php");
    exit;
}

// Fetch evacuees under the logged-in admin_id with member count
$evacuees_sql = "SELECT e.id, e.first_name, e.middle_name, e.last_name, e.contact, e.barangay, e.disaster_type, e.date,
                 (SELECT COUNT(*) FROM members m WHERE m.evacuees_id = e.id) AS member_count
                 FROM evacuees e WHERE e.admin_id = ?";
$evacuees_stmt = $conn->prepare($evacuees_sql);
$evacuees_stmt->bind_param("i", $admin_id);
$evacuees_stmt->execute();
$evacuees_result = $evacuees_stmt->get_result();
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
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css">


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
                            <a href="viewEC.php">Request admission transfer chu chu</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <!-- <button class="addBg-admin" onclick="window.location.href='evacueesForm.php'">
                            Admit
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <!-- <special-navbar></special-navbar> -->



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
                                        <!-- <label for="modal-toggle" class="close">
                                            <i class="fa-solid fa-xmark"></i>
                                        </label> -->
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="tetuan">
                                                <label for="tetuan">Tetuan</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="tugbungan">
                                                <label for="tugbungan">Tugbungan</label>
                                            </div>



                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="input_group">
                                <input type="search" placeholder="Search...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>

                                    <tr>
                                        <th>Family Head</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Number of members</th>
                                        <th style="text-align: center;">Barangay</th>
                                        <th style="text-align: center;">Date</th>
                                        <th>Calamity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php while ($row = $evacuees_result->fetch_assoc()): ?>
                                        <tr onclick="window.location.href='viewEvacueesDetails.php?id=<?= $row['id'] ?>'">
                                            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['contact']) ?></td>
                                            <td class="ecMembers" style="text-align: center;">
                                                <?= $row['member_count'] ?>

                                                <ul class="viewMembers" style="text-align: left;">
                                                    <?php
                                                    // Fetch the individual members for this evacuee
                                                    $members_sql = "SELECT first_name, last_name FROM members WHERE evacuees_id = ?";
                                                    $members_stmt = $conn->prepare($members_sql);
                                                    $members_stmt->bind_param("i", $row['id']);
                                                    $members_stmt->execute();
                                                    $members_result = $members_stmt->get_result();

                                                    while ($member = $members_result->fetch_assoc()): ?>
                                                        <li><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                                        </li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </td>
                                            <td style="text-align: center;"><?= htmlspecialchars($row['barangay']) ?></td>
                                            <td style="text-align: center;"><?= htmlspecialchars($row['date']) ?></td>
                                            <td><?= htmlspecialchars($row['disaster_type']) ?></td>
                                            <td style="text-align: center;">
                                                <a href="viewEvacueesDetails.php?id=<?= $row['id'] ?>"
                                                    class="view-action">View</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </section>

                        <div class="no-match-message">No matching data found</div>
                    </div>

                </div>
            </div>
        </main>

    </div>


    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>