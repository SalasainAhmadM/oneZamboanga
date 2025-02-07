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
    <link rel="stylesheet" href="../../assets/styles/utils/myProfile.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

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
                            <a href="viewAssignedEC.php">Tetuan Central School</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="personnel.php">Team</a>
                        </div>





                        <!-- <button class="addBg-admin">
                            Create
                        </button> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">

                    <div class="profile-left">
                        <div class="left-wrapper">

                            <div class="profileOption active" id="profile">
                                <i class="fa-regular fa-user"></i>
                                <p>Profile</p>
                            </div>



                            <!-- <div class="profileOption" id="password">
                                <i class="fa-solid fa-lock"></i>
                                <p>Change Password</p>
                            </div> -->
                        </div>
                    </div>


                    <!-- ======myProfile====== -->
                    <div class="profile-right" id="myProfile">
                        <h2>Monkey D. Luffy</h2>

                        <div class="right-wrapper">
                            <img src="../../assets/img/undraw_male_avatar_g98d.svg" alt="">

                            <ul class="profileDetails">
                                <li>
                                    <p>City/Province: <span>Tetuan chuchu chu</span></p>
                                </li>
                                <li>
                                    <p>Gender: <span>Male</span></p>
                                </li>
                                <li>
                                    <p>Barangay: <span>Tetuan</span></p>
                                </li>
                                <li>
                                    <p>Contact Information: <span>09090909</span></p>
                                </li>
                                <li>
                                    <p>Email: <span>youremail@gmail.com</span></p>
                                </li>
                                <li>
                                    <p>Position: <span>Kagawad</span></p>
                                </li>
                            </ul>

                            <button id="openProof">Proof of appointment</button>
                        </div>

                        <div class="proof">
                            <i class="fa-solid fa-xmark" id="closeProof"></i>
                            <img src="../../assets/img/captain.webp" alt="">
                        </div>

                    </div>






                </div>
            </div>
        </main>

    </div>



    <!-- sidebar import js -->
    <script src="../../includes/sidebarWokers.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>


    <!-- pop up add form -->
    <script src="../../assets/src/utils/addEc-popup.js"></script>


    <script>
        // Select all profileOption elements
        const profileOptions = document.querySelectorAll('.profileOption');

        // Function to handle click event
        profileOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove active class from all options
                profileOptions.forEach(opt => opt.classList.remove('active'));

                // Add active class to the clicked option
                option.classList.add('active');
            });
        });

    </script>


    <!-- profile options -->
    <script>
        const profileBtn = document.getElementById('profile')
        const editBtn = document.getElementById('edit');
        const passBtn = document.getElementById('password');
        const myProfile = document.getElementById('myProfile');
        const editProfile = document.getElementById('editProfile');
        const passProfile = document.getElementById('passProfile');

        // show profile
        profileBtn.addEventListener('click', function () {
            myProfile.style.display = 'block';
            editProfile.style.display = 'none';
            passProfile.style.display = 'none';
        });

        // show edit
        editBtn.addEventListener('click', function () {
            editProfile.style.display = 'block';
            myProfile.style.display = 'none';
            passProfile.style.display = 'none';
        });

        // show pass
        passBtn.addEventListener('click', function () {
            passProfile.style.display = 'block';
            myProfile.style.display = 'none';
            editProfile.style.display = 'none';
        });
    </script>


    <!-- proof of appointment -->
    <script>
        const openProof = document.getElementById('openProof');
        const proof = document.querySelector('.proof');
        const closeProof = document.getElementById('closeProof');

        openProof.addEventListener('click', function () {
            proof.style.display = 'block';

            console.log('hello');
        });

        closeProof.addEventListener('click', function () {
            proof.style.display = 'none';
        })

    </script>



    <!-- sweetalert popup messagebox add form-->
    <script>
        $('#save').on('click', function () {
            Swal.fire({
                title: "Save Changes?",
                text: "",
                icon: "question",
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