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
    <link rel="stylesheet" href="../../assets/styles/utils/addEvacuees.css">
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
                            <a href="#">Add Evacuees</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container evacuees">
                    <div class="eform-wrapper">
                        <div class="eform-header">
                            <img src="../../assets/img/zambo.png" alt="zamboanga-logo">
                            <p>Republica de Filipinas</p>
                            <p>Ciudad de Zamboanga</p>
                            <h4>OFICINA DE ASISTENCIA SOCIAL Y DESAROLLO</h4>
                            <h3>DISASTER ASSITANCE FAMILY ACCESS CARD (DAFAC)</h3>
                        </div>

                        <form action="">
                            <div class="ec-info">
                                <div class="ecInfo">
                                    <label for="">Evacuation Center</label>
                                    <span>:</span>
                                    <!-- <input type="text" required> -->
                                    <select name="" id=""> <!--dapat dito ma dropdown lahat ng list of evacuation center-->
                                        <option value="">Select</option>
                                        <option value="">Tetuan Central School</option>
                                        <option value="">Sta Maria Evacuation Center</option>
                                    </select>
                                </div>
                                <div class="ecInfo">
                                    <label for="">Barangay</label>
                                    <span>:</span>
                                    <select name="" id="" required> <!--dapat dito maka input/type din pra mabilis makapili ng option-->
                                        <option value="">Select</option>
                                        <option value="">Tetuan</option>
                                        <option value="">Tugbungan</option>
                                    </select>
                                    <!-- <input type="text" required> -->
                                </div>
                                
                                <div class="ecInfo">
                                    <label for="">Type of Disaster</label>
                                    <span>:</span>
                                    <select name="disaster" id="dSelect" onchange="toggleInput()" required>
                                        <option value="">Select</option>
                                        <option value="flood">Flood Incident</option>
                                        <option value="fire">Fire Incident</option>
                                        <option value="others">Others</option>
                                    </select>
                                    <input type="text" placeholder="Specify.." id="dInput" style="display: none;">
                                </div>
                                <div class="ecInfo">
                                    <label for="">Date</label>
                                    <span>:</span>
                                    <input type="date" required>
                                </div>
                            </div>

                            <div class="headFam">
                                <label for="">Name of Family Head:</label>
                                <div class="details-container">
                                    <div class="headFam-details">
                                        <input type="text" required>
                                        <label class="details" for="">Last Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" required>
                                        <label class="details" for="">First Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" required>
                                        <label class="details" for="">Middle Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" class="age" required>
                                        <label class="details" for="">Extension</label>
                                    </div>
                                    
                                    
                                    
                                </div>

                                <div class="occupation-container">
                                    <div class="headFam-details">
                                        <input type="date" class="age" required>
                                        <label class="details" for="">Birthdate</label>
                                    </div>

                                    <div class="headFam-details">
                                        <input type="number" class="age" required>
                                        <label class="details" for="">Age</label>
                                    </div>

                                    <div class="headFam-details">
                                        <!-- <input type="number" class="age" required> -->
                                        <select name="" id="" required class="age">
                                            <option value="">Select</option>
                                            <option value="">Male</option>
                                            <option value="">Female</option>
                                        </select>
                                        <label class="details" for="">Sex</label>
                                    </div>
                                    <div class="occupation">
                                        <label for="">Occupation:</label>
                                        <input type="text" required>
                                    </div>

                                    <div class="income">
                                        <label for="">Total Monthly Income:</label>
                                        <input type="number" required>
                                    </div>
                                </div>

                                <div class="houseStatus">
                                    <div class="statusOccupancy">
                                        <div class="titleStat">
                                            <p>Status of Occupancy:</p>
                                        </div>
    
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">House Owner</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">Sharer</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">Renter</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">Boarder</label>
                                        </div>
                                    </div>
    
                                    <div class="damaged">
                                        <div class="titleStat">
                                            <p>Damaged:</p>
                                        </div>
    
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">Totally Damaged</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox">
                                            <label for="">Partially Damaged</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <label for="">Estimated Cost of Damaged:</label>
                                            <input type="number" required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="owner">
                                    <label for="">Name of House owner:</label>
                                    <input type="text" required>
                                </div>
                            </div>

                            <div class="addMembers-container">
                                <p class="memberTitle">Add Members:</p>
                                <div class="member-details">

                                    <div class="member-input">
                                        <label for="">Last Name: </label>
                                        <input type="text">
                                    </div>

                                    <div class="member-input">
                                        <label for="">First Name: </label>
                                        <input type="text">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Middle Name: </label>
                                        <input type="text">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Extension: </label>
                                        <input type="text" placeholder="e.g., Jr.">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Relation to Family Head:</label>
                                        <input type="text">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Age:</label>
                                        <input type="number">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Gender:</label>
                                        <select name="gender" id="">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>

                                    <div class="member-input">
                                        <label for="">Education:</label>
                                        <input type="text">
                                    </div>

                                    <div class="member-input">
                                        <label for="">Occupation:</label>
                                        <input type="text">
                                    </div>
                                </div>
                                <button class="mainBtn">Add More</button>
                            </div>


                            <div class="addEvacuees">
                                <button class="mainBtn" id="create">Admit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </div>


    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <script src="../../includes/logout.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script>
        $('#create').on('click', function() {
            Swal.fire({
            title: "Admit Evacuees?",
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
    
    
    <!--select to input js-->
    <script>
        function toggleInput() {
            var select = document.getElementById('dSelect');
            var input = document.getElementById('dInput');
            if (select.value === 'others') {
                select.style.display = 'none';
                input.style.display = 'inline';
                input.focus();
            } else {
                select.style.display = 'inline';
                input.style.display = 'none';
            }
        }
        
        document.addEventListener('click', function(event) {
            var input = document.getElementById('dInput');
            var select = document.getElementById('dSelect');
            if (input.style.display === 'inline' && !input.value) {
                var isClickInside = input.contains(event.target) || select.contains(event.target);
                if (!isClickInside) {
                    input.style.display = 'none';
                    select.style.display = 'inline';
                    select.value = '';
                }
            }
        });
    </script>

    
    
</body>
</html>