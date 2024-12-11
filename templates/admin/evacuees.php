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

// Fetch evacuees from the database (latest to oldest)
$barangayFilter = isset($_GET['barangay']) ? $_GET['barangay'] : 'all';

$query = "
    SELECT 
        evacuees.id AS evacuee_id,
        CONCAT(evacuees.first_name, ' ', evacuees.middle_name, ' ', evacuees.last_name, ' ', evacuees.extension_name) AS family_head,
        evacuees.contact,
        evacuees.status,
        evacuees.date,
        evacuees.disaster_type,
        evacuees.barangay,
        COUNT(members.id) AS member_count,
        GROUP_CONCAT(CONCAT(members.first_name, ' ', members.last_name) SEPARATOR ', ') AS member_names
    FROM 
        evacuees
    LEFT JOIN 
        members ON evacuees.id = members.evacuees_id
    WHERE 
        ('$barangayFilter' = 'all' OR evacuees.barangay = '$barangayFilter')
    GROUP BY 
        evacuees.id
    ORDER BY 
        evacuees.date DESC";

$result = $conn->query($query);
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

        .viewMembers {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            padding: 6px;
            border-radius: 8px;
            list-style: none;
            max-width: 200px;
            z-index: 10;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ecMembers:hover .viewMembers {
            display: block;
        }

        .viewMembers li {
            margin-bottom: 5px;
        }

        .table-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }

        .scrollable-table {
            max-height: 500px;
            overflow-y: auto;
        }

        #mainTable {
            width: 100%;
            border-collapse: collapse;
        }

        #mainTable thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
        }

        #mainTable th,
        #mainTable td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
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
                            <a href="evacuees.php">Evacuees</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create new barangay admin account</a> -->
                        </div>

                        <?php
                        // Fetch unique barangays from the admin table
                        $barangayQuery = "SELECT DISTINCT barangay FROM admin WHERE role != 'superadmin' ORDER BY barangay ASC";
                        $barangayResult = $conn->query($barangayQuery);
                        ?>
                        <select id="filterBarangay" class="filter-admin">
                            <option value="all" <?php echo (!isset($_GET['barangay']) || $_GET['barangay'] === 'all') ? 'selected' : ''; ?>>
                                All
                            </option>
                            <?php if ($barangayResult->num_rows > 0): ?>
                                <?php while ($row = $barangayResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['barangay']); ?>" <?php echo (isset($_GET['barangay']) && $_GET['barangay'] === $row['barangay']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['barangay']); ?></option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>No Barangays Found</option>
                            <?php endif; ?>
                        </select>


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

                            <!-- The modal or filter popup -->
                            <div class="modal">
                                <div class="modal-content">
                                    <div class="filter-option">
                                        <div class="option-content">
                                            <input type="checkbox" name="evacuees" id="admit" class="filter-checkbox"
                                                data-filter="Admitted">
                                            <label for="admit">Admitted</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" name="evacuees" id="moveout" class="filter-checkbox"
                                                data-filter="Moved-out">
                                            <label for="moveout">Moved-out</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" name="evacuees" id="transfer" class="filter-checkbox"
                                                data-filter="Transfer">
                                            <label for="transfer">Transfer</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" name="evacuees" id="transferred"
                                                class="filter-checkbox" data-filter="Transferred">
                                            <label for="transferred">Transferred</label>
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


                    <section class="table-container">
                        <div class="scrollable-table">
                            <table id="mainTable">
                                <thead>
                                    <tr>
                                        <th>Family Head</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Number of Members</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Date</th>
                                        <th>Calamity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr data-status="<?php echo htmlspecialchars($row['status']); ?>">
                                                <td><?php echo htmlspecialchars($row['family_head']); ?></td>
                                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                                <td class="ecMembers" style="text-align: center; position: relative;">
                                                    <?php echo $row['member_count']; ?>
                                                    <ul class="viewMembers">
                                                        <?php
                                                        $member_names = explode(', ', $row['member_names']);
                                                        foreach ($member_names as $member_name): ?>
                                                            <li><?php echo htmlspecialchars($member_name); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($row['status']); ?>
                                                </td>
                                                <td style="text-align: center;"><?php echo htmlspecialchars($row['date']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['disaster_type']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No Evacuees Yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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
        document.getElementById('filterBarangay').addEventListener('change', function () {
            const selectedBarangay = this.value;
            const url = new URL(window.location.href);

            // Update or remove the 'barangay' query parameter
            if (selectedBarangay === 'all') {
                url.searchParams.delete('barangay');
            } else {
                url.searchParams.set('barangay', selectedBarangay);
            }

            window.location.href = url;
        });

        // Function to filter rows based on selected checkboxes
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const selectedFilters = Array.from(document.querySelectorAll('.filter-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.filter);

                // Select all rows in the table body
                const rows = document.querySelectorAll('#mainTable tbody tr');

                rows.forEach(row => {
                    const rowStatus = row.dataset.status;

                    // Show or hide row based on whether its status matches any selected filter
                    if (selectedFilters.length === 0 || selectedFilters.includes(rowStatus)) {
                        row.style.display = ''; // Show row
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });
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