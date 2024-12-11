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
validateSession('admin');

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

} else {
    header("Location: ../../login.php");
    exit;
}

// Fetch approved supplies (Received) for the admin's evacuation centers
$query = "
    SELECT 
        s.id AS supply_id,
        s.name AS supply_name,
        s.description,
        s.quantity,
        s.original_quantity,
        s.unit,
        s.image,
        s.date,
        s.time,
        s.`from` AS supply_from,
        s.approved,
        ec.name AS evacuation_center_name,
        ec.location AS evacuation_center_location
    FROM 
        supply AS s
    INNER JOIN 
        evacuation_center AS ec
    ON 
        s.evacuation_center_id = ec.id
    WHERE 
        ec.admin_id = ? 
        AND s.approved = 1  
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$supplies = [];
while ($row = $result->fetch_assoc()) {
    $supplies[] = $row;
}


// Fetch distributed supplies (if any) for the admin's evacuation centers
$distributed_query = "
    SELECT 
        d.id AS distribute_id,
        d.supply_name,
        d.date,
        d.quantity,
        ec.name AS evacuation_center_name,
        CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name) AS evacuee_name,
        d.distributor_type
    FROM 
        distribute AS d
    INNER JOIN 
        evacuees AS e
    ON 
        d.evacuees_id = e.id
    INNER JOIN 
        evacuation_center AS ec
    ON 
        e.evacuation_center_id = ec.id
    WHERE 
        ec.admin_id = ?
";
$distributed_stmt = $conn->prepare($distributed_query);
$distributed_stmt->bind_param("i", $admin_id);
$distributed_stmt->execute();
$distributed_result = $distributed_stmt->get_result();

