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
    // Calculate the total distributed quantity
    // Fetch the total distributed quantity from the distribute table
    $distributeSumQuery = "
SELECT SUM(quantity) AS total_distributed_quantity
FROM distribute
WHERE supply_id = ?
";
    $distributeSumStmt = $conn->prepare($distributeSumQuery);
    $distributeSumStmt->bind_param("i", $supplyId);
    $distributeSumStmt->execute();
    $distributeSumResult = $distributeSumStmt->get_result();

    $totalDistributed = 0;
    if ($distributeSumResult->num_rows > 0) {
        $distributeSumRow = $distributeSumResult->fetch_assoc();
        $totalDistributed = $distributeSumRow['total_distributed_quantity'] ? $distributeSumRow['total_distributed_quantity'] : 0;
    }

    // Calculate the remaining quantity (for consistency)
    $remainingQuantity = $totalQuantity - $totalDistributed;
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

// Fetch the distribute values that match the supply id
$distributeQuery = "
    SELECT 
        distribute.distributor_id,
        distribute.distributor_type,
        distribute.evacuees_id,
        DATE_FORMAT(distribute.date, '%m-%d-%Y') AS date,
        DATE_FORMAT(distribute.date, '%h:%i %p') AS time,
        CASE 
            WHEN supply.unit = 'piece' AND distribute.quantity <= 1 THEN CONCAT(distribute.quantity, ' piece')
            WHEN supply.unit = 'piece' AND distribute.quantity > 1 THEN CONCAT(distribute.quantity, ' pieces')
            WHEN supply.unit = 'pack' AND distribute.quantity <= 1 THEN CONCAT(distribute.quantity, ' pack')
            WHEN supply.unit = 'pack' AND distribute.quantity > 1 THEN CONCAT(distribute.quantity, ' packs')
            ELSE CONCAT(distribute.quantity, ' units')
        END AS quantity,
        CASE 
            WHEN distribute.distributor_type = 'admin' THEN CONCAT(admin.first_name, ' ', admin.middle_name, ' ', admin.last_name)
            WHEN distribute.distributor_type = 'worker' THEN CONCAT(worker.first_name, ' ', worker.middle_name, ' ', worker.last_name)
            ELSE 'Unknown'
        END AS distributed_by,
        CONCAT(evacuees.first_name, ' ', evacuees.middle_name, ' ', evacuees.last_name) AS distributed_to
    FROM distribute
    LEFT JOIN admin ON distribute.distributor_id = admin.id AND distribute.distributor_type = 'admin'
    LEFT JOIN worker ON distribute.distributor_id = worker.id AND distribute.distributor_type = 'worker'
    LEFT JOIN evacuees ON distribute.evacuees_id = evacuees.id
    LEFT JOIN supply ON distribute.supply_id = supply.id
    WHERE distribute.supply_id = ?
";

