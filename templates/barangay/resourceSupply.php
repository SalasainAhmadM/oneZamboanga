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

$evacuationCenterId = $_GET['id'];  // Get the evacuation center ID from the URL parameter

// Fetch the evacuation center name and admin_id
$evacuationCenterSql = "SELECT name, admin_id FROM evacuation_center WHERE id = ?";
$evacuationCenterStmt = $conn->prepare($evacuationCenterSql);
$evacuationCenterStmt->bind_param("i", $evacuationCenterId);
$evacuationCenterStmt->execute();
$evacuationCenterResult = $evacuationCenterStmt->get_result();
$evacuationCenter = $evacuationCenterResult->fetch_assoc();
$adminId = $evacuationCenter['admin_id'];

// Fetch categories associated with the admin, including their IDs
$categorySql = "SELECT id, name FROM category WHERE admin_id = ?";
$categoryStmt = $conn->prepare($categorySql);
$categoryStmt->bind_param("i", $adminId);
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();

// Store categories in an array for reuse in different sections
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch supplies associated with the evacuation center, including the dynamic `quantity` calculation
$supplySql = "
    SELECT s.id, s.name, s.description, s.quantity, s.original_quantity, s.unit, s.image, s.category_id, s.approved,
           (s.quantity + COALESCE(SUM(st.quantity), 0)) AS total_quantity
    FROM supply s
    LEFT JOIN stock st ON s.id = st.supply_id
    WHERE s.evacuation_center_id = ?
    GROUP BY s.id
    ORDER BY s.approved ASC, s.name ASC";


$supplyStmt = $conn->prepare($supplySql);
$supplyStmt->bind_param("i", $evacuationCenterId);
$supplyStmt->execute();
$supplyResult = $supplyStmt->get_result();

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
    <link rel="stylesheet" href="../../assets/styles/utils/resources.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    /* select quantity category */
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



    /* manage category */
    .categoryCTA {
        background-color: var(--clr-slate600);
        color: var(--clr-white);
        border: none;
        outline: none;
        padding: .2em;
        transition: .3s;
        cursor: pointer;

        &:hover {
            background-color: var(--clr-dark);
        }
    }

    .listCategory {
        position: relative;
        display: none;
    }

    .closeManage {
        display: none;
        border: none;
        outline: none;
        background-color: transparent;

        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 9;

        i {
            font-size: var(--size-lg);
            font-weight: 600;
            color: var(--clr-slate600);
            cursor: pointer;

            &:hover {
                color: var(--clr-dark);
            }
        }
    }

    .supply-card {
        position: relative;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        margin: 10px;
    }

    .status-indicator {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 1px solid #fff;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .redirect-dropdown {
        margin-bottom: 5px;
        display: flex;
        justify-content: left;
    }

    #resource-switcher {
        padding: 8px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 50%;
        max-width: 180px;
        outline: none;
        appearance: none;
        background-color: #fff;
        cursor: pointer;
    }

    #resource-switcher:hover {
        border-color: #888;
    }
