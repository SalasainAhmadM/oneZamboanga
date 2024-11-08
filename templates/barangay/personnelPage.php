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
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <button class="addBg-admin" onclick="window.location.href='addAccount.php'">
                            Create
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-personnel></special-personnel>
                    <!-- <div class="ecNavbar">
                        <ul>
                            <div class="navList">
                                <li><a href="viewEC.php">Overview</a></li>
                                <div class="indicator"></div>
                            </div>
                            <div class="navList">
                                <li class="active"><a href="evacueesPage.php">Evacuees</a></li>
                                <div class="indicator"></div>
                            </div>
                            <div class="navList">
                                <li><a href="resources.php">Resource Management</a></li>
                                <div class="indicator long"></div>
                            </div>

                            <div class="navList">
                                <li><a href="personnel.php">Personnel</a></li>
                                <div class="indicator mid"></div>
                            </div>

                            <div class="navList">
                                <li><a href="nearEC.php">Near Evacuation Center</a></li>
                                <div class="indicator long"></div>
                            </div>
                        </ul>
                    </div> -->


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

                                <!-- the modal or filter popup-->
                                <div class="modal">
                                    <div class="modal-content">
                                        <!-- <label for="modal-toggle" class="close">
                                            <i class="fa-solid fa-xmark"></i>
                                        </label> -->
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="kagawas">
                                                <label for="kagawad">Kagawad</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="sk">
                                                <label for="sk">SK</label>
                                            </div>
                                            <!-- <div class="option-content">
                                                <input type="checkbox" name="barangay" id="tugbungan">
                                                <label for="tugbungan">Tugbungan</label>
                                            </div> -->


                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="input_group">
                                <input type="search" placeholder="Search...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                        </section>

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>

                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Position</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <!-- <tr onclick="window.location.href='viewEvacuees.php'">
                                        <td>
                                            Mark Larenz Tabotabo
                                        </td>
                                        <td>09356234162</td>
                                        <td style="text-align: center;">10,000</td>
                                        <td>House Owner</td>
                                        <td><a href="viewEvacuees.php" class="view-action">View</a></td>
                                        <td style="text-align: center;"><a href="viewEvacuees.php" class="view-action">View</a></td>
                                    </tr> -->

                                    <tr onclick="window.location.href='workersProfile.php'">
                                        <td>
                                            Mark Larenz Tabotabo
                                        </td>
                                        <td>communityWorkers@gmail.com</td>
                                        <td>09356234162</td>
                                        <td style="text-align: center;">Kagawad</td>
                                        <td style="text-align: center;"><a href="workersProfile.php"
                                                class="view-action">View</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>

                        <div class="no-match-message">No matching data found</div>
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

    <script src="../../includes/personnelpageNav.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>