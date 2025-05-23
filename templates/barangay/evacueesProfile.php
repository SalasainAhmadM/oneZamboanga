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
    <link rel="stylesheet" href="../../assets/styles/utils/viewProfile.css">



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
                            <a href="evacueesPage.php">Evacuees</a>

                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Profile</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container profile-grid">

                    <!-- modal -->
                    <div class="profile-cta">
                        <label for="cta-toggle" class="cta-button">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </label>
                        <input type="checkbox" name="" id="cta-toggle" class="cta-toggle">

                        <div class="cta-modal">
                            <div class="cta-options" style="text-align: center;">
                                <a href="#">Edit</a>
                                <a href="#">Move out</a>
                                <a href="#">Transfer</a>
                            </div>
                        </div>
                    </div>

                    <!-- left content -->
                    <div class="profile-left">
                        <div class="profileInfo">

                            <div class="profileInfo-left">
                                <img class="profileImg" src="../../assets/img/undraw_male_avatar_g98d.svg" alt="">

                                <p class="details-profile">Household Leader</p>
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

                        <div class="barangayEcenter">
                            <h3 class="ecHeader-title">
                                Food Distribution/Distribution
                            </h3>

                            <div class="ecBarangay-container">
                                <div class="ecBarangay-list">
                                    <p class="ecBarangay-name">......</p>
                                    <!-- <p class="ecBarangay-status statActive">Active</p> -->
                                </div>
    
                                

                            </div>
                            
                        </div>
                    </div>


                    <div class="profile-right">
                        <div class="profile-right-container">
                            
                            <div class="right-header">
                                <div class="personel">
                                    <!-- <a href="viewProfile.php" class="personel-header active">
                                        Personel List
                                    </a> -->
    
                                    <p class="personel-header">
                                        Family Members
                                    </p>
    
                                    <div class="activeLine fam"></div>
                                </div>
    
                                <div class="activityLog">
                                    <!-- <a href="viewProfile-page2.php" class="activityLog-header">
                                        Activity Logs
                                    </a> -->
    
                                    <p class="activityLog-header">
                                        Activity Logs
                                    </p>
    
                                    <div class="activeLine"></div>
                                </div>
                            </div>
    
                            <!-- personel content -->
                            <div class="personel-content">
                                <p class="personal-name">Jose Manalo</p>
                                <p class="personel-role">Brother</p>
                            </div>
    
                            <div class="personel-content">
                                <p class="personal-name">Maria Manalo</p>
                                <p class="personel-role">Mother</p>
                            </div>

                            <div class="personel-content">
                                <p class="personal-name">Pipito Manalo</p>
                                <p class="personel-role">Father</p>
                            </div>

                            
    
                            <!-- activity logs content -->
                            <div class="activityLog-content">
                                <div class="log-container">
                                    <p class="logDate">11-15-2024</p>
                                    <div class="logDivider"></div>
                                    <p class="logInfo">Logs Info here</p>
                                </div>
                            </div>
    
                            
                            
    
                            
                        </div>

                    </div>

                    
                </div>
            </div>
        </main>

    </div>

    


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select the elements
            const personelHeader = document.querySelector('.personel-header');
            const activityLogHeader = document.querySelector('.activityLog-header');
            const personelActiveLine = document.querySelector('.personel .activeLine');
            const activityLogActiveLine = document.querySelector('.activityLog .activeLine');

            const personelContent = document.querySelectorAll('.personel-content');
            const activityLogContent = document.querySelectorAll('.activityLog-content');

            // Function to handle toggling active class and showing active line
            function toggleActive(header, activeLine, contentToShow, contentToHide) {
                // Remove active from both headers
                personelHeader.classList.remove('active');
                activityLogHeader.classList.remove('active');
                
                // Hide both active lines
                personelActiveLine.style.display = 'none';
                activityLogActiveLine.style.display = 'none';

                // Add active class to clicked header and show corresponding line
                header.classList.add('active');
                activeLine.style.display = 'block';

                // Show and hide the corresponding content
                contentToShow.forEach(content => content.style.display = 'flex');
                contentToHide.forEach(content => content.style.display = 'none');
            }

            // Set personel-header as active by default on page load
            personelHeader.classList.add('active');
            personelActiveLine.style.display = 'block';
            
            // Display personel-content by default
            personelContent.forEach(content => content.style.display = 'flex');
            activityLogContent.forEach(content => content.style.display = 'none');

            // Add event listeners to the headers
            personelHeader.addEventListener('click', function() {
                toggleActive(personelHeader, personelActiveLine, personelContent, activityLogContent);
            });

            activityLogHeader.addEventListener('click', function() {
                toggleActive(activityLogHeader, activityLogActiveLine, activityLogContent, personelContent);
            });
        });


    </script>
    

    

   
    
    
</body>
</html>