</style>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>
    Swal.fire({
        icon: '{$_SESSION['message_type']}',
        title: '{$_SESSION['message']}'
    }).then(() => {
        location.reload();
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
                            <a
                                href="viewEC.php?id=<?php echo $evacuationCenterId; ?>"><?php echo $evacuationCenter['name']; ?></a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resources.php?id=<?php echo $evacuationCenterId; ?>">Resource Management</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resourceSupply.php?id=<?php echo $evacuationCenterId; ?>">Supplies</a>
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">

                    <special-navbar></special-navbar>

                    <div class="redirect-dropdown">
                        <select id="resource-switcher" onchange="redirectToPage()">
                            <option value="resourceSupply.php" <?php echo basename($_SERVER['PHP_SELF']) === 'resourceSupply.php' ? 'selected' : ''; ?>>Resource
                                Supply</option>
                            <option value="resourceDistribution.php" <?php echo basename($_SERVER['PHP_SELF']) === 'resourceDistribution.php' ? 'selected' : ''; ?>>
                                Resource Distribution</option>
                        </select>
                    </div>

                    <script>
                        function redirectToPage() {
                            const selectedPage = document.getElementById('resource-switcher').value;
                            const params = new URLSearchParams({
                                id: "<?php echo htmlspecialchars($evacuationCenterId); ?>"
                            });
                            window.location.href = `${selectedPage}?${params.toString()}`;
                        }
                    </script>

                    <div class="supplySearch">
                        <input type="text" id="supply-search" placeholder="Search supply by name..."
                            onkeyup="filterSupplies()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <ul class="supply-filter">
                        <li class="active" data-category-id="all">All</li>
                        <?php foreach ($categories as $category): ?>
                            <li data-category-id="<?php echo htmlspecialchars($category['id']); ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </li>
                        <?php endforeach; ?>
                        <li class="addCategory" data-category-id="all" style="background-color: transparent;">
                            <label for="category-modal">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </label>
                            <input type="checkbox" id="category-modal" class="category-modal">
                            <div class="modalCategory">
                                <div class="categoryOption">
                                    <button class="supplyAdd">Add Supply</button>
                                </div>
                                <div class="categoryOption">
                                    <button class="categoryAdd">Add Category</button>
                                </div>
                                <div class="categoryOption">
                                    <button class="categoryManage">Manage Category</button>
                                </div>
                            </div>
                        </li>
                    </ul>



                    <!-- popup supply add -->
                    <div class="addForm-supply">
                        <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>
                        <form action="../endpoints/add_supply.php" method="post" enctype="multipart/form-data"
                            class="supplyForm">

                            <h3>Add Supply</h3>
                            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($adminId); ?>">
                            <input type="hidden" name="evacuation_center_id"
                                value="<?php echo htmlspecialchars($evacuationCenterId); ?>">
                            <input type="hidden" name="evacuation_center_name" id="evacuationCenterName"
                                value="<?php echo htmlspecialchars($evacuationCenter['name']); ?>" readonly>
                            <div class="addInput-wrapper">
                                <div class="add-input">
                                    <label for="supplyName">Supply Name:</label>
                                    <input type="text" name="name" id="supplyName" required>
                                </div>

                                <div class="add-input">
                                    <label for="category">Category:</label>
                                    <select name="category_id" id="category" required>
                                        <option value="">Select</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="add-input">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date" id="date" required>
                                </div>

                                <div class="add-input">
                                    <label for="time">Time:</label>
                                    <input type="time" name="time" id="time" required>
                                </div>

                                <div class="add-input">
                                    <label for="from">From:</label>
                                    <input type="text" name="from" id="from" required>
                                </div>

                                <div class="add-input">
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity" placeholder="Enter quantity"
                                        required>
                                </div>
                                <div class="add-input">
                                    <label for="unit">Unit:</label>
                                    <select name="unit" id="unit" id="unit" required>
                                        <option value="">Select</option>
                                        <option value="piece">Piece</option>
                                        <option value="pack">Pack</option>
                                    </select>
                                </div>

                                <div class="add-input">
                                    <label for="image">Add Photo:</label>
                                    <input type="file" name="image" id="image" required>
                                </div>

                                <div class="add-input">
                                    <label for="description">Description:</label>
                                    <input type="text" name="description" id="description" required>
                                </div>
                            </div>

                            <button type="button" class="mainBtn">Add Supply</button>
                        </form>

                    </div>

                    <!-- popup category add -->
                    <div class="addForm-category">
                        <form action="../endpoints/add_category.php" method="POST" class="categoryForm">
                            <button class="closeCategory"><i class="fa-solid fa-xmark"></i></button>
                            <h3>Add Category</h3>
                            <div class="addInput-wrapper">
                                <div class="add-input category">
                                    <label for="category">Category: </label>
                                    <input type="text" name="category" required>
                                </div>
                            </div>
                            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($adminId); ?>">
                            <input type="hidden" name="evacuation_center_id"
                                value="<?php echo htmlspecialchars($evacuationCenterId); ?>">
                            <button type="button" class="mainBtn category" style="width: 100%;">Add Category</button>
                        </form>


                        <table class="listCategory">
                            <thead>
                                <button class="closeManage"><i class="fa-solid fa-xmark"></i></button>
                                <tr>
                                    <th>Category</th>
                                    <th colspan="2" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td>
                                            <button class="categoryCTA"
                                                onclick="editCategory(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')">Edit</button>
                                        </td>
                                        <td>
                                            <button class="categoryCTA"
                                                onclick="deleteCategory(<?php echo $category['id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>



                    </div>
                    <div class="supply-wrapper">
                        <?php if ($supplyResult->num_rows > 0): ?>
                            <?php while ($supply = $supplyResult->fetch_assoc()): ?>
                                <?php
                                $currentQuantity = $supply['quantity'];
                                $originalQuantity = $supply['original_quantity'];

                                // Determine color status
                                if ($currentQuantity == 0) {
                                    $statusColor = "red";
                                } elseif ($currentQuantity <= 0.3 * $originalQuantity) {
                                    $statusColor = "yellow";
                                } elseif ($currentQuantity >= 0.7 * $originalQuantity) {
                                    $statusColor = "green";
                                } else {
                                    $statusColor = "yellow";
                                }

                                $imagePath = !empty($supply['image']) ? "../../assets/img/" . htmlspecialchars($supply['image']) : "../../assets/img/supplies.png";
                                ?>
                                <div class="supply-card" data-category="<?php echo htmlspecialchars($supply['category_id']); ?>"
                                    data-name="<?php echo strtolower(htmlspecialchars($supply['name'])); ?>">

                                    <!-- Status Indicator -->
                                    <div class="status-indicator" style="background-color: <?php echo $statusColor; ?>;"></div>

                                    <img class="supply-img" src="<?php echo $imagePath; ?>" alt="">
                                    <ul class="supply-info">
                                        <li>Name: <?php echo htmlspecialchars($supply['name']); ?></li>
                                        <li>Description: <?php echo htmlspecialchars($supply['description']); ?></li>
                                        <li>Quantity:
                                            <?php echo htmlspecialchars($supply['quantity']) . ' ' . htmlspecialchars($supply['unit']); ?>s
                                        </li>
                                    </ul>
                                    <?php if ($supply['approved'] == 1): ?>
                                        <a href="viewSupply.php?id=<?php echo htmlspecialchars($supply['id']); ?>"
                                            class="supply-btn">View Details</a>
                                    <?php else: ?>
                                        <a href="#" class="supply-btn approve-btn"
                                            data-id="<?php echo htmlspecialchars($supply['id']); ?>">Approve?</a>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No Supplies Yet.</p>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
        </main>

    </div>


    <!-- filter active -->
    <script>
        function filterSupplies() {
            const searchInput = document.getElementById('supply-search').value.toLowerCase();
            const supplyCards = document.querySelectorAll('.supply-card');

            supplyCards.forEach(card => {
                const supplyName = card.getAttribute('data-name');
                if (supplyName.includes(searchInput)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        const supplyFilter = document.querySelectorAll('.supply-filter li');
        const supplyCards = document.querySelectorAll('.supply-card');

        supplyFilter.forEach(item => {
            item.addEventListener('click', function () {
                // Remove active class from all filter items
                supplyFilter.forEach(i => i.classList.remove('active'));

                // Add active class to the clicked filter item
                this.classList.add('active');

                // Get the category ID from the clicked item
                const categoryId = this.getAttribute('data-category-id');

                // Filter supply cards based on the selected category
                supplyCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (categoryId === 'all' || cardCategory === categoryId) {
                        card.style.display = 'block'; // Show the card if it matches
                    } else {
                        card.style.display = 'none'; // Hide the card if it doesn't match
                    }
                });
            });
        });

    </script>


    <!-- select type of quantity -->
    <script>
        document.querySelector('.mainBtn').addEventListener('click', () => {
            Swal.fire({
                title: "Add Supply?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(document.querySelector('.supplyForm'));

                    $.ajax({
                        url: '../endpoints/add_supply.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire("Success!", "Supply successfully added.", "success");
                        },
                        error: function () {
                            Swal.fire("Error", "There was a problem adding the supply.", "error");
                        }
                    });
                }
            });
        });

        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function () {
                const supplyId = this.dataset.id;

                Swal.fire({
                    title: "Approve Supply?",
                    text: "This action will make the supply available.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../endpoints/supply_approve.php',
                            type: 'POST',
                            data: { supply_id: supplyId },
                            success: function (response) {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire("Approved!", "Supply has been approved.", "success").then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire("Error", res.error, "error");
                                }
                            },
                            error: function () {
                                Swal.fire("Error", "There was a problem approving the supply.", "error");
                            }
                        });
                    }
                });
            });
        });


    </script>

    <!-- sweetalerts import js -->
    <script src="../../includes/modals.js"></script>

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


    <script>

        const supplyBtn = document.querySelector('.supplyAdd');
        const categoryBtn = document.querySelector('.categoryAdd');
        const supplyForm = document.querySelector('.addForm-supply');
        const categoryForm = document.querySelector('.addForm-category');
        const closeForm = document.querySelector('.closeForm');
        const closeCategoryForm = document.querySelector('.closeCategory');
        const modalOption = document.querySelector('.category-modal');
        const body = document.querySelector('body'); // to get the body element


        const categoryManageBtn = document.querySelector('.categoryManage');
        const categoryHide = document.querySelector('.categoryForm');
        const viewManageCategory = document.querySelector('.listCategory');
        const closeManage = document.querySelector('.closeManage');


        // add supply
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



        // add category
        categoryBtn.addEventListener('click', function () {
            categoryForm.style.display = 'block';
            categoryHide.style.display = 'block';
            viewManageCategory.style.display = 'none';
            closeManage.style.display = 'none';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }

            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeCategoryForm.addEventListener('click', function () {
            categoryForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // view category
        categoryManageBtn.addEventListener('click', function () {
            categoryForm.style.display = 'block';
            categoryHide.style.display = 'none';
            viewManageCategory.style.display = 'block';
            closeManage.style.display = 'block';


            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }

            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeManage.addEventListener('click', function () {
            categoryForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });
    </script>

    <!-- sweetalert popup messagebox -->
    <script>


        $('.mainBtn.category').on('click', function () {
            Swal.fire({
                title: "Add Category?",
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
                    // Submit the form
                    $('.categoryForm').submit();
                }
            });
        });

    </script>




</body>

</html>