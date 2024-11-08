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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Assign</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container supply"> <!--overview-->
                    
                    <special-personnel></special-personnel>
                    
                    <div class="viewSupply-container" style="box-shadow: none;">
                        

                        <div class="supplyTop itemDonate">
                            <img src="../../assets/img/evacuation-default.svg" alt="">
                            <ul class="supplyDetails">
                                <li>Evacuation Center: Tetuan Central School</li>
                                <li>Capacity: 100 Families</li>
                                <li>Total Families: 30</li>
                                <li>Total Evacuees: 120</li>
                            </ul>
                        </div>

                        <div class="supplyBot">
                            

                            <div class="supplyTable-container supplyDonate">
                                
                                <form action="">
                                    <table class="distributedTable donate">
                                        <thead>
                                            <div class="distributeSearch" style="display: none;">
                                                <input type="text" placeholder="Search...">
                                                <label for="distributeSearch"><i class="fa-solid fa-magnifying-glass"></i></label>
                                            </div>

                                            <div class="filterStatus">
                                                <div class="statusFilter">
                                                    <label for="statusEC"><i class="fa-solid fa-filter"></i></label>
                                                    <input type="checkbox" id="statusEC" class="statusEC">

                                                    <div class="showStatus">
                                                        <p>Kagawad</p>
                                                        <p>SK</p>
                                                    </div>
                                                </div>

                                                <div class="searchFilter">
                                                    <input type="text" placeholder="Search...">
                                                    <label for=""><i class="fa-solid fa-magnifying-glass"></i></label>
                                                </div>
                                            </div>

                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact #</th>
                                                <th style="text-align: center;">Position</th>
                                                <!-- <th style="text-align: center;">Status</th> -->
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>


                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td>communityWorkers@gmail.com</td>
                                                <td>0909090909</td>
                                                <td style="text-align: center;">Kagawad</td>
                                            </tr>

                                            
                                            
                                            
                                        </tbody>
                                    </table>
                                    <div class="distributeBtn-container">
                                        <button class="distributeBtn">Select All</button>
                                        <button class="distributeBtn">Assign</button>
                                    </div>
                                </form>
    
                               

                                


                            </div>

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
    <script src="../../includes/personnelpageNav.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    


    <!-- the checkbox will be checked when the tr is clicked -->
    <script>
        function toggleCheckbox(row) {
            const checkbox = row.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked; // Toggle checkbox state
        }
    </script>


    


    

    <!-- ====sweetalert popup messagebox====== -->
    <script>
        $('.mainBtn').on('click', function() {
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


    

    
    
    
</body>
</html>