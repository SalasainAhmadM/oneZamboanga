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
                            <a href="viewEC.php">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Transfer</a>
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

                    <div class="bgEc-container" style="margin-top: 1em;">    
                        <div class="bgEc-cards" onclick="window.location.href='evacueesForm.php'">
                            <div class="bgEc-status red"></div>
                            <img src="../../assets/img/evacuation-default.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 85/100</li>
                                <li>Total Evacuees: 70</li>
                            </ul>
                        </div>

                        <div class="bgEc-cards" onclick="window.location.href='evacueesForm.php'">
                            <div class="bgEc-status green"></div>
                            <img src="../../assets/img/ecDefault.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 20/100</li>
                                <li>Total Evacuees: 70</li>
                            </ul>
                        </div>

                        
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

    <!-- import navbar -->
    <script src="../../includes/ecNavbar.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    
    
    
</body>
</html>