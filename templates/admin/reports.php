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
                            <a href="barangayAcc.php">Reports</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create new barangay admin account</a> -->
                        </div>

                        <!-- <button class="profile-btn" id="profile-btn">
                            <i class="fa-solid fa-user-plus"></i>
                            <img src="/assets/img/hero.jpg">
                        </button> -->
                        <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
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

                            <!-- the modal or filter popup-->
                            <div class="modal">
                                <div class="modal-content">
                                    <!-- <label for="modal-toggle" class="close">
                                        <i class="fa-solid fa-xmark"></i>
                                    </label> -->
                                    <div class="filter-option">
                                        <div class="option-content">
                                            <input type="checkbox" name="barangay" id="active">
                                            <label for="active">Active</label>
                                        </div>
                                        <div class="option-content">
                                            <input type="checkbox" name="barangay" id="inactive">
                                            <label for="inactive">Inactive</label>
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
                                    <!-- <th>Id</th> -->
                                    <th>Barangay</th>
                                    <th>Full Name</th>
                                    <th>Contact Info</th>
                                    <th>Email</th>
                                    <th style="text-align: center;">Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr onclick="window.location.href='viewProfile.php'">
                                    <!-- <td>1</td> -->
                                    <td>
                                        <!-- <div class="relative">
                                            <img src="../../assets/img/zambo.png" alt="myImg">Tetuan
                                            <div class="dotss high"></div>
                                        </div> -->
                                        Tetuan
                                    </td>
                                    <td>
                                        <div class="relative">
                                            <img src="../../assets/img/hero.jpg" alt="myImg">
                                            Soo Tang Hoon
                                        </div>
                                        <!-- Soo Tang Hoon -->
                                    </td>
                                    <td>09356234162</td>
                                    <td>sutanghon@gmail.com</td>
                                    <td>
                                        <p class="status role">Admin</p>
                                    </td>
                                    <td>
                                        <p class="status role active">Active</p>
                                    </td>
                                    <td><a href="viewProfile.php" class="view-action">View</a></td>
                                    <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                </tr>

                                <tr onclick="window.location.href='viewProfile.php'">
                                    <!-- <td>1</td> -->
                                    <td>
                                        <!-- <div class="relative">
                                            <img src="../../assets/img/zambo.png" alt="myImg">Tetuan
                                            <div class="dotss high"></div>
                                        </div> -->
                                        Tugbungan
                                    </td>
                                    <td>
                                        <div class="relative">
                                            <img src="../../assets/img/undraw_male_avatar_g98d.svg" alt="myImg">
                                            Juan Carlos Yulo
                                        </div>
                                    </td>
                                    <td>09356234162</td>
                                    <td>sutanghon@gmail.com</td>
                                    <td>
                                        <p class="status role">Admin</p>
                                    </td>
                                    <td>
                                        <p class="status role active">Active</p>
                                    </td>
                                    <td><a href="viewProfile.php" class="view-action">View</a></td>
                                    <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                </tr>

                                <tr onclick="window.location.href='viewProfile.php'">
                                    <!-- <td>1</td> -->
                                    <td>
                                        <!-- <div class="relative">
                                            <img src="../../assets/img/zambo.png" alt="myImg">Tetuan
                                            <div class="dotss high"></div>
                                        </div> -->
                                        Guiwan
                                    </td>
                                    <td>
                                        <div class="relative">
                                            <img src="../../assets/img/undraw_male_avatar_g98d.svg" alt="myImg">
                                            Jedeh Arpy
                                        </div>
                                    </td>
                                    <td>09356234162</td>
                                    <td>sutanghon@gmail.com</td>
                                    <td>
                                        <p class="status role">Admin</p>
                                    </td>
                                    <td>
                                        <p class="status role inactive">Inactive</p>
                                    </td>
                                    <td><a href="viewProfile.php" class="view-action">View</a></td>
                                    <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                </tr>







                            </tbody>
                        </table>
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