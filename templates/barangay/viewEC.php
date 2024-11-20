<?php
session_start();
require_once '../../connection/conn.php';
require_once '../../connection/auth.php';
validateSession('admin');
// Get the evacuation center ID from the URL
$evacuationCenterId = $_GET['id'];

// Query to get evacuation center details along with admin's barangay
$sqlEvacuationCenter = "
    SELECT ec.*, a.barangay AS admin_barangay
    FROM evacuation_center ec
    JOIN admin a ON ec.admin_id = a.id
    WHERE ec.id = ?";
$stmtEvacuationCenter = $conn->prepare($sqlEvacuationCenter);
$stmtEvacuationCenter->bind_param("i", $evacuationCenterId);
$stmtEvacuationCenter->execute();
$resultEvacuationCenter = $stmtEvacuationCenter->get_result();

if ($resultEvacuationCenter->num_rows > 0) {
    $evacuationCenter = $resultEvacuationCenter->fetch_assoc();

    // Set image source, using default if none is provided
    $imageSrc = !empty($evacuationCenter['image']) ? "../../assets/img/" . $evacuationCenter['image'] : "../../assets/img/ecDeaultPhoto.svg";

    // Query to count total families (evacuees) for this evacuation center
    $sqlTotalFamilies = "SELECT COUNT(*) as total_families FROM evacuees WHERE evacuation_center_id = ?";
    $stmtTotalFamilies = $conn->prepare($sqlTotalFamilies);
    $stmtTotalFamilies->bind_param("i", $evacuationCenterId);
    $stmtTotalFamilies->execute();
    $resultTotalFamilies = $stmtTotalFamilies->get_result();
    $totalFamilies = $resultTotalFamilies->fetch_assoc()['total_families'];

    // Query to count total evacuees and members for this evacuation center
    $sqlTotalEvacuees = "
        SELECT 
            (SELECT COUNT(*) FROM evacuees WHERE evacuation_center_id = ?) + 
            (SELECT COUNT(*) FROM members WHERE evacuees_id IN 
                (SELECT id FROM evacuees WHERE evacuation_center_id = ?)
            ) AS total_evacuees";
    $stmtTotalEvacuees = $conn->prepare($sqlTotalEvacuees);
    $stmtTotalEvacuees->bind_param("ii", $evacuationCenterId, $evacuationCenterId);
    $stmtTotalEvacuees->execute();
    $resultTotalEvacuees = $stmtTotalEvacuees->get_result();
    $totalEvacuees = $resultTotalEvacuees->fetch_assoc()['total_evacuees'];

    // Calculate occupancy
    $occupancy = $totalFamilies . "/" . $evacuationCenter['capacity'];

    // Determine status color based on occupancy
    if ($totalFamilies == 0) {
        $statusColor = "grey";
    } else {
        $occupancyPercentage = ($totalFamilies / $evacuationCenter['capacity']) * 100;
        $statusColor = $occupancyPercentage < 70 ? "green" : ($occupancyPercentage < 100 ? "yellow" : "red");
    }
} else {
    echo "Evacuation center not found.";
    exit;
}

// Array to store evacuee count per month
$monthlyEvacuees = array_fill(1, 12, 0); // Fill with 0 for each month initially

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch the current year dynamically based on today's date
$currentYear = date("Y");

// Fetch the selected year from the GET parameter or default to the current year
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;

// Generate years for the dropdown (past 5 years + current year)
$pastYears = range($currentYear - 5, $currentYear);


// Query to count evacuees per month filtered by year and their members
$sqlMonthlyEvacuees = "
    SELECT 
        MONTH(e.date) as month, 
        COUNT(e.id) as evacuees_count,
        (SELECT COUNT(m.id) FROM members m WHERE m.evacuees_id = e.id) as members_count
    FROM evacuees e
    WHERE e.evacuation_center_id = ? AND YEAR(e.date) = ?
    GROUP BY MONTH(e.date)";
$stmtMonthlyEvacuees = $conn->prepare($sqlMonthlyEvacuees);
$stmtMonthlyEvacuees->bind_param("ii", $evacuationCenterId, $selectedYear);
$stmtMonthlyEvacuees->execute();
$resultMonthlyEvacuees = $stmtMonthlyEvacuees->get_result();

// Populate evacuee count per month
$monthlyEvacuees = array_fill(1, 12, 0); // Fill with 0 for each month initially
while ($row = $resultMonthlyEvacuees->fetch_assoc()) {
    $month = $row['month'];
    $evacueesCount = $row['evacuees_count'];
    $membersCount = $row['members_count'];
    $monthlyEvacuees[$month] = $evacueesCount + $membersCount; // Total evacuees for the month
}

