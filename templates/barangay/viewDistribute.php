<?php
require_once '../../connection/conn.php';

// Get the supply ID from the URL parameter
$supplyId = $_GET['id'];

// SQL query to fetch supply details, evacuation center name, and category name
$query = "
    SELECT 
        supply.name AS supply_name,
        supply.description,
        supply.quantity,
        supply.original_quantity,
        supply.unit,
        supply.image,
        evacuation_center.name AS evacuation_center_name,
        evacuation_center.id AS evacuation_center_id,
        category.name AS category_name
    FROM 
        supply
    JOIN 
        evacuation_center ON supply.evacuation_center_id = evacuation_center.id
    JOIN 
        category ON supply.category_id = category.id
    WHERE 
        supply.id = ?
";

// Prepare and execute the statement
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supplyId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $supplyName = $row['supply_name'];
    $categoryName = $row['category_name'];
    $description = $row['description'];
    $quantity = $row['quantity'];
    $quantityOrig = $row['original_quantity'];
    $unit = $row['unit'];
    $image = $row['image'];
    $evacuationCenterName = $row['evacuation_center_name'];
    $evacuationCenterId = $row['evacuation_center_id'];

    // Fetch the total additional quantity from the stock table
    $stockQuery = "
        SELECT SUM(quantity) AS total_stock_quantity
        FROM stock
        WHERE supply_id = ?
    ";
    $stockStmt = $conn->prepare($stockQuery);
    $stockStmt->bind_param("i", $supplyId);
    $stockStmt->execute();
    $stockResult = $stockStmt->get_result();

    $totalStockQuantity = 0;
    if ($stockResult->num_rows > 0) {
        $stockRow = $stockResult->fetch_assoc();
        $totalStockQuantity = $stockRow['total_stock_quantity'] ? $stockRow['total_stock_quantity'] : 0;
    }

    // Calculate the total quantity (initial + stock)
    $totalQuantity = $quantity + $totalStockQuantity;

    // Fetch evacuees who have not been distributed this supply
    $evacueesQuery = "
SELECT 
    e.id AS evacuee_id,
    CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
    e.status
FROM 
    evacuees e
WHERE 
    e.evacuation_center_id = ? 
    AND NOT EXISTS (
        SELECT 1 
        FROM distribute d 
        WHERE d.evacuees_id = e.id 
          AND d.supply_id = ?
    )
";
    $evacueesStmt = $conn->prepare($evacueesQuery);
    $evacueesStmt->bind_param("ii", $evacuationCenterId, $supplyId);
    $evacueesStmt->execute();
    $evacueesResult = $evacueesStmt->get_result();

} else {
    // Handle the case where the supply or evacuation center is not found
    $supplyName = "Supply Not Found";
    $categoryName = "Unknown";
    $description = "";
    $totalQuantity = 0;
    $unit = "";
    $image = "default.png";
    $evacuationCenterName = "Evacuation Center Not Found";
}

$evacueeQuery = "
    SELECT 
        distribute.id AS distribute_id,
        distribute.evacuees_id,
        CONCAT(evacuees.first_name, ' ', evacuees.middle_name, ' ', evacuees.last_name, ' ', evacuees.extension_name) AS family_head,
        DATE_FORMAT(distribute.date, '%m-%d-%Y') AS date_received,
        DATE_FORMAT(distribute.date, '%h:%i %p') AS time_received,
        CONCAT(distribute.quantity, ' pack') AS quantity,
        'Admitted' AS status  
    FROM distribute
    LEFT JOIN evacuees ON distribute.evacuees_id = evacuees.id
    WHERE distribute.supply_id = ?
";
$evacueeStmt = $conn->prepare($evacueeQuery);
$evacueeStmt->bind_param("i", $supplyId);
$evacueeStmt->execute();
$evacueeResult = $evacueeStmt->get_result();

$distributedQuery = "
    SELECT 
        distribute.id AS distribute_id,
        distribute.evacuees_id,
        CONCAT(evacuees.first_name, ' ', evacuees.middle_name, ' ', evacuees.last_name, ' ', evacuees.extension_name) AS family_head,
        DATE_FORMAT(distribute.date, '%m-%d-%Y') AS date_received,
        DATE_FORMAT(distribute.date, '%h:%i %p') AS time_received,
        distribute.quantity,
        evacuees.status,
        supply.unit AS supply_unit
    FROM distribute
    LEFT JOIN evacuees ON distribute.evacuees_id = evacuees.id
    LEFT JOIN supply ON distribute.supply_id = supply.id
    WHERE distribute.supply_id = ?
