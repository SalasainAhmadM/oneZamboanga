<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Font Awesome-->
    <link rel="stylesheet" href="../../assets/fontawesome/all.css">
    <link rel="stylesheet" href="../../assets/fontawesome/fontawesome.min.css">
    <!--styles-->

    <link rel="icon" href="../../assets/img/zambo.png">

    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/utils/ecenter.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">



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
                            <a href="barangayStatus.php">Barangay</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">View Account</a> -->
                        </div>




                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <div class="statusHeader">
                        <h3>Barangay in Zamboanga City</h3>
                    </div>

                    <div class="statusCard-container">

                        <div class="statusCard" onclick="window.location.href='barangayEC.php'">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tetuan</h4>
                            </a>


                            <div class="modal-ecContainer">
                                <a href="barangayEC.php" class="ecTitle">
                                    <h5 class="totalEc">5 Evacuation Centers</h5>
                                </a>

                                <div class="ecList-modal">
                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Barangay Hall</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot yellow"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>
                                </div>
                            </div>


                            <div class="bgAdmin">
                                <h4>Mark Larenz Tabotabo</h5>
                                    <p>Admin</p>
                            </div>
                        </div>

                        <div class="statusCard">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tetuan</h4>
                            </a>


                            <div class="modal-ecContainer">
                                <a href="#" class="ecTitle">
                                    <h5 class="totalEc">5 Evacuation Centers</h5>
                                </a>

                                <div class="ecList-modal">
                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Barangay Hall</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot yellow"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>
                                </div>
                            </div>


                            <div class="bgAdmin">
                                <h4>Mark Larenz Tabotabo</h5>
                                    <p>Admin</p>
                            </div>
                        </div>

                        <div class="statusCard">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tetuan</h4>
                            </a>


                            <div class="modal-ecContainer">
                                <a href="#" class="ecTitle">
                                    <h5 class="totalEc">5 Evacuation Centers</h5>
                                </a>

                                <div class="ecList-modal">
                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Barangay Hall</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>
                                </div>
                            </div>


                            <div class="bgAdmin">
                                <h4>Mark Larenz Tabotabo</h5>
                                    <p>Admin</p>
                            </div>
                        </div>

                        <div class="statusCard">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tetuan</h4>
                            </a>


                            <div class="modal-ecContainer">
                                <a href="#" class="ecTitle">
                                    <h5 class="totalEc">5 Evacuation Centers</h5>
                                </a>

                                <div class="ecList-modal">
                                    <div class="ecList">
                                        <div class="ecDot green"></div>
                                        <p class="ecName">Barangay Hall</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot yellow"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot red"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>

                                    <div class="ecList">
                                        <div class="ecDot yellow"></div>
                                        <p class="ecName">Tetuan Central School</p>
                                    </div>
                                </div>
                            </div>


                            <div class="bgAdmin">
                                <h4>Mark Larenz Tabotabo</h5>
                                    <p>Admin</p>
                            </div>
                        </div>

                        <!-- <div class="statusCard">
                            <img class="barangayLogo" src="../../assets/img/zambo.png" alt="">

                            <a href="#">
                                <h4 class="statusBgname">Tugbugan</h4>
                            </a>

                            <a href="">
                                <h5 class="totalEc">315 Evacuation Centers</h5>
                            </a>

                            <div class="bgAdmin">
                                <h4>Jose Manalo</h5>
                                <p>Admin</p>
                            </div>
                        </div> -->







                    </div>


                </div>
            </div>
        </main>

    </div>


    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>