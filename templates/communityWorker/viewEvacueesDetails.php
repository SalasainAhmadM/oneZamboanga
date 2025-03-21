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
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesTable.css">
    <link rel="stylesheet" href="../../assets/styles/utils/evacueesProfile.css">

    <style>
        .btnAdmit {
            border: none;
            outline: none;
            background-color: var(--clr-slate600);
            color: var(--clr-white);
            padding: .3em;
            border-radius: .5em;
            cursor: pointer;
            transition: .3s;

            &:hover {
                background-color: var(--clr-dark);
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
                            <a href="viewAssignedEC.php">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="evacueesPage.php">Evacuees</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Profile</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="addEvacuees.php">
                            <i class="fa-solid fa-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container eProfile">
                    <!-- modal -->
                    <!-- <div class="profile-cta">
                        <label for="cta-toggle" class="cta-button">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </label>
                        <input type="checkbox" name="" id="cta-toggle" class="cta-toggle">

                        <div class="cta-modal">
                            <div class="cta-options" style="text-align: center;">
                                <a href="evacueesForm.php">Admit</a>
                            </div>
                        </div>
                    </div> -->
                    <button class="profile-cta btnAdmit" onclick="window.location.href='evacueesForm.php'">Admit</button>
                    
                    <div class="eprofile-top">
                        <div class="evacueesProfile">
                            <div class="profileInfo-left">
                                <img class="profileImg" src="../../assets/img/undraw_male_avatar_g98d.svg" alt="">
                                <p class="leader">Household Leader</p>
                            </div>

                            <div class="profileInfo-right">
                                <h3 profile-name>Mark Larenz Tabotabo</h3>

                                <div class="profile-details">
                                    <p class="details-profile">Address: Tetuan Alvarez Drive</p>
                                    <p class="details-profile">Gender: Male</p>
                                    <p class="details-profile">Age: 22</p>
                                    <p class="details-profile">Contact Number: 0909090909</p>
                                    <p class="details-profile">Occupation: Student</p>
                                    <p class="details-profile">Status of Occupancy:</p>
                                    <p class="details-profile">Damaged: </p>
                                    <p class="details-profile">Cost of damaged: </p>
                                </div>
                            </div>

                        </div>

                        <div class="eprofileLog">
                            <div class="right-header">
                                <!-- <div class="distribution">
                                    
    
                                    <p class="distributon-header">
                                        Distribution
                                    </p>
    
                                    <div class="activeLine"></div>
                                </div> -->
    
                                <div class="activityLog">
                                    <p class="activityLog-header" style="color: var(--clr-slate600);">
                                        Activity Logs
                                    </p>
    
                                    <div class="activeLine log"></div>
                                </div>
                            </div>

                            <div class="data">

                                <div class="log-container">
                                    <p class="logDate">11-15-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Moved-out</p>
                                </div>

                                <div class="log-container">
                                    <p class="logDate">11-15-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Admitted</p>
                                </div>

                                

                                <div class="log-container">
                                    <p class="logDate">11-15-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Approved, transferred successfully</p>
                                </div>


                                <div class="log-container">
                                    <p class="logDate">11-28-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Transferred, waiting for approval</p>
                                </div>

                                

                                <div class="log-container">
                                    <p class="logDate">11-28-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Admitted</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    




                    <div class="table-container">
                        <section class="tblheader">
                            
                            <h4>Family Members</h4>
    
                            <!-- <div class="filter-popup">
                                <label for="modal-toggle" class="modal-button">
                                    <i class="fa-solid fa-filter"></i>
                                </label>
                                <input type="checkbox" name="" id="modal-toggle" class="modal-toggle">
    
                                <div class="modal famMember">
                                    <div class="modal-content">
                                        <div class="filter-option">
                                            <div class="option-content">
                                                <input type="checkbox" name="barangay" id="head">
                                                <label for="head">Head of the family</label>
                                            </div>
                                            <div class="option-content">
                                                <input type="checkbox" name="barangay" id="pwd">
                                                <label for="pwd">Pwd</label>
                                            </div>
                                            
    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> -->
                            <div class="filterFam">
                                <label for="fam-toggle" class="famBtn">
                                    <i class="fa-solid fa-filter"></i>
                                </label>
                                <input type="checkbox" id="fam-toggle" class="fam-toggle">

                                <div class="modal-fam">
                                    <!-- <div class="famOption">
                                        <input type="checkbox" name="famMembers" id="pwd">
                                        <label for="pwd">Family Head</label>
                                    </div> -->
                                    <div class="famOption">
                                        <input type="checkbox" name="famMembers" id="head">
                                        <label for="head">PWD</label>
                                    </div>
                                </div>
                            </div>
    
    
                            <!-- <div class="input_group">
                                <input type="search" placeholder="Search...">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div> -->
    
                        </section>
    
                        <section class="tblbody">
                            <table id="mainTable">
                                <thead>
                                    
                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Full Name</th>
                                        <th>Relationship</th>
                                        <th>Age</th>
                                        <th style="text-align: center;">Gender</th>
                                        <th>Education</th>
                                        <th>Occupation</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    
                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>
                                    
                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                    <tr onclick="window.location.href='#'">
                                        <td>
                                            Lebron James
                                        </td>
                                        <td>Brother</td>
                                        <td>22</td>
                                        <td style="text-align: center;">Male</td>
                                        <td>Colloge</td>
                                        <td>Student</td>
                                        <!-- <td><a href="#" class="view-action">Edit</a></td> -->
                                    </tr>

                                                                        
                                </tbody>
                            </table>
                        </section>
        
                        <div class="no-match-message">No matching data found</div>
                    </div>
                    
                    <!-- <div class="eprofile-bot">
                    </div> -->

                    
                    
                    
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

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    
    
    
</body>
</html>