";

$distributedStmt = $conn->prepare($distributedQuery);
$distributedStmt->bind_param("i", $supplyId);
$distributedStmt->execute();
$distributedResult = $distributedStmt->get_result();
?>
<script>
    const evacuationCenterId = <?= json_encode($evacuationCenterId) ?>;
</script>
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
                            <a href="viewEC.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">
                                <?php echo htmlspecialchars($evacuationCenterName); ?>
                            </a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resources.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">Resource
                                Management</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="resourceDistribution.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">Distribution</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container supply"> <!--overview-->

                    <special-navbar></special-navbar>

                    <div class="viewSupply-container" style="box-shadow: none;">


                        <div class="supplyTop itemDonate">
                            <img src="<?php echo htmlspecialchars($image ? $image : '../../assets/img/supplies.png'); ?>"
                                alt="">
                            <ul class="supplyDetails">
                                <li>Supply Name: <?php echo htmlspecialchars($supplyName); ?></li>
                                <li>Category: <?php echo htmlspecialchars($categoryName); ?></li>
                                <li>Description: <?php echo htmlspecialchars($description); ?></li>
                                <li>Quantity: <?php echo htmlspecialchars($totalQuantity); ?>
                                    <?php echo htmlspecialchars($unit); ?>s
                                </li>
                            </ul>
                        </div>

                        <div class="supplyBot">
                            <ul class="supplyTable">
                                <li class="showDistributed active">Distribute</li>
                                <li class="showReceived">Distributed</li>
                            </ul>

                            <div class="supplyTable-container supplyDonate">
                                <form action="javascript:void(0);">
                                    <table class="distributedTable donate">
                                        <thead>
                                            <tr>
                                                <th>Family Head</th>
                                                <th style="text-align: center;">Number of Members</th>
                                                <th style="text-align: center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($evacueesResult && $evacueesResult->num_rows > 0): ?>
                                                <?php while ($evacuee = $evacueesResult->fetch_assoc()): ?>
                                                    <?php
                                                    // Fetch family members for each evacuee
                                                    $membersQuery = "
                        SELECT CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name, ' ', m.extension_name) AS member_name
                        FROM members m
                        WHERE m.evacuees_id = ?
                    ";
                                                    $membersStmt = $conn->prepare($membersQuery);
                                                    $membersStmt->bind_param("i", $evacuee['evacuee_id']);
                                                    $membersStmt->execute();
                                                    $membersResult = $membersStmt->get_result();

                                                    // Count the number of family members
                                                    $memberCount = $membersResult->num_rows;
                                                    ?>
                                                    <tr onclick="toggleCheckbox(this)">
                                                        <td class="selectName" style="text-align: center;">
                                                            <input type="checkbox" name="evacuee_ids[]"
                                                                value="<?php echo $evacuee['evacuee_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($evacuee['family_head']); ?>">
                                                            <?php echo htmlspecialchars($evacuee['family_head']); ?>
                                                        </td>
                                                        <td class="ecMembers" style="text-align: center;">
                                                            <?php echo $memberCount; ?>
                                                            <ul class="viewMembers" style="text-align: left;">
                                                                <?php while ($member = $membersResult->fetch_assoc()): ?>
                                                                    <li><?php echo htmlspecialchars($member['member_name']); ?></li>
                                                                <?php endwhile; ?>
                                                            </ul>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo htmlspecialchars($evacuee['status']); ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" style="text-align: center;">No Evacuees Found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div class="distributeBtn-container">
                                        <button type="button" id="selectAllBtn" class="distributeBtn"
                                            onclick="selectAll()">Select All</button>
                                        <button type="button" class="distributeBtn"
                                            onclick="confirmDistribute()">Distribute</button>
                                    </div>
                                </form>


                                <form action="javascript:void(0);">
                                    <table class="receivedTable sent">
                                        <thead>
                                            <tr>
                                                <th>Family Head</th>
                                                <th style="text-align: center;">Number of Members</th>
                                                <th>Status</th>
                                                <th>Date Received</th>
                                                <th>Time Received</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($distributedResult && $distributedResult->num_rows > 0): ?>
                                                <?php while ($distributed = $distributedResult->fetch_assoc()): ?>
                                                    <?php
                                                    // Fetch and count the number of members for each distributed evacuee
                                                    $membersQuery = "
                        SELECT CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name, ' ', m.extension_name) AS member_name
                        FROM members m
                        WHERE m.evacuees_id = ?
                    ";
                                                    $membersStmt = $conn->prepare($membersQuery);
                                                    $membersStmt->bind_param("i", $distributed['evacuees_id']);
                                                    $membersStmt->execute();
                                                    $membersResult = $membersStmt->get_result();
                                                    $memberCount = $membersResult->num_rows;

                                                    // Adjust unit display based on quantity
                                                    $displayUnit = ($distributed['quantity'] > 1) ? $distributed['supply_unit'] . 's' : $distributed['supply_unit'];
                                                    ?>
                                                    <tr onclick="toggleCheckboxRe(this)">
                                                        <td class="selectName" style="text-align: center;">
                                                            <input type="checkbox" name="evacuee_ids[]"
                                                                value="<?php echo $distributed['evacuees_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($distributed['family_head']); ?>">
                                                            <?php echo htmlspecialchars($distributed['family_head']); ?>
                                                        </td>
                                                        <td style="text-align: center;"><?php echo $memberCount; ?></td>
                                                        <td style="text-align: center;">
                                                            <?php echo htmlspecialchars($distributed['status']); ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo htmlspecialchars($distributed['date_received']); ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo htmlspecialchars($distributed['time_received']); ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo htmlspecialchars($distributed['quantity']); ?>
                                                            <?php echo htmlspecialchars($displayUnit); ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center;">No Distributed Records
                                                        Found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div class="receiveBtn-container">
                                        <button type="button" id="selectAllRedistributeBtn" class="redistributeBtn"
                                            onclick="selectAllRedistribute()">Select All</button>
                                        <button class="redistributeBtn"
                                            onclick="confirmRedistribute()">Redistribute</button>
                                    </div>
                                </form>

                                <script>


                                    // const supplyId = <?php echo json_encode($supplyId); ?>;

                                    function toggleCheckboxRe(row) {
                                        const checkbox = row.querySelector('input[type="checkbox"]');
                                        checkbox.checked = !checkbox.checked;
                                        updateSelectAllRedistributeButtonText();
                                    }

                                    function selectAllRedistribute() {
                                        const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
                                        const selectAllRedistributeBtn = document.getElementById('selectAllRedistributeBtn');
                                        const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);

                                        checkboxes.forEach(checkbox => checkbox.checked = !allSelected);

                                        // Toggle button text
                                        selectAllRedistributeBtn.textContent = allSelected ? 'Select All' : 'Unselect All';
                                    }

                                    function updateSelectAllRedistributeButtonText() {
                                        const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
                                        const selectAllRedistributeBtn = document.getElementById('selectAllRedistributeBtn');
                                        const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);

                                        // Update button text based on current selection state
                                        selectAllRedistributeBtn.textContent = allSelected ? 'Unselect All' : 'Select All';
                                    }

                                    function confirmRedistribute() {
                                        const selectedEvacuees = Array.from(document.querySelectorAll('input[name="evacuee_ids[]"]:checked'))
                                            .map(checkbox => ({
                                                id: checkbox.value,
                                                name: checkbox.getAttribute('data-name')
                                            }));

                                        if (selectedEvacuees.length === 0) {
                                            Swal.fire({
                                                title: "No Evacuees Selected",
                                                text: "Please select at least one evacuee.",
                                                icon: "warning",
                                                confirmButtonText: "OK"
                                            });
                                            return;
                                        }

                                        const quantityInputs = selectedEvacuees.map(evacuee =>
                                            `<div>
            <label>${evacuee.name}: </label>
            <input type="number" id="quantity_${evacuee.id}" placeholder="Enter quantity" required>
        </div>`
                                        ).join('');

                                        Swal.fire({
                                            title: 'Confirm Redistribution',
                                            html: `<div>${quantityInputs}</div>`,
                                            showCancelButton: true,
                                            confirmButtonText: 'Redistribute',
                                            preConfirm: () => {
                                                return selectedEvacuees.map(evacuee => ({
                                                    evacuee_id: evacuee.id,
                                                    quantity: document.getElementById(`quantity_${evacuee.id}`).value
                                                }));
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const distributionData = {
                                                    evacuee_data: result.value,
                                                    supply_id: supplyId
                                                };

                                                fetch('../endpoints/redistribute_supply.php', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json' },
                                                    body: JSON.stringify(distributionData)
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire("Success", "Supplies redistributed successfully!", "success")
                                                                .then(() => {
                                                                    location.reload();
                                                                });
                                                        } else {
                                                            Swal.fire("Error", "Not Enough Supplies.", "error");
                                                        }
                                                    })
                                                    .catch(error => console.error('Error:', error));
                                            }
                                        });
                                    }



                                </script>


                            </div>

                        </div>

                    </div>




                </div>
            </div>
        </main>

    </div>

    <!-- the checkbox will be checked when the tr is clicked -->
    <script>
        const supplyId = <?php echo json_encode($supplyId); ?>;

        function toggleCheckbox(row) {
            const checkbox = row.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            updateSelectAllButtonText();
        }
        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allSelected);

            // Toggle button text
            selectAllBtn.textContent = allSelected ? 'Select All' : 'Unselect All';
        }
        function updateSelectAllButtonText() {
            const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);

            // Update button text based on current selection state
            selectAllBtn.textContent = allSelected ? 'Unselect All' : 'Select All';
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allSelected);

            // Toggle button text
            selectAllBtn.textContent = allSelected ? 'Select All' : 'Unselect All';
        }

        function confirmDistribute() {
            const selectedEvacuees = Array.from(document.querySelectorAll('input[name="evacuee_ids[]"]:checked'))
                .map(checkbox => ({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name')
                }));

            if (selectedEvacuees.length === 0) {
                Swal.fire({
                    title: "No Evacuees Selected",
                    text: "Please select at least one evacuee.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }

            const quantityInputs = selectedEvacuees.map(evacuee =>
                `<div>
            <label>${evacuee.name}: </label>
            <input type="number" id="quantity_${evacuee.id}" placeholder="Enter quantity" required>
        </div>`
            ).join('');

            Swal.fire({
                title: 'Confirm Distribution',
                html: `
            <div>
                ${quantityInputs}
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Distribute',
                preConfirm: () => {
                    return selectedEvacuees.map(evacuee => ({
                        evacuee_id: evacuee.id,
                        quantity: document.getElementById(`quantity_${evacuee.id}`).value
                    }));
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const distributionData = {
                        evacuee_data: result.value,
                        supply_id: supplyId
                    };

                    fetch('../endpoints/distribute_supply.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(distributionData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Success", "Supplies distributed successfully!", "success")
                                    .then(() => {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire("Error", "Not Enough Supplies.", "error");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }

        // Get the elements
        const distributeBtn = document.querySelector('.showDistributed');
        const distributeTable = document.querySelector('.distributedTable');
        const distributeBtnShow = document.querySelector('.distributeBtn-container');

        const receiveBtn = document.querySelector('.showReceived');
        const receiveTable = document.querySelector('.receivedTable');
        const receiveBtnShow = document.querySelector('.receiveBtn-container');

        distributeBtn.addEventListener('click', function () {
            // receiveTable.style.visibility = 'hidden';
            // distributeTable.style.visibility = 'visible';

            receiveTable.style.display = 'none';  // Hide the received table
            distributeTable.style.display = 'table';  // Show the distributed table (use 'table' for correct display)

            distributeBtnShow.style.display = 'block';
            receiveBtnShow.style.display = 'none';

        });

        receiveBtn.addEventListener('click', function () {
            // distributeTable.style.visibility = 'hidden';
            // receiveTable.style.visibility = 'visible';

            distributeTable.style.display = 'none';  // Hide the distributed table
            receiveTable.style.display = 'table';  // Show the received table (use 'table' for correct display)

            receiveBtnShow.style.display = 'block';
            distributeBtnShow.style.display = 'none';

        });

        $('.mainBtn').on('click', function () {
            Swal.fire({
                title: "Save Changes?",
                text: "",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                customClass: {
                    popup: 'custom-swal-popup' //to customize the style
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Success!",
                        text: "",
                        icon: "success",
                        customClass: {
                            popup: 'custom-swal-popup'
                        }
                    });
                }
            });

        })
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/ecNavbarSupply.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- filter active -->
    <script>
        const supplyFiler = document.querySelectorAll('.supplyTable li');

        supplyFiler.forEach(item => {
            item.addEventListener('click', function () {
                //check if the item is clicked
                if (this.classList.contains('active')) {
                    //if active, remove active class
                    this.classList.remove('active');
                } else {
                    // if not, first remove active
                    supplyFiler.forEach(i => i.classList.remove('active'));

                    // then add actgive if clicked
                    this.classList.add('active');
                }
            })
        })
    </script>







</body>

</html>