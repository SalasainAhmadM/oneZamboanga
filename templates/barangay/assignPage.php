<?php
session_start();
include("../../connection/conn.php");

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $center_id = $_GET['id'];
    $admin_id = $_SESSION['user_id'];

    // Fetch details of the selected evacuation center
    $sql = "SELECT name, location, capacity, image FROM evacuation_center WHERE id = ? AND admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $center_id, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $center = $result->fetch_assoc();
    } else {
        echo "No details found for this evacuation center.";
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
}

// Query to fetch workers under the logged-in admin
$worker_query = "SELECT id, first_name, middle_name, last_name, extension_name, email, contact, position, image, status 
 FROM worker 
 WHERE admin_id = ? AND (verification_code IS NULL OR verification_code = '')";
$worker_stmt = $conn->prepare($worker_query);
$worker_stmt->bind_param("i", $admin_id);
$worker_stmt->execute();
$worker_result = $worker_stmt->get_result();

// Fetch unique positions for filter options
$position_query = "SELECT DISTINCT position FROM worker WHERE admin_id = ? AND (verification_code IS NULL OR verification_code = '')";
$position_stmt = $conn->prepare($position_query);
$position_stmt->bind_param("i", $admin_id);
$position_stmt->execute();
$position_result = $position_stmt->get_result();
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
    <!-- <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css"> -->
    <!-- <link rel="stylesheet" href="../../assets/styles/utils/resources.css"> -->
    <link rel="stylesheet" href="../../assets/styles/utils/viewSupply.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                <div class="main-container supply"> <!--overview-->

                    <special-personnel></special-personnel>

                    <div class="viewSupply-container" style="box-shadow: none;">

                        <div class="supplyTop itemDonate">
                            <img src="<?php echo !empty($center['image']) ? htmlspecialchars($center['image']) : '../../assets/img/evacuation-default.svg'; ?>"
                                alt="Evacuation Center Image">
                            <ul class="supplyDetails">
                                <li>Evacuation Center: <?php echo htmlspecialchars($center['name']); ?></li>
                                <li>Location: <?php echo htmlspecialchars($center['location']); ?></li>
                                <li>Capacity: <?php echo htmlspecialchars($center['capacity']); ?> Families</li>
                                <li>Total Families: 30</li>
                                <li>Total Evacuees: 120</li>
                            </ul>
                        </div>


                        <div class="supplyBot">
                            <div class="supplyTable-container supplyDonate">
                                <form action="" method="post" id="assignWorkerForm">
                                    <input type="hidden" name="evacuation_center_id" value="<?php echo $center_id; ?>">
                                    <table class="distributedTable donate">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact #</th>
                                                <th style="text-align: center;">Position</th>
                                                <th style="text-align: center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Query to fetch all workers with assignment status to the current evacuation center
                                            $assigned_workers = []; // To store assigned worker IDs
                                            $assigned_result = $conn->query("SELECT worker_id FROM assigned_worker WHERE evacuation_center_id = $center_id AND status = 'assigned'");

                                            while ($assigned = $assigned_result->fetch_assoc()) {
                                                $assigned_workers[] = $assigned['worker_id'];
                                            }

                                            // Fetch all workers
                                            while ($worker = $worker_result->fetch_assoc()):
                                                $isAssigned = in_array($worker['id'], $assigned_workers);
                                                ?>
                                                <tr onclick="toggleCheckbox(this)">
                                                    <td class="selectName" style="text-align: center;">
                                                        <input type="checkbox" name="selected_workers[]"
                                                            value="<?php echo $worker['id']; ?>" <?php echo $isAssigned ? 'checked' : ''; ?>>
                                                        <?php echo htmlspecialchars($worker['first_name'] . ' ' . $worker['middle_name'] . ' ' . $worker['last_name'] . ' ' . $worker['extension_name']); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($worker['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($worker['contact']); ?></td>
                                                    <td style="text-align: center;">
                                                        <?php echo htmlspecialchars($worker['position']); ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php echo $isAssigned ? 'Assigned' : 'Unassigned'; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                    <div class="distributeBtn-container">
                                        <button type="button" class="selectBtn" id="toggleSelectBtn"
                                            onclick="toggleSelectAll()">Select All</button>
                                        <button type="button" class="distributeBtn"
                                            onclick="confirmAssign()">Assign</button>
                                    </div>
                                </form>

                            </div>
                        </div>


                    </div>




                </div>
            </div>
        </main>

    </div>

    <script>
        function confirmAssign() {
            const selectedWorkers = document.querySelectorAll("input[name='selected_workers[]']:checked");

            if (selectedWorkers.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Workers Selected',
                    text: 'Please select at least one worker to assign.',
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to assign the selected workers.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, assign!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(document.getElementById("assignWorkerForm"));

                        fetch("../endpoints/assign_worker.php", {
                            method: "POST",
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    icon: data.type,
                                    title: data.title,
                                    text: data.message
                                }).then(() => {
                                    if (data.status === 'success') {
                                        location.reload(); // Reload the page on success
                                    }
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Assignment Failed',
                                    text: 'There was an error assigning workers. Please try again.'
                                });
                            });
                    }
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

    <!-- import navbar -->
    <script src="../../includes/personnelpageNav.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>




    <!-- the checkbox will be checked when the tr is clicked -->
    <script>
        function toggleCheckbox(row) {
            const checkbox = row.querySelector("input[type='checkbox']");
            checkbox.checked = !checkbox.checked;
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll("input[name='selected_workers[]']");
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

            // Update the button text
            const toggleButton = document.getElementById("toggleSelectBtn");
            toggleButton.textContent = allChecked ? "Select All" : "Unselect All";
        }
    </script>



</body>

</html>