$distributeStmt = $conn->prepare($distributeQuery);
$distributeStmt->bind_param("i", $supplyId);
$distributeStmt->execute();
$distributeResult = $distributeStmt->get_result();
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



    <!-- quantity category -->
    <style>
        .selectQuantity {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
        }

        .perPiece,
        .perPack {
            background-color: var(--clr-slate600);
            color: var(--clr-white);
            padding-inline: .5em;
            border-radius: .5em;
            font-size: var(--size-sm);
            transition: .3s;
            cursor: pointer;

            &:hover {
                background-color: var(--clr-dark);
            }
        }

        .piece {
            display: none;
        }

        .pack {
            display: none;
        }

        .status-indicator {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            left: 10px;
            border: 2px solid #4b5563;

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
                            <a href="viewEC.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">
                                <?php echo htmlspecialchars($evacuationCenterName); ?>
                            </a>

                            <!-- next page -->
                            <i class=" fa-solid fa-chevron-right"></i>
                            <a href="resources.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">Resource
                                Management</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a
                                href="resourceSupply.php?id=<?php echo htmlspecialchars($evacuationCenterId); ?>">Supplies</a>
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

                    <div class="viewSupply-container">
                        <div class="supply-cta">
                            <label for="supply-toggle" class="supply-button">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </label>
                            <input type="checkbox" name="" id="supply-toggle" class="supply-toggle">

                            <div class="supply-modal" data-supply-id="<?php echo $supplyId; ?>">
                                <div class="cta-options" style="text-align: center;">
                                    <button class="supplyStock">Add Stock</button>
                                    <button class="supplyAdd">Edit</button>
                                    <button class="supplyDelete">Delete</button>
                                </div>
                            </div>

                        </div>



                        <!-- add supply stock -->
                        <div class="addStock-supply">
                            <button class="closeStock"><i class="fa-solid fa-xmark"></i></button>
                            <form action="" class="supplyForm">
                                <h3>Add Stock</h3>
                                <input type="hidden" name="supply_id" value="<?php echo $supplyId; ?>">

                                <div class="addInput-wrapper">
                                    <div class="add-input">
                                        <label for="">Date: </label>
                                        <input type="date" name="date" required>
                                    </div>

                                    <div class="add-input">
                                        <label for="">Time: </label>
                                        <input type="time" name="time" required>
                                    </div>

                                    <div class="add-input">
                                        <label for="">From: </label>
                                        <input type="text" name="from" required>
                                    </div>

                                    <div class="add-input">
                                        <label for="quantityStock">Quantity:</label>
                                        <input type="number" name="quantityStock" id="quantityStock"
                                            placeholder="Enter quantity" required>
                                    </div>
                                    <div class="add-input">
                                        <label for="unitStock">Unit:</label>
                                        <select name="unitStock" id="unitStock" required>
                                            <option value="piece" <?php if ($unit == 'piece')
                                                echo 'selected'; ?>>Piece
                                            </option>
                                            <option value="pack" <?php if ($unit == 'pack')
                                                echo 'selected'; ?>>Pack
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <button class="mainBtn" id="stocks">Confirm</button>
                            </form>
                        </div>




                        <!-- edit supply -->
                        <div class="addForm-supply">
                            <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>
                            <form action="" class="supplyForm">
                                <h3>Edit Supply</h3>
                                <div class="addInput-wrapper">
                                    <div class="add-input">
                                        <label for="supplyName">Supply Name:</label>
                                        <input type="text" id="supplyName"
                                            value="<?php echo htmlspecialchars($supplyName); ?>">
                                    </div>

                                    <div class="add-input">
                                        <label for="category">Category:</label>
                                        <select id="category">
                                            <option value="">Select</option>
                                            <?php
                                            // Fetch all categories from the database
                                            $categoryQuery = "SELECT id, name FROM category";
                                            $categoryResult = $conn->query($categoryQuery);

                                            while ($category = $categoryResult->fetch_assoc()) {
                                                $selected = ($category['name'] == $categoryName) ? 'selected' : '';
                                                echo "<option value='" . $category['id'] . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="add-input">
                                        <label for="image">Change Photo:</label>
                                        <input type="file" id="image">
                                    </div>

                                    <div class="add-input">
                                        <label for="description">Description:</label>
                                        <input type="text" id="description"
                                            value="<?php echo htmlspecialchars($description); ?>">
                                    </div>

                                    <div class="add-input">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" id="quantity"
                                            value="<?php echo htmlspecialchars($quantity); ?>">
                                    </div>

                                    <div class="add-input">
                                        <label for="unit">Unit:</label>
                                        <select id="unit">
                                            <option value="piece" <?php if ($unit == 'piece')
                                                echo 'selected'; ?>>Piece
                                            </option>
                                            <option value="pack" <?php if ($unit == 'pack')
                                                echo 'selected'; ?>>Pack
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <button type="button" class="mainBtn">Save</button>
                            </form>
                        </div>



                        <?php
                        // Determine status color based on quantity and original quantity
                        if ($totalQuantity == 0) {
                            $statusColor = "red";
                        } elseif ($totalQuantity <= 0.3 * $quantityOrig) {
                            $statusColor = "yellow";
                        } elseif ($totalQuantity >= 0.7 * $quantityOrig) {
                            $statusColor = "green";
                        } else {
                            $statusColor = "yellow";
                        }
                        ?>

                        <div class="supplyTop" style="position: relative;">
                            <!-- Status color indicator -->
                            <div class="status-indicator" style="background-color: <?php echo $statusColor; ?>;"></div>

                            <!-- Supply details -->
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
                                <li class="showReceived active">Received</li>
                                <li class="showDistributed">Distributed</li>
                            </ul>

                            <div class="supplyTable-container">
                                <table class="receivedTable">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>Date Received</th>
                                            <th>Time Received</th>
                                            <th>Current Quantity</th>
                                            <th>Origin Quanity</th>
                                            <!-- <th>Distributed</th> -->
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        // Fetch the initial supply record
                                        $supplyQuery = "
        SELECT `from`, date, time, quantity, original_quantity, unit
        FROM supply
        WHERE id = ?
    ";
                                        $supplyStmt = $conn->prepare($supplyQuery);
                                        $supplyStmt->bind_param("i", $supplyId);
                                        $supplyStmt->execute();
                                        $supplyResult = $supplyStmt->get_result();

                                        if ($supplyResult->num_rows > 0) {
                                            $supplyRow = $supplyResult->fetch_assoc();
                                            $distributed = $supplyRow['original_quantity'] - $supplyRow['quantity'];
                                            $unit = htmlspecialchars($supplyRow['unit']);

                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($supplyRow['from']) . "</td>";
                                            echo "<td>" . htmlspecialchars(date("m-d-Y", strtotime($supplyRow['date']))) . "</td>";
                                            echo "<td>" . htmlspecialchars(date("h:i A", strtotime($supplyRow['time']))) . "</td>";
                                            echo "<td>" . htmlspecialchars($supplyRow['quantity'] . " " . $unit . ($supplyRow['quantity'] > 1 ? "s" : "")) . "</td>";
                                            echo "<td>" . htmlspecialchars($supplyRow['original_quantity'] . " " . $unit . ($supplyRow['original_quantity'] > 1 ? "s" : "")) . "</td>";
                                            // echo "<td>" . htmlspecialchars($distributed . " " . $unit . ($distributed > 1 ? "s" : "")) . "</td>";
                                            echo "</tr>";
                                        } else {
                                            echo "<tr><td colspan='5'>Initial supply data not available.</td></tr>";
                                        }

                                        $supplyStmt->close();

                                        // Fetch the stock history related to the supply ID
                                        $historyQuery = "
        SELECT `from`, date, time, quantity, original_quantity, unit
        FROM stock
        WHERE supply_id = ?
        ORDER BY date DESC, time DESC
    ";
                                        $historyStmt = $conn->prepare($historyQuery);
                                        $historyStmt->bind_param("i", $supplyId);
                                        $historyStmt->execute();
                                        $historyResult = $historyStmt->get_result();

                                        if ($historyResult->num_rows > 0) {
                                            while ($historyRow = $historyResult->fetch_assoc()) {
                                                $distributed = $historyRow['original_quantity'] - $historyRow['quantity'];
                                                $unit = htmlspecialchars($historyRow['unit']);

                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($historyRow['from']) . "</td>";
                                                echo "<td>" . htmlspecialchars(date("m-d-Y", strtotime($historyRow['date']))) . "</td>";
                                                echo "<td>" . htmlspecialchars(date("h:i A", strtotime($historyRow['time']))) . "</td>";
                                                echo "<td>" . htmlspecialchars($historyRow['quantity'] . " " . $unit . ($historyRow['quantity'] > 1 ? "s" : "")) . "</td>";
                                                echo "<td>" . htmlspecialchars($historyRow['original_quantity'] . " " . $unit . ($historyRow['original_quantity'] > 1 ? "s" : "")) . "</td>";
                                                // echo "<td>" . htmlspecialchars($distributed . " " . $unit . ($distributed > 1 ? "s" : "")) . "</td>";
                                                echo "</tr>";
                                            }
                                        }

                                        $historyStmt->close();
                                        ?>
                                    </tbody>





                                    <table class="distributedTable">
                                        <thead>
                                            <tr>
                                                <th>Distributed by</th>
                                                <th>Distributed to</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            if ($distributeResult->num_rows > 0) {
                                                while ($distributeRow = $distributeResult->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($distributeRow['distributed_by']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($distributeRow['distributed_to']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($distributeRow['date']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($distributeRow['time']) . "</td>";
                                                    echo "<td>" . '-' . htmlspecialchars($distributeRow['quantity']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No distribution records available.</td></tr>";
                                            }

                                            $distributeStmt->close();
                                            ?>
                                        </tbody>
                                    </table>

                            </div>

                        </div>

                    </div>




                </div>
            </div>
        </main>

    </div>

    <!-- message pop up for edit supply -->
    <script>
        $('.mainBtn').on('click', function () {
            let supplyId = <?php echo json_encode($supplyId); ?>;
            let supplyName = $('#supplyName').val();
            let category = $('#category').val();
            let description = $('#description').val();
            let quantity = $('#quantity').val();
            let unit = $('#unit').val();
            let image = $('#image')[0].files[0];

            Swal.fire({
                title: "Save Changes?",
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
                    let formData = new FormData();
                    formData.append("id", supplyId);
                    formData.append("name", supplyName);
                    formData.append("category_id", category);
                    formData.append("description", description);
                    formData.append("quantity", quantity);
                    formData.append("unit", unit);
                    if (image) {
                        formData.append("image", image);
                    }

                    $.ajax({
                        url: '../endpoints/edit_supply.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            let res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: "The supply has been updated.",
                                    icon: "success",
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error", res.error, "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "An error occurred while updating the supply.", "error");
                        }
                    });
                }
            });
        });



    </script>
    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import navbar -->
    <script src="../../includes/ecNavbarSupply.js"></script>

    <script src="../../includes/logout.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- select type of quantity -->
    <script>
        const selectQuantity = document.querySelector('.selectQuantity');
        const perPieceBtn = document.querySelector('.perPiece');
        const perPackBtn = document.querySelector('.perPack');

        const inputPiece = document.querySelector('.piece');
        const inputPack = document.querySelector('.pack');

        perPieceBtn.addEventListener('click', function () {
            selectQuantity.style.display = 'none';
            inputPiece.style.display = 'block';
        })

        perPackBtn.addEventListener('click', function () {
            selectQuantity.style.display = 'none';
            inputPack.style.display = 'block';
        });
    </script>

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


    <!-- display tables -->
    <script>
        // Get the elements
        const distributeBtn = document.querySelector('.showDistributed');
        const distributeTable = document.querySelector('.distributedTable');

        const receiveBtn = document.querySelector('.showReceived');
        const receiveTable = document.querySelector('.receivedTable');

        distributeBtn.addEventListener('click', function () {
            // receiveTable.style.visibility = 'hidden';
            // distributeTable.style.visibility = 'visible';

            receiveTable.style.display = 'none';  // Hide the received table
            distributeTable.style.display = 'table';  // Show the distributed table (use 'table' for correct display)

        });

        receiveBtn.addEventListener('click', function () {
            // distributeTable.style.visibility = 'hidden';
            // receiveTable.style.visibility = 'visible';

            distributeTable.style.display = 'none';  // Hide the distributed table
            receiveTable.style.display = 'table';  // Show the received table (use 'table' for correct display)

        });

    </script>





    <script>

        const supplyBtn = document.querySelector('.supplyAdd');
        const supplyForm = document.querySelector('.addForm-supply');
        const closeForm = document.querySelector('.closeForm');

        const stockBtn = document.querySelector('.supplyStock');
        const stockForm = document.querySelector('.addStock-supply');
        const closeStock = document.querySelector('.closeStock');


        const modalOption = document.querySelector('.supply-toggle');
        const body = document.querySelector('body'); // to get the body element
        const deleteOption = document.querySelector('.supplyDelete');

        // edit supply
        supplyBtn.addEventListener('click', function () {
            supplyForm.style.display = 'block';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }

            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeForm.addEventListener('click', function () {
            supplyForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // add stock
        stockBtn.addEventListener('click', function () {
            stockForm.style.display = 'block';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }

            body.classList.add('body-overlay'); // add the overlay class to the body

        });
        closeStock.addEventListener('click', function () {
            stockForm.style.display = 'none'
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // delete supply
        deleteOption.addEventListener('click', function () {
            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
        })
    </script>

    <!-- ====sweetalert popup messagebox====== -->




    <!-- message pop up for add stock -->
    <script>
        $('#stocks').on('click', function (event) {
            event.preventDefault(); // Prevent form submission

            Swal.fire({
                title: "Add stock(s)?",
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
                    let formData = new FormData(document.querySelector('.supplyForm'));
                    $.ajax({
                        url: '../endpoints/add_stock.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            let res = JSON.parse(response);
                            if (res.status === "success") {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.message,
                                    icon: "success",
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: res.message,
                                    icon: "error",
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });

    </script>


    <!-- message pop up for delete btn -->
    <script>
        $('.supplyDelete').on('click', function () {
            // Assuming supplyId and evacuationCenterId are available from PHP
            let supplyId = <?php echo json_encode($supplyId); ?>;
            let evacuationCenterId = <?php echo json_encode($evacuationCenterId); ?>;

            Swal.fire({
                title: "Delete Supply?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                customClass: {
                    popup: 'custom-swal-popup' // Customize style if needed
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete the supply
                    $.ajax({
                        url: '../endpoints/delete_supply.php',
                        type: 'POST',
                        data: { id: supplyId },
                        success: function (response) {
                            let res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "The supply has been deleted.",
                                    icon: "success",
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                }).then(() => {
                                    // Redirect to resourceSupply.php with evacuationCenterId after success
                                    window.location.href = "resourceSupply.php?id=" + evacuationCenterId;
                                });
                            } else {
                                Swal.fire("Error", res.error, "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "An error occurred while deleting the supply.", "error");
                        }
                    });
                }
            });
        });

    </script>




</body>

</html>