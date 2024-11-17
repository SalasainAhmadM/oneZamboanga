<?php
require_once '../../connection/conn.php';

if (isset($_GET['id']) && isset($_GET['center_id']) && isset($_GET['worker_id'])) {
    $evacueeId = intval($_GET['id']);
    $centerId = intval($_GET['center_id']);
    $workerId = intval($_GET['worker_id']);
}

// Fetch the main evacuee details
$query = "SELECT * FROM evacuees WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $evacueeId);
$stmt->execute();
$result = $stmt->get_result();
$evacuee = $result->fetch_assoc();

// Fetch the evacuation center details
$evacuationQuery = "SELECT * FROM evacuation_center WHERE id = ?";
$evacuationStmt = $conn->prepare($evacuationQuery);
$evacuationStmt->bind_param("i", $evacuee['evacuation_center_id']);
$evacuationStmt->execute();
$evacuationResult = $evacuationStmt->get_result();
$evacuationCenter = $evacuationResult->fetch_assoc();

// Fetch all other evacuation centers for the dropdown
$centersQuery = "SELECT id, name FROM evacuation_center WHERE id != ? AND admin_id = ?";
$centersStmt = $conn->prepare($centersQuery);
$centersStmt->bind_param("ii", $evacuee['evacuation_center_id'], $evacuee['admin_id']);
$centersStmt->execute();
$centersResult = $centersStmt->get_result();
$otherCenters = [];
while ($center = $centersResult->fetch_assoc()) {
    $otherCenters[] = $center;
}
// Fetch household members
$membersQuery = "SELECT * FROM members WHERE evacuees_id = ?";
$membersStmt = $conn->prepare($membersQuery);
$membersStmt->bind_param("i", $evacueeId);
$membersStmt->execute();
$membersResult = $membersStmt->get_result();

