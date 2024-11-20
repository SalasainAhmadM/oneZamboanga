<?php
session_start();
require_once '../../connection/conn.php';
require_once '../../connection/auth.php';
validateSession('admin');

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
                            <a href="#">Prints Reports</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Evacuees</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->

                        <!-- <button class="addBg-admin" onclick="window.location.href='evacueesForm.php'">
                            Admit
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-personnel></special-personnel>



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
                                                <input type="checkbox" name="evacuees" id="tetuan">
                                                <label for="tetuan">Tetuan</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="evacuees" id="tugbungan">
                                                <label for="tugbungan">Tugbungan</label>
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

                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>

                                    <tr>
                                        <th>Family Head</th>
                                        <th>Contact #</th>
                                        <th style="text-align: center;">Number of members</th>
                                        <th style="text-align: center;">Barangay</th>
                                        <th style="text-align: center;">Date</th>
                                        <th>Calamity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

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