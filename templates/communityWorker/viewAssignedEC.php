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
    <link rel="stylesheet" href="../../assets/styles/utils/viewEC.css">

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
                            <a href="#">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="viewAssignedEC.php">Overview</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                        <button class="addBg-admin" onclick="window.location.href='evacueesForm.php'">
                            Admit
                            <!-- <i class="fa-solid fa-plus"></i> -->
                        </button>
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container overview">
                    <special-navbar></special-navbar>

                    <div class="ecView-content">
                        <div class="description">
                            <img src="../../assets/img/ecDeaultPhoto.svg" alt="" class="bgEc-img">

                            <ul class="bgEc-info">
                                <div class="bgEc-status yellow"></div>
                                <li><strong>Tetuan Central School</strong></li>
                                <li>Barangay: Tetuan</li>
                                <li>Location: Tetuan Alvarez Drive</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 50</li>
                                <li>Total Evacuees: 70</li>
                                <li>Occupied: 50/100</li>

                                
                            </ul>
                        </div>

                        <div class="chart">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>



                    <!-- edit evacuation center -->
                    <div class="editEC-container">
                        <div class="editEC-form">
                            <button class="closeEdit"><i class="fa-solid fa-xmark"></i></button>

                            <h3 class="editEC-header">
                                Edit Evacuation Center
                            </h3>
                            <form action="">
                                <div class="editEC-input">
                                    <label for="">Name of Evacuation Center</label>
                                    <input type="text" required>
                                </div>
    
                                <div class="editEC-input">
                                    <label for="">Location</label>
                                    <input type="text" required>
                                </div>
    
                                <div class="editEC-input">
                                    <label for="">Capacity</label>
                                    <input type="number" required>
                                </div>
    
                                <div class="editEC-input">
                                    <label for="">Change photo</label>
                                    <input id="fileInput" type="file" class="noBorder" />
                                </div>
    
                                <div class="editEC-input">
                                    <button class="mainBtn" id="save">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>



                </div>
            </div>
        </main>

    </div>

    
    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/navbarECworkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- popup edit ecenter form -->
    <script src="../../assets/src/utils/editEc-popup.js"></script>

    <!-- graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js"></script>
    

    <!-- sweetalert edit form -->
    <script>
        $('#save').on('click', function() {
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


    <!-- sweetalert delete -->
    <script>
        $('.ecenterDelete').on('click', function() {
            Swal.fire({
            title: "Delete Evacuation Center?",
            text: "",
            icon: "warning",
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
                text: "Evacuation center deleted.",
                icon: "success",
                customClass: {
                    popup: 'custom-swal-popup'
                }
                });
            }
            });

        })
    </script>


    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
            label: 'Evacuees',
            data: [33, 23, 15, 10, 3, 40, 50, 60, 60, 20, 10, 11],
            
            borderWidth: 1
            }]
        },
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        }
        });
    </script>
    
    
</body>
</html>