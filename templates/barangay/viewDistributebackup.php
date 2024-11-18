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

    // Fetch evacuees for the evacuation center
    $evacueesQuery = "
        SELECT 
            e.id AS evacuee_id,
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.extension_name) AS family_head,
            e.status
        FROM 
            evacuees e
        WHERE 
            e.evacuation_center_id = ?
    ";
    $evacueesStmt = $conn->prepare($evacueesQuery);
    $evacueesStmt->bind_param("i", $evacuationCenterId);
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
        CONCAT(evacuees.first_name, ' ', evacuees.middle_name, ' ', evacuees.last_name) AS family_head,
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

$evacueeData = [];
while ($evacueeRow = $evacueeResult->fetch_assoc()) {
    $evacueeId = $evacueeRow['evacuees_id'];

    $memberQuery = "
        SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name, IF(extension_name != '', CONCAT(' ', extension_name), '')) AS member_name
        FROM members
        WHERE evacuees_id = ?
    ";
    $memberStmt = $conn->prepare($memberQuery);
    $memberStmt->bind_param("i", $evacueeId);
    $memberStmt->execute();
    $memberResult = $memberStmt->get_result();

    $members = [];
    while ($memberRow = $memberResult->fetch_assoc()) {
        $members[] = $memberRow['member_name'];
    }
    $memberCount = count($members);

    $evacueeData[] = [
        'distribute_id' => $evacueeRow['distribute_id'],
        'family_head' => $evacueeRow['family_head'],
        'member_count' => $memberCount,
        'members' => $members,
        'status' => $evacueeRow['status'],
        'date_received' => $evacueeRow['date_received'],
        'time_received' => $evacueeRow['time_received'],
        'quantity' => $evacueeRow['quantity'],
    ];

    $memberStmt->close();
}
$evacueeStmt->close();
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
                                        <button type="button" class="distributeBtn" onclick="selectAll()">Select
                                            All</button>
                                        <button type="button" class="distributeBtn"
                                            onclick="confirmDistribute()">Distribute</button>
                                    </div>
                                </form>


                                <form action="javascript:void(0);">
                                    <table class="receivedTable sent">
                                        <thead>
                                            <tr>
                                                <th>Family Head</th>
                                                <th style="text-align: center;">Number of members</th>
                                                <th>Status</th>
                                                <th>Date Received</th>
                                                <th>Time Received</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($evacueeData as $evacuee): ?>
                                                <tr onclick="toggleCheckboxRe(this)">
                                                    <td class="selectName">
                                                        <input type="checkbox" id="donate"
                                                            value="<?= htmlspecialchars($evacuee['distribute_id']); ?>">
                                                        <?= htmlspecialchars($evacuee['family_head']); ?>
                                                    </td>
                                                    <td class="ecMembers" style="text-align: center;">
                                                        <?= $evacuee['member_count']; ?>
                                                        <ul class="viewMembers" style="text-align: left;">
                                                            <?php foreach ($evacuee['members'] as $member): ?>
                                                                <li><?= htmlspecialchars($member); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </td>
                                                    <td><?= htmlspecialchars($evacuee['status']); ?></td>
                                                    <td><?= htmlspecialchars($evacuee['date_received']); ?></td>
                                                    <td><?= htmlspecialchars($evacuee['time_received']); ?></td>
                                                    <td><?= htmlspecialchars($evacuee['quantity']); ?>s</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="receiveBtn-container">
                                        <button class="redistributeBtn" onclick="selectAllRedistribute()">Select
                                            All</button>
                                        <button class="redistributeBtn" onclick="confirmRedistribute()">Return
                                            Supplies</button>
                                    </div>
                                </form>

                                <script>
                                    // redistribute 
                                    // const supplyId = <?php echo json_encode($supplyId); ?>;

                                    function toggleCheckboxRe(row) {
                                        const checkbox = row.querySelector('input[type="checkbox"]');
                                        checkbox.checked = !checkbox.checked;
                                    }

                                    function selectAllRedistribute() {
                                        const checkboxes = document.querySelectorAll('.receivedTable input[type="checkbox"]');
                                        checkboxes.forEach(checkbox => checkbox.checked = !checkbox.checked);
                                    }

                                    function confirmRedistribute() {
                                        const selectedEvacuees = Array.from(document.querySelectorAll('.receivedTable input[type="checkbox"]:checked'))
                                            .map(checkbox => ({
                                                distribute_id: checkbox.value,
                                                name: checkbox.closest('tr').querySelector('.selectName').textContent.trim()
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
                <input type="number" id="quantity_${evacuee.distribute_id}" placeholder="Enter quantity" required>
            </div>`
                                        ).join('');

                                        Swal.fire({
                                            title: 'Confirm Returning of Supplies',
                                            html: `<div>${quantityInputs}</div>`,
                                            showCancelButton: true,
                                            confirmButtonText: 'Return',
                                            preConfirm: () => {
                                                return selectedEvacuees.map(evacuee => {
                                                    const quantity = document.getElementById(`quantity_${evacuee.distribute_id}`).value;
                                                    if (!quantity) {
                                                        Swal.showValidationMessage("Please enter all quantities");
                                                        return false;
                                                    }
                                                    return {
                                                        distribute_id: evacuee.distribute_id,
                                                        quantity: quantity
                                                    };
                                                });
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const redistributionData = {
                                                    evacuee_data: result.value,
                                                    supply_id: supplyId
                                                };

                                                fetch('../endpoints/return_supply.php', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json' },
                                                    body: JSON.stringify(redistributionData)
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire("Success", "Supplies returned successfully!", "success")
                                                                .then(() => location.reload());
                                                        } else {
                                                            Swal.fire("Error", data.message || "Error in returning.", "error");
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        Swal.fire("Error", "An error occurred. Check console for details.", "error");
                                                    });
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
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="evacuee_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = !checkbox.checked);
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