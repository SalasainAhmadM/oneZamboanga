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
    <link rel="stylesheet" href="../../assets/styles/utils/addEC.css">
    <link rel="stylesheet" href="../../assets/styles/utils/barangayStatus.css">
    <!-- <link rel="stylesheet" href="../../assets/styles/utils/messagebox.css"> -->

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

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
                            <a href="evacuation.php">Barangay Tetuan Evacuation Centers</a>

                            <!-- next page -->
                            <!-- <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Tetuan Evacuation Centers</a> -->
                        </div>

                        


                        <!-- <a class="addBg-admin" href="addEC.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                        <button class="addBg-admin">
                            Create
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper bgEcWrapper">
                <div class="main-container bgEcList">

                    
                    <!-- <div class="statusHeader">
                        <h3>Barangay Tetuan</h3>
                    </div> -->

                    <div class="bgEc-container">    
                        <div class="bgEc-cards" onclick="window.location.href='viewEC.php'">
                            <!-- status color -->
                            <div class="bgEc-status red"></div>
                            <img src="../../assets/img/evacuation-default.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                            </ul>

                        </div>

                        <div class="bgEc-cards" onclick="window.location.href='viewEC.php'">
                            <!-- status color -->
                            <div class="bgEc-status red"></div>
                            <img src="../../assets/img/evacuation-default.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                            </ul>

                            
                        </div>

                        <div class="bgEc-cards" onclick="window.location.href='viewEC.php'">
                            <div class="bgEc-status green"></div>
                            <img src="../../assets/img/ecDefault.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                            </ul>
                        </div>

                        <div class="bgEc-cards">
                            <div class="bgEc-status green"></div>
                            <img src="../../assets/img/ecDeaultPhoto.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                            </ul>
                        </div>

                        <div class="bgEc-cards">
                            <div class="bgEc-status yellow"></div>
                            <img src="../../assets/img/ecDeaultPhoto.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Location: Tetuan</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                            </ul>
                        </div>
                    </div>


                    <!-- add evacuaton form -->
                    <div class="addEC-container">
                        <div class="addEC-form">
                            <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>

                            <h3 class="addEC-header">
                                Create Evacuation Center
                            </h3>
                            <form action="">
                                <div class="addEC-input">
                                    <label for="">Name of Evacuation Center</label>
                                    <input type="text" required>
                                </div>
    
                                <div class="addEC-input">
                                    <label for="">Location</label>
                                    <input type="text" required>
                                </div>
    
                                <div class="addEC-input">
                                    <label for="">Capacity (per family)</label>
                                    <input type="number" required>
                                </div>
    
                                <div class="addEC-input">
                                    <label for="">Add photo</label>
                                    <input id="fileInput" type="file" class="noBorder" />
                                </div>
    
                                <div class="addEC-input">
                                    <button class="mainBtn" id="create">Create</button>
                                </div>
                            </form>
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

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    <!-- pop up add form -->
    <script src="../../assets/src/utils/addEc-popup.js"></script>



    <!-- sweetalert popup messagebox add form-->
    <script>
        $('#create').on('click', function() {
            Swal.fire({
            title: "Create Evacuation Center?",
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
                text: "Evacuation center created.",
                icon: "success",
                customClass: {
                    popup: 'custom-swal-popup'
                }
                });
            }
            });

        })
    </script>



    

    
    
</body>
</html>