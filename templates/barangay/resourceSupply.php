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



    <style>
        
        
    </style>


    <style>
        /* select quantity category */
        .selectQuantity {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
        }

        .perPiece, .perPack {
            background-color: var(--clr-slate600);
            color: var(--clr-white);
            padding-inline: .5em;
            border-radius: .5em;
            font-size: var(--size-sm);
            transition: .3s;
            cursor: pointer;

            &:hover {
                background-color: var(--clr-dark);
            }
        }

        .piece {
            display: none;
        }

        .pack {
            display: none;
        }



        /* manage category */
        .categoryCTA {
            background-color: var(--clr-slate600);
            color: var(--clr-white);
            border: none;
            outline: none;
            padding: .2em;
            transition: .3s;
            cursor: pointer;

            &:hover {
                background-color: var(--clr-dark);
            }
        }

        .listCategory {
            position: relative;
            display: none;
        }

        .closeManage {
            display: none;
            border: none;
            outline: none;
            background-color: transparent;

            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 9;
            
            i {
                font-size: var(--size-lg);
                font-weight: 600;
                color: var(--clr-slate600);
                cursor: pointer;

                &:hover {
                    color: var(--clr-dark);
                }
            }
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
                            <a href="viewEC.php">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resources.php">Resource Management</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="resourceSupply.php">Supplies</a>
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
                        <li class="addCategory" style="background-color: transparent;">
                            <label for="category-modal">
                                <!-- <i class="fa-solid fa-plus"></i> -->
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </label>
                            <input type="checkbox" id="category-modal" class="category-modal">

                            <div class="modalCategory">
                                <div class="categoryOption">
                                    <button class="supplyAdd">Add Supply</button>
                                </div>
                                <div class="categoryOption">
                                    <button class="categoryAdd">Add Category</button>
                                </div>
                                <div class="categoryOption">
                                    <button class="categoryManage">Manage Category</button>
                                </div>
                            </div>
                        </li>
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
                                    <input type="number" class="piece" placeholder="Enter amount by piece" required>
                                    <input type="number" class="pack" placeholder="Enter amount by pack" required>

                                    <ul class="selectQuantity">
                                        <li class="perPiece">By piece</li>
                                        <li class="perPack">By pack</li>
                                    </ul>

                                </div>

                                <div class="add-input">
                                    <label for="">Add Photo: </label>
                                    <input type="file" required>
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
                        <form action="" class="categoryForm">
                            <button class="closeCategory"><i class="fa-solid fa-xmark"></i></button>
                            <h3>Add Category</h3>
                            <div class="addInput-wrapper">
                                <div class="add-input category">
                                    <label for="">Category: </label>
                                    <input type="text" required>
                                </div>
    
                                
                            </div>

                            <button class="mainBtn category" style="width: 100%;">Add Category</button>
                        </form>

                        <table class="listCategory">

                            <thead>
                                <button class="closeManage"><i class="fa-solid fa-xmark"></i></button>
                                <tr>
                                    <th>Category</th>
                                    <th colspan="2" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td>Food</td>
                                    <td><button class="categoryCTA">Edit</button></td>
                                    <td><button class="categoryCTA">Delete</button></td>
                                </tr>

                                <tr>
                                    <td>Food, canton, etc ambut</td>
                                    <td><button class="categoryCTA">Edit</button></td>
                                    <td><button class="categoryCTA">Delete</button></td>
                                </tr>
                            <tbody>

                            </tbody>
                        </table>

                        
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
                                    <li>Quantity: 100 packs</li>
                                </ul>
                                <a href="viewSupply.php" class="supply-btn">View Details</a>
                            </div>

                            <div class="supply-card">
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <!-- <li>Description: basta lucky me pancit canton ito</li> -->
                                     <li>Description: </li>
                                    <li>Quantity: 10 pieces <span style="color: var(--clr-red);">(Low stock)</span></li>
                                </ul>
                                <a href="#" class="supply-btn">View Details</a>
                            </div>

                            <div class="supply-card">
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <li>Description: basta lucky me pancit canton ito</li>
                                    <li>Quantity: 100 packs</li>
                                </ul>
                                <a href="#" class="supply-btn">View Details</a>
                            </div>

                            <div class="supply-card">
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <li>Description: basta lucky me pancit canton ito</li>
                                    <li>Quantity: 100 pieces</li>
                                </ul>
                                <a href="#" class="supply-btn">View Details</a>
                            </div>

                            <div class="supply-card">
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <li>Description: basta lucky me pancit canton ito</li>
                                    <li>Quantity: 100 pieces</li>
                                </ul>
                                <a href="#" class="supply-btn">View Details</a>
                            </div>

                            <div class="supply-card">
                                <img class="supply-img" src="../../assets/img/canton.png" alt="">
                                <ul class="supply-info">
                                    <li>Name: Pancit Canton</li>
                                    <li>Description: basta lucky me pancit canton ito</li>
                                    <li>Quantity: 100 packs</li>
                                </ul>
                                <a href="#" class="supply-btn">View Details</a>
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

    <!-- select type of quantity -->
    <script>
        const selectQuantity = document.querySelector('.selectQuantity');
        const perPieceBtn = document.querySelector('.perPiece');
        const perPackBtn = document.querySelector('.perPack');

        const inputPiece = document.querySelector('.piece');
        const inputPack = document.querySelector('.pack');

        perPieceBtn.addEventListener('click', function() {
            selectQuantity.style.display = 'none';
            inputPiece.style.display = 'block';
        })

        perPackBtn.addEventListener('click', function() {
            selectQuantity.style.display = 'none';
            inputPack.style.display = 'block';
        });
    </script>


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


    <script>
                
        const supplyBtn = document.querySelector('.supplyAdd');
        const categoryBtn = document.querySelector('.categoryAdd');
        const supplyForm = document.querySelector('.addForm-supply');
        const categoryForm = document.querySelector('.addForm-category');
        const closeForm = document.querySelector('.closeForm');
        const closeCategoryForm = document.querySelector('.closeCategory');
        const modalOption = document.querySelector('.category-modal');
        const body = document.querySelector('body'); // to get the body element
        

        const categoryManageBtn = document.querySelector('.categoryManage');
        const categoryHide = document.querySelector('.categoryForm');
        const viewManageCategory = document.querySelector('.listCategory');
        const closeManage = document.querySelector('.closeManage');


        // add supply
        supplyBtn.addEventListener('click', function() {
            supplyForm.style.display = 'block';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
            
            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeForm.addEventListener('click', function() {
            supplyForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // add category
        categoryBtn.addEventListener('click', function() {
            categoryForm.style.display = 'block';
            categoryHide.style.display = 'block';
            viewManageCategory.style.display = 'none';
            closeManage.style.display = 'none';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
            
            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeCategoryForm.addEventListener('click', function() {
            categoryForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // view category
        categoryManageBtn.addEventListener('click', function() {
            categoryForm.style.display = 'block';
            categoryHide.style.display = 'none';
            viewManageCategory.style.display = 'block';
            closeManage.style.display = 'block';


            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
            
            body.classList.add('body-overlay'); // add the overlay class to the body
        });

        closeManage.addEventListener('click', function() {
            categoryForm.style.display = 'none';
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });
    </script>

    <!-- sweetalert popup messagebox -->
    <script>
        $('.mainBtn').on('click', function() {
            Swal.fire({
            title: "Add Supply?",
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
                text: "Supply successfully added.",
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
        $('.mainBtn.category').on('click', function() {
            Swal.fire({
            title: "Add Category?",
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
                text: "Category successfully added.",
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