$distributed = [];
while ($row = $distributed_result->fetch_assoc()) {
    $distributed[] = $row;
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
    <link rel="stylesheet" href="../../assets/styles/utils/viewEC.css">
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css">


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .viewSupply {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        list-style: none;
        z-index: 10000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .ecSupply {
        position: relative;
        cursor: pointer;
    }
</style>

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
                            <a href="#">Supply Reports</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>


                        <label for="startDate">Start:</label>
                        <input type="date" id="startDate" class="filter-admin" onchange="filterTableByDate()">

                        <label for="endDate">End:</label>
                        <input type="date" id="endDate" class="filter-admin" onchange="filterTableByDate()">

                        <select id="filterBarangay" class="filter-admin" onchange="filterEvacuationCenter()">
                            <option value="All">All</option>
                        </select>


                        <button class="addBg-admin" id="exportButton">Export</button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-personnel></special-personnel>



                    <div class="table-container">
                        <section class="tblheader">
                            <div class="filter-popup">
                                <label for="modal-toggle" class="modal-button">
                                    <i class="fa-solid fa-filter"></i>
                                </label>
                                <input type="checkbox" name="" id="modal-toggle" class="modal-toggle">

                                <div class="modal">
                                    <div class="modal-content">
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="filter" id="received" checked>
                                                <label for="received">Received</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="filter" id="distributed">
                                                <label for="distributed">Distributed</label>
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
                            <table id="mainTable" style="margin-bottom: 10px">
                                <!-- Received Table Header -->
                                <thead id="receivedHeader">
                                    <tr>
                                        <th>Supply Name</th>
                                        <th>Quantity</th>
                                        <th style="text-align: center;">Stocks</th>
                                        <th>Evacuation Center</th>
                                        <th>Date</th>
                                        <th>Source</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <!-- Received Supplies -->
                                <tbody id="receivedTable">
                                    <?php if (!empty($supplies)): ?>
                                        <?php foreach ($supplies as $supply): ?>
                                            <tr>
                                                <td class="supplyName"><?php echo htmlspecialchars($supply['supply_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($supply['quantity']); ?>
                                                    /<?php echo htmlspecialchars($supply['original_quantity']); ?>
                                                    <?php echo htmlspecialchars($supply['unit']); ?>s
                                                </td>
                                                <td class="ecSupply" style="text-align: center;">
                                                    <?php
                                                    // Sum stock quantities and original quantities for the current supply
                                                    $stock_sum_query = "
        SELECT 
            SUM(quantity) AS total_quantity, 
            SUM(original_quantity) AS total_original_quantity 
        FROM stock 
        WHERE supply_id = ?
    ";
                                                    $stock_sum_stmt = $conn->prepare($stock_sum_query);
                                                    $stock_sum_stmt->bind_param("i", $supply['supply_id']);
                                                    $stock_sum_stmt->execute();
                                                    $stock_sum_result = $stock_sum_stmt->get_result();
                                                    $stock_sums = $stock_sum_result->fetch_assoc();

                                                    $total_quantity = $stock_sums['total_quantity'] ?? 0;
                                                    $total_original_quantity = $stock_sums['total_original_quantity'] ?? 0;

                                                    // Display total quantity / total original quantity
                                                    echo htmlspecialchars($total_quantity) . " / " . htmlspecialchars($total_original_quantity);
                                                    ?>
                                                    <ul class="viewSupply" style="text-align: left; z-index: 10000;">
                                                        <?php
                                                        // Fetch stock details for the current supply (hover stays the same)
                                                        $stock_query = "
            SELECT `from`, quantity, original_quantity, unit 
            FROM stock 
            WHERE supply_id = ?
        ";
                                                        $stock_stmt = $conn->prepare($stock_query);
                                                        $stock_stmt->bind_param("i", $supply['supply_id']);
                                                        $stock_stmt->execute();
                                                        $stock_result = $stock_stmt->get_result();

                                                        while ($stock = $stock_result->fetch_assoc()): ?>
                                                            <li>
                                                                <strong>From:</strong>
                                                                <?php echo htmlspecialchars($stock['from']); ?>
                                                                <strong>Quantity:</strong>
                                                                <?php echo htmlspecialchars($stock['quantity']); ?> /
                                                                <?php echo htmlspecialchars($stock['original_quantity']); ?>
                                                                <?php echo htmlspecialchars($stock['unit']); ?>s
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                    <?php echo htmlspecialchars($supply['unit']); ?>s
                                                </td>

                                                <td><?php echo htmlspecialchars($supply['evacuation_center_name']); ?></td>
                                                <td><?php echo htmlspecialchars($supply['date']); ?></td>
                                                <td><?php echo htmlspecialchars($supply['supply_from']); ?></td>
                                                <td style="text-align: center;">
                                                    <a class="view-action" href="javascript:void(0);"
                                                        onclick="printSupply(<?php echo htmlspecialchars($supply['supply_id']); ?>)">Print</a>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No supplies found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                                <!-- Distributed Table Header -->
                                <thead id="distributedHeader" style="display:none;">
                                    <tr>
                                        <th>Supply Name</th>
                                        <th>Distributed to</th>
                                        <th>Quantity</th>
                                        <th>Evacuation Center</th>
                                        <th>Date Distributed</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <!-- Distributed Supplies -->
                                <tbody id="distributedTable" style="display:none;">
                                    <?php if (!empty($distributed)): ?>
                                        <?php foreach ($distributed as $distribution): ?>
                                            <tr>
                                                <td class="supplyName">
                                                    <?php echo htmlspecialchars($distribution['supply_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($distribution['evacuee_name']); ?></td>
                                                <td><?php echo htmlspecialchars($distribution['quantity']); ?></td>
                                                <td><?php echo htmlspecialchars($distribution['evacuation_center_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($distribution['date']); ?></td>
                                                <td style="text-align: center;">
                                                    <a class="view-action" href="javascript:void(0);"
                                                        onclick="printDistributed(<?php echo htmlspecialchars($distribution['distribute_id']); ?>)">Print</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No distributed supplies found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </section>

                        <div class="no-match-message">No matching data found</div>
                    </div>

                    <script>
                        function filterTableByDate() {
                            const startDateValue = document.getElementById('startDate').value;
                            const endDateValue = document.getElementById('endDate').value;

                            const startDate = startDateValue ? new Date(startDateValue) : null;
                            const endDate = endDateValue ? new Date(endDateValue) : null;

                            const receivedTable = document.getElementById('receivedTable');
                            const distributedTable = document.getElementById('distributedTable');
                            const isReceivedVisible = receivedTable.style.display !== 'none';
                            const tableToFilter = isReceivedVisible ? receivedTable : distributedTable;

                            const rows = tableToFilter.getElementsByTagName('tr');
                            for (const row of rows) {
                                const dateCell = row.cells[4];
                                if (!dateCell) continue;

                                const rowDate = new Date(dateCell.textContent.trim());
                                let showRow = true;

                                if (startDate && rowDate < startDate) {
                                    showRow = false;
                                }

                                if (endDate && rowDate > endDate) {
                                    showRow = false;
                                }

                                row.style.display = showRow ? '' : 'none';
                            }
                        }

                        document.getElementById('received').addEventListener('change', function () {
                            if (this.checked) {
                                document.getElementById('distributed').checked = false;
                                document.getElementById('receivedTable').style.display = 'table-row-group';
                                document.getElementById('receivedHeader').style.display = 'table-header-group';
                                document.getElementById('distributedTable').style.display = 'none';
                                document.getElementById('distributedHeader').style.display = 'none';
                                updateEvacuationCenterFilter();
                                filterTableByDate();
                            }
                        });

                        document.getElementById('distributed').addEventListener('change', function () {
                            if (this.checked) {
                                document.getElementById('received').checked = false;
                                document.getElementById('distributedTable').style.display = 'table-row-group';
                                document.getElementById('distributedHeader').style.display = 'table-header-group';
                                document.getElementById('receivedTable').style.display = 'none';
                                document.getElementById('receivedHeader').style.display = 'none';
                                updateEvacuationCenterFilter();
                                filterTableByDate();
                            }
                        });
                        function updateEvacuationCenterFilter() {
                            const filterSelect = document.getElementById('filterBarangay');
                            filterSelect.innerHTML = '<option value="all">All</option>';

                            const activeTable =
                                document.querySelector('#receivedTable').style.display !== 'none'
                                    ? document.getElementById('receivedTable')
                                    : document.getElementById('distributedTable');

                            const evacuationCenters = new Set();

                            const rows = activeTable.getElementsByTagName('tr');
                            for (const row of rows) {
                                const centerCell = row.cells[3];
                                if (centerCell) {
                                    evacuationCenters.add(centerCell.textContent.trim());
                                }
                            }

                            evacuationCenters.forEach(center => {
                                const option = document.createElement('option');
                                option.value = center;
                                option.textContent = center;
                                filterSelect.appendChild(option);
                            });
                        }


                        function filterTableByDateAndCenter() {
                            const filterValue = document.getElementById('filterBarangay').value.toLowerCase();
                            const startDateValue = document.getElementById('startDate').value;
                            const endDateValue = document.getElementById('endDate').value;

                            const startDate = startDateValue ? new Date(startDateValue) : null;
                            const endDate = endDateValue ? new Date(endDateValue) : null;

                            const receivedTable = document.getElementById('receivedTable');
                            const distributedTable = document.getElementById('distributedTable');
                            const isReceivedVisible = receivedTable.style.display !== 'none';
                            const tableToFilter = isReceivedVisible ? receivedTable : distributedTable;

                            const rows = tableToFilter.getElementsByTagName('tr');
                            for (const row of rows) {
                                const centerCell = row.cells[3];
                                const dateCell = row.cells[4];
                                if (!centerCell || !dateCell) continue;

                                const centerName = centerCell.textContent.trim().toLowerCase();
                                const rowDate = new Date(dateCell.textContent.trim());
                                let showRow = true;

                                if (filterValue !== 'all' && centerName !== filterValue) {
                                    showRow = false;
                                }

                                if (startDate && rowDate < startDate) {
                                    showRow = false;
                                }

                                if (endDate && rowDate > endDate) {
                                    showRow = false;
                                }

                                row.style.display = showRow ? '' : 'none';
                            }
                        }

                        document.getElementById('filterBarangay').addEventListener('change', filterTableByDateAndCenter);

                        document.getElementById('startDate').addEventListener('change', filterTableByDateAndCenter);
                        document.getElementById('endDate').addEventListener('change', filterTableByDateAndCenter);


                        document.addEventListener('DOMContentLoaded', function () {
                            updateEvacuationCenterFilter();

                            document.getElementById('filterBarangay').addEventListener('change', filterEvacuationCenter);

                            document.getElementById('startDate').addEventListener('change', filterTableByDate);
                            document.getElementById('endDate').addEventListener('change', filterTableByDate);
                        });

                        function exportReport() {
                            const isReceived = document.getElementById('received').checked;
                            const evacuationCenterFilter = document.getElementById('filterBarangay');
                            const evacuationCenterName = evacuationCenterFilter ? evacuationCenterFilter.value : 'all';

                            const startDate = document.getElementById('startDate').value;
                            const endDate = document.getElementById('endDate').value;

                            const filter = isReceived ? 'received' : 'distributed';

                            // Redirect to export_supply.php with additional date parameters
                            window.location.href = `../export/export_supply.php?filter=${filter}&evacuation_center_name=${encodeURIComponent(evacuationCenterName)}&start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`;
                        }

                        document.getElementById('searchInput').addEventListener('keyup', function () {
                            const searchValue = this.value.toLowerCase();
                            const tableRows = document.querySelectorAll('#receivedTable tr');

                            tableRows.forEach(row => {
                                const supplyName = row.querySelector('.supplyName')?.textContent.toLowerCase();
                                if (supplyName && supplyName.includes(searchValue)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });
                    </script>


                </div>
            </div>
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const supplyRows = document.querySelectorAll('.ecSupply');

            supplyRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    const details = this.querySelector('.viewSupply');
                    if (details) details.style.display = 'block';
                });

                row.addEventListener('mouseleave', function () {
                    const details = this.querySelector('.viewSupply');
                    if (details) details.style.display = 'none';
                });
            });
        });


        document.getElementById('exportButton').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export the report?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    exportReport();
                    // Swal.fire('Exported!', 'The report has been exported successfully.', 'success');
                }
            });
        });
        function printSupply(supplyId) {
            Swal.fire({
                title: 'Print Report?',
                text: `Confirm to print the report for this received supply?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger the export PHP script
                    window.location.href = `../export/export_received_supply.php?supply_id=${supplyId}`;
                }
            });
        }

        function printDistributed(distributeId) {
            Swal.fire({
                title: 'Print Report?',
                text: `Confirm to print the report for this distributed supply?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger the export PHP script
                    window.location.href = `../export/export_distributed.php?distribute_id=${distributeId}`;
                }
            });
        }

    </script>
    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>
    <script src="../../includes/printReports.js"></script>
    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>