// Convert PHP array to JSON for JavaScript
$monthlyEvacueesJson = json_encode(array_values($monthlyEvacuees));

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
    <link rel="stylesheet" href="../../assets/styles/utils/viewEC.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .year-select {
        padding: 10px;
        font-size: 12px;
        color: #0056b3;
        background-color: #e8f4fc;
        border: 1px solid #0056b3;
        border-radius: 5px;
        cursor: pointer;
        outline: none;
        margin-bottom: 20px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .year-select:hover {
        background-color: #cdeafc;
    }

    .year-select option {
        padding: 10px;
        background-color: #ffffff;
        color: #0056b3;
    }
</style>

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
                            <a href="#"><?php echo $evacuationCenter['name']; ?></a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Overview</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                        <button class="addBg-admin"
                            onclick="window.location.href='evacueesForm.php?id=<?php echo $evacuationCenterId; ?>'">
                            Admit
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>

                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-navbar></special-navbar>

                    <div class="ecView-content">
                        <div class="description">
                            <img src="<?php echo $imageSrc; ?>" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <div class="bgEc-status <?php echo $statusColor; ?>"></div>
                                <li><strong><?php echo $evacuationCenter['name']; ?></strong></li>
                                <li>Barangay: <?php echo $evacuationCenter['admin_barangay']; ?></li>
                                <li>Location: <?php echo $evacuationCenter['location']; ?></li>
                                <li>Capacity: <?php echo $evacuationCenter['capacity']; ?> Families</li>
                                <li>Total Families: <?php echo $totalFamilies; ?></li>
                                <li>Total Evacuees: <?php echo $totalEvacuees; ?></li>
                                <li>Occupied: <?php echo $occupancy; ?></li>


                                <div class="ecenter-cta">
                                    <label for="ecenter-toggle">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </label>
                                    <input type="checkbox" id="ecenter-toggle" class="ecenter-toggle">

                                    <ul class="ecenterOptions">
                                        <li class="ecenterEdit">Edit</li>
                                        <li class="ecenterDelete">Delete</li>
                                    </ul>

                                </div>
                            </ul>
                        </div>

                        <div class="chart">
                            <select id="yearFilter" class="year-select">
                                <?php foreach ($pastYears as $year): ?>
                                    <option value="<?php echo $year; ?>" <?php echo $year == $selectedYear ? 'selected' : ''; ?>>
                                        <?php echo $year; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>



                    <!-- edit evacuation center -->
                    <div class="editEC-container">
                        <div class="editEC-form">
                            <button class="closeEdit"><i class="fa-solid fa-xmark"></i></button>

                            <h3 class="editEC-header">
                                Edit Evacuation Center
                            </h3>

                            <form action="../endpoints/edit_evacuation_center.php" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="evacuation_id" value="<?php echo $evacuationCenterId; ?>">

                                <input type="hidden" name="barangay"
                                    value="<?php echo $evacuationCenter['admin_barangay']; ?>">

                                <div class="editEC-input">
                                    <label for="">Name of Evacuation Center</label>
                                    <input type="text" name="name" class="name"
                                        value="<?php echo $evacuationCenter['name']; ?>">
                                </div>

                                <div class="editEC-input">
                                    <label for="">Location</label>
                                    <input type="text" name="location" class="location"
                                        value="<?php echo $evacuationCenter['location']; ?>">
                                </div>

                                <div class="editEC-input">
                                    <label for="">Capacity (per family)</label>
                                    <input type="number" name="capacity" class="capacity"
                                        value="<?php echo $evacuationCenter['capacity']; ?>">
                                </div>

                                <div class="editEC-input">
                                    <label for="">Change photo</label>
                                    <input id="fileInput" name="image" type="file" class="noBorder" />
                                </div>

                                <div class="editEC-input">
                                    <button type="button" class="mainBtn" id="save">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>



                </div>
            </div>
        </main>

    </div>


    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- popup edit ecenter form -->
    <script src="../../assets/src/utils/editEc-popup.js"></script>

    <!-- graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js"></script>


    <!-- sweetalert edit form -->
    <script>
        $('#save').on('click', function () {
            Swal.fire({
                title: "Save Changes?",
                text: "",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('form').submit();
                }
            });
        });

        //  sweetalert delete 
        $('.ecenterDelete').on('click', function () {
            Swal.fire({
                title: "Delete Evacuation Center?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it",
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const evacuationId = "<?php echo $evacuationCenterId; ?>";
                    window.location.href = `../endpoints/delete_evacuation_center.php?id=${evacuationId}`;
                }
            });
        });

    </script>


    <script>
        document.getElementById('yearFilter').addEventListener('change', function () {
            const selectedYear = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('year', selectedYear); // Update the year parameter in the URL
            window.location.search = urlParams.toString(); // Reload the page with the selected year
        });


        // Chart rendering logic
        const monthlyEvacueesData = <?php echo $monthlyEvacueesJson; ?>;

        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Evacuees',
                    data: monthlyEvacueesData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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


</body>

</html>