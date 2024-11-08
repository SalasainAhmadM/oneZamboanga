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
    <link rel="stylesheet" href="../../assets/styles/utils/resources.css">

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
                            <a href="viewAssignedEC.php">Tetuan Central School</a>

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
                <div class="main-container overview">
                    
                    <special-navbar></special-navbar>
                    
                    <div class="supplySearch">
                        <input type="text" placeholder="Search...">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <ul class="supply-filter">
                        <li class="active">All</li>
                        <li>Food</li>
                        <li>Drinks</li>
                        <li>Clothes</li>
                        <li>Ayuda pack</li>
                    </ul>

                    <!-- popup supply add -->
                    <div class="addForm-supply">
                        <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>
                        <form action="" class="supplyForm">
                            <h3>Add Supply</h3>
                            <div class="addInput-wrapper">
                                <div class="add-input">
                                    <label for="">Supply Name: </label>
                                    <input type="text" required>
                                </div>
    
                                <div class="add-input">
                                    <label for="">Category: </label>
                                    <select name="" id="" required>
                                        <option value="">Select</option>
                                        <option value="">Ayuda Pack</option>
                                        <option value="">Food</option>
                                    </select>
                                </div>
    
                                <div class="add-input">
                                    <label for="">Date: </label>
                                    <input type="date" required>
                                </div>

                                <div class="add-input">
                                    <label for="">Time: </label>
                                    <input type="time" required>
                                </div>
    
                                <div class="add-input">
                                    <label for="">From: </label>
                                    <input type="text" required>
                                </div>
    
                                <div class="add-input">
                                    <label for="">Quantity: </label>
                                    <input type="number" required>
                                </div>
    
                                <div class="add-input">
                                    <label for="">Description: </label>
                                    <input type="text" required>
                                </div>
                            </div>

                            <button class="mainBtn">Add Supply</button>
                        </form>
                    </div>

                    <!-- popup category add -->
                    <div class="addForm-category">
                        <button class="closeCategory"><i class="fa-solid fa-xmark"></i></button>
                        <form action="" class="categoryForm">
                            <h3>Add Category</h3>
                            <div class="addInput-wrapper">
                                <div class="add-input category">
                                    <label for="">Category: </label>
                                    <input type="text" required>
                                </div>
    
                                
                            </div>

                            <button class="mainBtn category">Add Category</button>
                        </form>
                    </div>
                    

                    <div class="supply-container">
                        <div class="supply-wrapper">
                            <!-- <div class="supply-card add">
                                
                                <a href="#" class="supply-add">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div> -->
                            <div class="supply-card">
                                
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <li>Description: basta lucky me pancit canton ito</li>
                                    <li>Quantity: 100</li>
                                </ul>
                                <a href="viewDistribute.php" class="supply-btn">Distribute</a>
                            </div>

                            
                        </div>
                    </div>
                   


                    
                </div>
            </div>
        </main>

    </div>

    <!-- filter active -->
    <script>
        const supplyFiler = document.querySelectorAll('.supply-filter li');

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


    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logot -->
    <script src="../../includes/logout.js"></script>

    <!-- import navbar -->
    <script src="../../includes/navbarECworkers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>


    

    

    
    
    
</body>
</html>