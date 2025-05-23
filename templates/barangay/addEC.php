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
    <link rel="stylesheet" href="../../assets/styles/utils/messagebox.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<body>

    <div class="container">
        
        <aside class="left-section">
            <div class="logo">
                <button class="menu-btn" id="menu-close">
                    <i class="fa-regular fa-circle-left"></i>
                </button>
                <img src="../../assets/img/logo5.png" alt="">
                <a href="#">One Zamboanga</a>
            </div>

            <div class="sidebar">
                <div class="item">
                    <a href="dahsboard_barangay.php">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="dahsboard_barangay.php" class="dot">Dashboard</a>
                </div>

                <div class="item" id="active">
                    <a href="evacuation.php">
                        <i class="fa-solid fa-person-shelter"></i>
                    </a>
                    <a href="evacuation.php" class="dot">Evacuation Center</a>
                </div>

                <div class="item">
                    <a href="barangayStatus.php">
                        <i class="fa-solid fa-users"></i>
                    </a>
                    <a href="barangayStatus.php" class="dot">Barangay Personnel</a>
                </div>

                <div class="item">
                    <a href="#">
                        <i class="fa-solid fa-file-signature"></i>
                    </a>
                    <a href="#" class="dot">Activity Feed</a>
                </div>

                
                <div class="item">
                    <a href="#">
                        <i class="fa-solid fa-bell"></i>
                    </a>
                    <a href="#" class="dot">Notifications</a>
                </div>
                
                <div class="item">
                    <a href="#">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <a href="#" class="dot">Settings</a>
                </div>
            </div>

            <div class="pic">
                <img src="../../assets/img/zambo.png" alt="">
            </div>

            <div class="logout">
                <!-- <h5>Barangay Name or idk</h5> -->
                <div class="link">
                    <a href="login.php">
                        <p>Click to <b>Logout</b></p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
                
            </div>
            <a class="logout logout-icon" href="login.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>

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
                            <a href="evacuation.php">Evacuation Center</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create Evacuation Center</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper bgEcWrapper">
                <div class="main-container addEc">

                    <div class="addEC-container">
                        <div class="addEC-form">

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
                                    <label for="">Capacity</label>
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

    


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

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