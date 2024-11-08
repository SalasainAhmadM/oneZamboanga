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



    <!-- quantity category -->
    <style>
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
                            <a href="viewAssignedEC.php">Tetuan Central School</a>

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
                <div class="main-container supply"> <!--overview-->
                    
                    <special-navbar></special-navbar>
                    
                    <div class="viewSupply-container">
                        <div class="supply-cta">
                            <label for="supply-toggle" class="supply-button">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </label>
                            <input type="checkbox" name="" id="supply-toggle" class="supply-toggle">
    
                            <div class="supply-modal">
                                <div class="cta-options" style="text-align: center;">
                                    <button class="supplyStock">Add Stock</button>
                                    <button class="supplyAdd">Edit</button>
                                    <button class="supplyDelete">Delete</button>
                                </div>
                            </div>
                        </div>



                        <!-- add supply stock -->
                        <div class="addStock-supply">
                            <button class="closeStock"><i class="fa-solid fa-xmark"></i></button>
                            <form action="" class="supplyForm">
                                <h3>Add Stock</h3>

                                <div class="addInput-wrapper">
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
        
                                    <!-- <div class="add-input">
                                        <label for="">Quantity: </label>
                                        <input type="number" required>
                                    </div> -->
                                    <div class="add-input">
                                        <label for="">Quantity: </label>
                                        <input type="number" class="piece" placeholder="Enter amount by piece" required>
                                        <input type="number" class="pack" placeholder="Enter amount by pack" required>
    
                                        <ul class="selectQuantity">
                                            <li class="perPiece">By piece</li>
                                            <li class="perPack">By pack</li>
                                        </ul>
    
                                    </div>
                                </div>
    
                                <button class="mainBtn" id="stocks">Confirm</button>
                            </form>
                        </div>
                        



                        <!-- edit supply -->
                        <div class="addForm-supply">
                            <button class="closeForm"><i class="fa-solid fa-xmark"></i></button>
                            <form action="" class="supplyForm">
                                <h3>Edit Supply</h3>
                                <div class="addInput-wrapper">
                                    <div class="add-input">
                                        <label for="">Supply Name: </label>
                                        <input type="text">
                                    </div>
        
                                    <div class="add-input">
                                        <label for="">Category: </label>
                                        <select name="" id="">
                                            <option value="">Select</option>
                                            <option value="">Ayuda Pack</option>
                                            <option value="">Food</option>
                                        </select>
                                    </div>

                                    <div class="add-input">
                                        <label for="">Change Photo: </label>
                                        <input type="file">
                                    </div>
        
                                    <div class="add-input">
                                        <label for="">Description: </label>
                                        <input type="text">
                                    </div>
                                </div>
    
                                <button class="mainBtn">Save</button>
                            </form>
                        </div>

                        <div class="supplyTop">
                            <img src="../../assets/img/canton.png" alt="">
                            <ul class="supplyDetails">
                                <li>Supply Name: Pancit Canton</li>
                                <li>Category: Lucky Me</li>
                                <li>Description: Lorem ipsum dolor sit amet consectetur adipisicing !</li>
                                <li>Quantity: 400</li>
                            </ul>
                        </div>

                        <div class="supplyBot">
                            <ul class="supplyTable">
                                <li class="showReceived active">Received</li>
                                <li class="showDistributed">Distributed</li>
                            </ul>

                            <div class="supplyTable-container">
                                <table class="receivedTable">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>Date Received</th>
                                            <th>Time Received</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>

                                        <tr>
                                            <td>Raiza Beligolo</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>+50 pcs</td>
                                        </tr>
    
                                        
                                    </tbody>
                                </table>
    
                                <table class="distributedTable">
                                    <thead>
                                        <tr>
                                            <th>Distributed by</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        <tr>
                                            <td>Lebron James</td>
                                            <td>08-24-2024</td>
                                            <td>5:00pm</td>
                                            <td>-50 pcs</td>
                                        </tr>
    
                                        
    
                                        
                                        
                                    </tbody>
                                </table>

                            </div>

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

    <!-- import navbar -->
    <script src="../../includes/navbarECworkers.js"></script>

    <script src="../../includes/logout.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

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

        const receiveBtn = document.querySelector('.showReceived');
        const receiveTable = document.querySelector('.receivedTable');

        distributeBtn.addEventListener('click', function() {
            // receiveTable.style.visibility = 'hidden';
            // distributeTable.style.visibility = 'visible';

            receiveTable.style.display = 'none';  // Hide the received table
            distributeTable.style.display = 'table';  // Show the distributed table (use 'table' for correct display)

        });

        receiveBtn.addEventListener('click', function() {
            // distributeTable.style.visibility = 'hidden';
            // receiveTable.style.visibility = 'visible';

            distributeTable.style.display = 'none';  // Hide the distributed table
            receiveTable.style.display = 'table';  // Show the received table (use 'table' for correct display)

        });
        
    </script>


    


    <script>
                
        const supplyBtn = document.querySelector('.supplyAdd');
        const supplyForm = document.querySelector('.addForm-supply');
        const closeForm = document.querySelector('.closeForm');

        const stockBtn = document.querySelector('.supplyStock');
        const stockForm = document.querySelector('.addStock-supply');
        const closeStock = document.querySelector('.closeStock');


        const modalOption = document.querySelector('.supply-toggle');
        const body = document.querySelector('body'); // to get the body element
        const deleteOption = document.querySelector('.supplyDelete');

        // edit supply
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



        // add stock
        stockBtn.addEventListener('click', function() {
            stockForm.style.display = 'block';

            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
            
            body.classList.add('body-overlay'); // add the overlay class to the body

        });
        closeStock.addEventListener('click', function() {
            stockForm.style.display = 'none'
            body.classList.remove('body-overlay'); // remove the overlay class to the body
        });



        // delete supply
        deleteOption.addEventListener('click', function() {
            // If modalOption is an input (checkbox or radio button), uncheck it
            if (modalOption.type === 'checkbox' || modalOption.type === 'radio') {
                modalOption.checked = false; // Uncheck the input
            }
        })
    </script>

    <!-- ====sweetalert popup messagebox====== -->


    <!-- message pop up for edit supply -->
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


    <!-- message pop up for add stock -->
    <script>
        $('#stocks').on('click', function() {
            Swal.fire({
            title: "Add stock(s)?",
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


    <!-- message pop up for delete btn -->
    <script>
        $('.supplyDelete').on('click', function() {
            Swal.fire({
            title: "Delete Supply?",
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