// Fetch activity logs
$logsQuery = "SELECT log_msg, created_at FROM evacuees_log WHERE evacuees_id = ? ORDER BY created_at DESC";
$logsStmt = $conn->prepare($logsQuery);
$logsStmt->bind_param("i", $evacueeId);
$logsStmt->execute();
$logsResult = $logsStmt->get_result();
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
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css">
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesProfile.css">


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
                            <a href="viewAssignedEC.php?id=<?php echo $centerId; ?>&worker_id=<?php echo $workerId; ?>">
                                <?php echo $evacuationCenter['name']; ?>
                            </a>
                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="evacueesPage.php?id=<?php echo $centerId; ?>&worker_id=<?php echo $workerId; ?>">Evacuees</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Profile</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container eProfile">
                    <!-- modal -->
                    <div class="profile-cta">
                        <label for="cta-toggle" class="cta-button">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </label>
                        <input type="checkbox" name="" id="cta-toggle" class="cta-toggle">

                        <div class="cta-modal">
                            <div class="cta-options" style="text-align: center;">
                                <?php if ($evacuee['status'] === 'Moved-out'): ?>
                                    <a href="" id="admitButton">Admit</a>
                                    <script>
                                        document.getElementById('admitButton').addEventListener('click', function (event) {
                                            event.preventDefault(); // Prevent default anchor action

                                            Swal.fire({
                                                title: 'Admit Evacuee',
                                                text: 'Are you sure you want to admit this evacuee back to the evacuation center?',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, Admit!',
                                                cancelButtonText: 'Cancel'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Send AJAX request to admit the evacuee
                                                    fetch("../endpoints/admit_back.php?id=<?= $evacueeId; ?>", {
                                                        method: "POST",
                                                        headers: {
                                                            "Content-Type": "application/json"
                                                        }
                                                    })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                // Show success SweetAlert
                                                                Swal.fire({
                                                                    title: 'Success!',
                                                                    text: 'Evacuee has been successfully admitted back.',
                                                                    icon: 'success',
                                                                    confirmButtonText: 'OK'
                                                                }).then(() => {
                                                                    // Optionally reload or redirect to update the UI
                                                                    location.reload();
                                                                });
                                                            } else {
                                                                // Show error SweetAlert
                                                                Swal.fire({
                                                                    title: 'Error!',
                                                                    text: data.message || 'An error occurred while admitting the evacuee.',
                                                                    icon: 'error',
                                                                    confirmButtonText: 'OK'
                                                                });
                                                            }
                                                        })
                                                        .catch(error => {
                                                            // Handle any network or server errors
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: 'Failed to communicate with the server.',
                                                                icon: 'error',
                                                                confirmButtonText: 'OK'
                                                            });
                                                        });
                                                }
                                            });
                                        });
                                    </script>
                                <?php else: ?>
                                    <a href="evacueesFormEdit.php?id=<?php echo $evacueeId; ?>">Edit</a>
                                    <a href="#" id="transferBtn">Transfer</a>
                                    <a href="#" id="moveOutBtn">Move out</a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <div class="eprofile-top">
                        <div class="evacueesProfile">
                            <div class="profileInfo-left">
                                <img class="profileImg" src="../../assets/img/undraw_male_avatar_g98d.svg" alt="">
                                <p class="leader">Household Leader</p>
                            </div>

                            <div class="profileInfo-right">
                                <h3 profile-name>
                                    <?php echo $evacuee['first_name'] . ' ' . $evacuee['middle_name'] . ' ' . $evacuee['last_name']; ?>
                                </h3>

                                <div class="profile-details">
                                    <p class="details-profile">Address: <?php echo $evacuee['barangay']; ?></p>
                                    <p class="details-profile">Sex: <?php echo $evacuee['gender']; ?></p>
                                    <p class="details-profile">Birth date:
                                        <?php echo date("F j, Y", strtotime($evacuee['birthday'])); ?>
                                    </p>
                                    <p class="details-profile">Age: <?php echo $evacuee['age']; ?></p>
                                    <p class="details-profile">Contact Number: <?php echo $evacuee['contact']; ?></p>
                                    <p class="details-profile">Occupation: <?php echo $evacuee['occupation']; ?></p>
                                    <p class="details-profile">Status of Occupancy: <?php echo $evacuee['status']; ?>
                                    </p>
                                    <p class="details-profile">Damaged: <?php echo ucfirst($evacuee['damage']); ?></p>
                                    <p class="details-profile">Cost of damaged: <?php echo $evacuee['cost_damage']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="eprofileLog">
                            <div class="right-header">
                                <div class="activityLog">
                                    <p class="activityLog-header" style="color: var(--clr-slate600);">
                                        Activity Logs
                                    </p>
                                    <div class="activeLine log"></div>
                                </div>
                            </div>

                            <div class="data">
                                <?php while ($log = $logsResult->fetch_assoc()): ?>
                                    <div class="log-container">
                                        <p class="logDate"><?php echo date("m-d-Y", strtotime($log['created_at'])); ?></p>
                                        <div class="logDivider"></div>
                                        <p class="logInfo"><?php echo htmlspecialchars($log['log_msg']); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                    </div>





                    <div class="table-container">
                        <section class="tblheader">

                            <h4>Family Members</h4>



                            <div class="input_group">
                                <input type="search" id="searchInput" placeholder="Search...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Relationship</th>
                                        <th>Age</th>
                                        <th style="text-align: center;">Gender</th>
                                        <th>Education</th>
                                        <th>Occupation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($membersResult->num_rows > 0) { ?>
                                        <?php while ($member = $membersResult->fetch_assoc()) { ?>
                                            <tr onclick="window.location.href='#'">
                                                <td><?php
                                                // Concatenate the names for the full name
                                                echo $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['extension_name'];
                                                ?></td>
                                                <td><?php echo $member['relation']; ?></td>
                                                <td><?php echo $member['age']; ?></td>
                                                <td style="text-align: center;"><?php echo $member['gender']; ?></td>
                                                <td><?php echo $member['education']; ?></td>
                                                <td><?php echo $member['occupation']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No Family Member for this evacuee.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </section>


                        <div class="no-match-message">No matching data found</div>
                    </div>

                    <!-- <div class="eprofile-bot">
                    </div> -->




                </div>
            </div>
        </main>

    </div>
    <style>
        /* Set a fixed width for the SweetAlert select container */
        .swal2-container .swal2-select {
            width: 100%;
            /* Full width within the modal */
            max-width: 380px;
            /* Set fixed width to 400px */
            box-sizing: border-box;
            /* Ensures padding doesn't affect width */
        }

        /* Ensure the dropdown options take the same width */
        .swal2-container .swal2-select option {
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* Keep the text in one line with ellipsis if needed */
        }
    </style>
    <script>

        // JavaScript to filter table based on search input
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#mainTable tbody tr');

            rows.forEach(row => {
                let fullName = row.cells[0].textContent.toLowerCase();
                if (fullName.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('transferBtn').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default anchor action

            Swal.fire({
                title: 'Transfer Evacuee',
                html: `
        <label for="centerSelect">Select a new evacuation center:</label>
        <select id="centerSelect" class="swal2-select" style="width: 400px;">
            <?php foreach ($otherCenters as $center): ?>
                                                                                                                            <option value="<?= $center['id']; ?>"><?= htmlspecialchars($center['name']); ?></option>
            <?php endforeach; ?>
        </select>
    `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Transfer',
                cancelButtonText: 'Cancel',
                customClass: {
                    htmlContainer: 'fixed-width-container' // Add a custom class to control width
                },
                preConfirm: () => {
                    const centerSelect = document.getElementById('centerSelect');
                    if (!centerSelect.value) {
                        Swal.showValidationMessage('Please select an evacuation center.');
                    }
                    return centerSelect.value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedCenterId = result.value;

                    // Send AJAX request to transfer evacuee
                    fetch("../endpoints/transfer_evacuee.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            evacuee_id: <?= $evacueeId; ?>, // Inject evacuee ID dynamically
                            center_id: selectedCenterId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Evacuee successfully transferred to the new center.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Redirect to requestTransfer.php
                                    window.location.href = "evacueesPage.php?id=<?php echo $centerId; ?>&worker_id=<?php echo $workerId; ?>";
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'An error occurred during the transfer.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to communicate with the server.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });


        // 
        document.getElementById('moveOutBtn').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default anchor action

            Swal.fire({
                title: 'Are you sure?',
                text: "This will mark the evacuee as moved out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to admit the evacuee
                    fetch("../endpoints/moved_out_evacuee.php?id=<?= $evacueeId; ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success SweetAlert
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Evacuee has been successfully moved-out.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Optionally reload or redirect to update the UI
                                    location.reload();
                                });
                            } else {
                                // Show error SweetAlert
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'An error occurred while moved-out the evacuee.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            // Handle any network or server errors
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to communicate with the server.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });
        // SweetAlert confirmation for Move out
        // document.getElementById('moveOutBtn').addEventListener('click', function (event) {
        //     event.preventDefault(); // Prevent default anchor action

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "This will mark the evacuee as moved out.",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Confirm!',
        //         cancelButtonText: 'Cancel'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Redirect to the move-out handler with evacuees_id
        //             window.location.href = "moveOutHandler.php?id=<?= $evacueeId; ?>";
        //         }
        //     });
        // });



    </script>

    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/navbarECworkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>