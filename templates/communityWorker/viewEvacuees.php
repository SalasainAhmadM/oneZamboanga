<?php
session_start();
require_once '../../connection/conn.php';
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
validateSession('worker');

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

// Fetch all other evacuation centers for the dropdown including capacity and evacuees count
$centersQuery = "
    SELECT 
        ec.id, 
        ec.name, 
        ec.capacity, 
        (
            SELECT COUNT(*)
            FROM evacuees e
            WHERE 
                e.evacuation_center_id = ec.id 
                AND (
                    e.status = 'Admitted' 
                    OR (e.status = 'Transfer' AND e.evacuation_center_id = e.origin_evacuation_center_id)
                )
        ) AS evacuees_count
    FROM evacuation_center ec
    WHERE ec.id != ? AND ec.admin_id = ?";
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

// Fetch all admins and their barangays
$adminsQuery = "SELECT id, barangay FROM admin WHERE id != ?";
$adminsStmt = $conn->prepare($adminsQuery);
$adminsStmt->bind_param("i", $evacuee['admin_id']);
$adminsStmt->execute();
$adminsResult = $adminsStmt->get_result();
$admins = [];
while ($admin = $adminsResult->fetch_assoc()) {
    $admins[] = $admin;
}

// Fetch evacuation centers not managed by the current admin
$otherCentersQuery = "SELECT id, name FROM evacuation_center WHERE admin_id != ?";
$otherCentersStmt = $conn->prepare($otherCentersQuery);
$otherCentersStmt->bind_param("i", $evacuee['admin_id']);
$otherCentersStmt->execute();
$otherCentersResult = $otherCentersStmt->get_result();
$allOtherCenters = [];
while ($center = $otherCentersResult->fetch_assoc()) {
    $allOtherCenters[] = $center;
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
                    <div class="profile-cta" <?php if ($evacuee['status'] === 'Transferred')
                        echo 'style="display:none;"'; ?>>
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
                                    <p class="details-profile">Calamity:
                                        <?php echo ucfirst($evacuee['disaster_type']); ?>
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



                            <div class="input_group" style="margin-top: 10px">
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
            event.preventDefault();

            Swal.fire({
                title: 'Choose Transfer Type',
                text: 'Do you want to transfer the evacuee within the Current Barangay or to the Nearest Barangay?',
                showCancelButton: true,
                confirmButtonText: 'Current Barangay',
                cancelButtonText: 'Other Barangay',
                customClass: {
                    htmlContainer: 'fixed-width-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Transfer within Current Barangay',
                        html: `
        <label for="centerSelect">Select a new evacuation center:</label>
        <select id="centerSelect" class="swal2-select" style="width: 400px; font-size: 14px;">
            <?php foreach ($otherCenters as $center): ?>
                                            <option value="<?= $center['id']; ?>" <?= $center['capacity'] <= $center['evacuees_count'] ? 'disabled' : ''; ?>>
                                                <?= htmlspecialchars($center['name'] . ' (Evacuees: ' . $center['evacuees_count'] . '/' . $center['capacity'] . ')'); ?>
                                            </option>
            <?php endforeach; ?>
        </select>
    `,
                        showCancelButton: true,
                        confirmButtonText: 'Transfer',
                        preConfirm: () => {
                            const centerSelect = document.getElementById('centerSelect');
                            if (!centerSelect.value) {
                                Swal.showValidationMessage('Please select an evacuation center.');
                            }
                            return centerSelect.value;
                        },
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            transferEvacuee(<?= $evacueeId; ?>, result.value);
                        }
                    });


                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Transfer to Other Barangay',
                        html: `
                <label for="barangaySelect">Select a Barangay:</label>
                <select id="barangaySelect" class="swal2-select" style="width: 400px;">
                    <option value="" disabled selected>Select a Barangay</option>
                    <?php foreach ($admins as $admin): ?>
                                                                        <option value="<?= $admin['id']; ?>"><?= htmlspecialchars($admin['barangay']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="centerSelect">Select an Evacuation Center:</label>
                <select id="centerSelect" class="swal2-select" style="width: 400px; font-size: 14px;" disabled>
                    <option value="" disabled selected>Select a Barangay first</option>
                </select>
                `,
                        showCancelButton: true,
                        confirmButtonText: 'Transfer',
                        allowOutsideClick: false,
                        preConfirm: () => {
                            const barangaySelect = document.getElementById('barangaySelect').value;
                            const centerSelect = document.getElementById('centerSelect').value;

                            if (!barangaySelect || !centerSelect) {
                                Swal.showValidationMessage('Please select both a barangay and an evacuation center.');
                            }
                            return { adminId: barangaySelect, centerId: centerSelect };
                        },
                        didOpen: () => {
                            const barangaySelect = document.getElementById('barangaySelect');
                            const centerSelect = document.getElementById('centerSelect');

                            barangaySelect.addEventListener('change', () => {
                                const adminId = barangaySelect.value;

                                if (adminId) {
                                    fetch("../endpoints/fetch_centers_by_barangay.php", {
                                        method: "POST",
                                        headers: { "Content-Type": "application/json" },
                                        body: JSON.stringify({ admin_id: adminId })
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                centerSelect.innerHTML = `<option value="" disabled selected>Select an Evacuation Center</option>`;
                                                data.centers.forEach(center => {
                                                    const option = document.createElement('option');
                                                    option.value = center.id;
                                                    option.textContent = `${center.name} (Evacuees: ${center.evacuees_count}/${center.capacity})`;
                                                    option.disabled = center.capacity <= center.evacuees_count; // Disable full centers
                                                    centerSelect.appendChild(option);
                                                });
                                                centerSelect.disabled = false;
                                            } else {
                                                centerSelect.innerHTML = `<option value="" disabled>${data.message}</option>`;
                                                centerSelect.disabled = true;
                                            }
                                        })
                                        .catch(() => {
                                            centerSelect.innerHTML = `<option value="" disabled>Error fetching centers</option>`;
                                            centerSelect.disabled = true;
                                        });
                                }
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const { adminId, centerId } = result.value;
                            transferToOtherBarangay(<?= $evacueeId; ?>, adminId, centerId);
                        }
                    });
                }
            });
        });


        function transferToOtherBarangay(evacueeId, adminId, centerId) {
            fetch("../endpoints/transfer_to_other_barangay.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ evacuee_id: evacueeId, admin_id: adminId, center_id: centerId })
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    Swal.fire('Success!', 'Evacuee transfer to the new barangay successfull and is pending for approval.', 'success')
                        .then(() => {
                            window.location.href = `evacueesPage.php?id=${centerId}&worker_id=<?php echo $workerId; ?>`;
                        });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            }).catch(error => {
                Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
            });
        }


        // AJAX function for transferring evacuee within the Current Barangay
        function transferEvacuee(evacueeId, centerId) {
            fetch("../endpoints/transfer_evacuee.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ evacuee_id: evacueeId, center_id: centerId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Evacuee transfer successfull and is pending for approval.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = `evacueesPage.php?id=<?php echo $centerId; ?>&worker_id=<?php echo $workerId; ?>`;
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'An error occurred during the transfer.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Failed to communicate with the server.', 'error'));
        }
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
                    // Fetch if evacuee has received required supplies
                    fetch(`../endpoints/check_supplies.php?id=<?= $evacueeId; ?>`, {
                        method: "GET"
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.hasRequiredSupplies) {
                                    // Proceed with move-out if all supplies are received
                                    fetch(`../endpoints/moved_out_evacuee.php?id=<?= $evacueeId; ?>`, {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json"
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'Evacuee has been successfully moved out.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: data.message || 'An error occurred while moving out the evacuee.',
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
                                } else {
                                    Swal.fire({
                                        title: 'Distribute Supplies First',
                                        text: 'Evacuee cannot be moved out because they have not received the required supplies.',
                                        icon: 'info',
                                        showCancelButton: true,
                                        confirmButtonText: 'Distribute?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = `resourceDistribution.php?id=<?php echo $evacuationCenter['id']; ?>`;
                                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        }
                                    });

                                }
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'Failed to verify evacuee supplies.',
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