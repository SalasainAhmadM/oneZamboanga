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
                            <a href="viewEC.php">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resources.php">Resource Management</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resourceDistribution.php">Distribution</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container supply"> <!--overview-->
                    
                    <special-navbar></special-navbar>
                    
                    <div class="viewSupply-container" style="box-shadow: none;">
                        

                        <div class="supplyTop itemDonate">
                            <img src="../../assets/img/canton.png" alt="">
                            <ul class="supplyDetails">
                                <li>Supply Name: Pancit Canton</li>
                                <li>Category: Lucky Me</li>
                                <li>Quantity: 400</li>
                            </ul>
                        </div>

                        <div class="supplyBot">
                            <ul class="supplyTable">
                                <li class="showDistributed active">Distribute</li>
                                <li class="showReceived">Distributed</li>
                            </ul>

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
                                                        <p>Admitted</p>
                                                        <p>Transferred</p>
                                                    </div>
                                                </div>

                                                <div class="searchFilter">
                                                    <input type="text" placeholder="Search...">
                                                    <label for=""><i class="fa-solid fa-magnifying-glass"></i></label>
                                                </div>
                                            </div>

                                            <tr>
                                                <th>Family Head</th>
                                                <th style="text-align: center;">Number of members</th>
                                                <th style="text-align: center;">Status</th>
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;">Admitted</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;">Transferred</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;">Admitted</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName" style="text-align: center;">
                                                    <input type="checkbox" id="donate">
                                                    Lebron James
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;">Admitted</td>
                                            </tr>
                                            
                                            
                                        </tbody>
                                    </table>
                                    <div class="distributeBtn-container">
                                        <button class="distributeBtn">Select All</button>
                                        <button class="distributeBtn">Distribute</button>
                                    </div>
                                </form>
    
                               

                                <form action="">
                                    <table class="receivedTable sent">
                                        <thead>
                                            <tr>
                                                <th>Family Head</th>
                                                <th style="text-align: center;">Number of members</th>
                                                <th>Status</th>
                                                <th>Date Received</th>
                                                <th>Time Received</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName">
                                                    <input type="checkbox" id="donate">
                                                    Raiza Beligolo
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td>Admitted</td>
                                                <td>08-24-2024</td>
                                                <td>5:00pm</td>
                                                <td>1 pack</td>
                                            </tr>

                                            <tr onclick="toggleCheckbox(this)">
                                                <td class="selectName">
                                                    <input type="checkbox" id="donate">
                                                    Raiza Beligolo
                                                </td>
                                                <td class="ecMembers" style="text-align: center;">
                                                    3
                                                    
                                                    <ul class="viewMembers" style="text-align: left;">
                                                        <li>Lebron James</li>
                                                        <li>Bronny James</li>
                                                        <li>Kevin Durant</li>
                                                        <li>mark larenz</li>
                                                    </ul>
                                                </td>
                                                <td>Transferred</td>
                                                <td>08-24-2024</td>
                                                <td>5:00pm</td>
                                                <td>1 pack</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    <div class="receiveBtn-container">
                                        <button class="distributeBtn">Select All</button>
                                        <button class="distributeBtn">Redistribute</button>
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
    <script src="../../includes/ecNavbar.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- filter active -->
    <script>
        const supplyFiler = document.querySelectorAll('.supplyTable li');

        supplyFiler.forEach(item => {
            item.addEventListener('click', function() {
                //check if the item is clicked
                if (this.classList.contains('active')) {
                    //if active, remove active class
                    this.classList.remove('active');
                } else {
                    // if not, first remove active
                    supplyFiler.forEach(i => i.classList.remove('active'));

                    // then add actgive if clicked
                    this.classList.add('active');
                }
            })
        })
    </script>


    <!-- display tables -->
    <script>
        // Get the elements
        const distributeBtn = document.querySelector('.showDistributed');
        const distributeTable = document.querySelector('.distributedTable');
        const distributeBtnShow = document.querySelector('.distributeBtn-container');

        const receiveBtn = document.querySelector('.showReceived');
        const receiveTable = document.querySelector('.receivedTable');
        const receiveBtnShow = document.querySelector('.receiveBtn-container');

        distributeBtn.addEventListener('click', function() {
            // receiveTable.style.visibility = 'hidden';
            // distributeTable.style.visibility = 'visible';

            receiveTable.style.display = 'none';  // Hide the received table
            distributeTable.style.display = 'table';  // Show the distributed table (use 'table' for correct display)

            distributeBtnShow.style.display = 'block';
            receiveBtnShow.style.display = 'none';

        });

        receiveBtn.addEventListener('click', function() {
            // distributeTable.style.visibility = 'hidden';
            // receiveTable.style.visibility = 'visible';

            distributeTable.style.display = 'none';  // Hide the distributed table
            receiveTable.style.display = 'table';  // Show the received table (use 'table' for correct display)

            receiveBtnShow.style.display = 'block';
            distributeBtnShow.style.display = 'none';

        });
        
    </script>


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