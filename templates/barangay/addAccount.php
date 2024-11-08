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
    <link rel="stylesheet" href="../../assets/styles/utils/barangay.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/admin-form.css">
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
                            <a href="personnelPage.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create account for community worker</a>
                        </div>

                        


                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <h2 class="admin-reg">Registration Form</h2>

                    <form action="" method="">
                        <div class="admin-input_container">

                            <div class="admin-input">
                                <label for="">Last Name</label>
                                <input type="text" name="" placeholder="Input Last Name" required>
                            </div>
    
                            <div class="admin-input">
                                <label for="">First Name</label>
                                <input type="text" name="" placeholder="Input First Name" required>
                            </div>
    
                            <div class="admin-input">
                                <label for="">Middle Name</label>
                                <input type="text" name="" placeholder="Input Middle Initial">
                            </div>

                            <div class="admin-input">
                                <label for="">Extension Name</label>
                                <input type="text" name="" placeholder="e.g., Jr.">
                            </div>

                            <div class="admin-input">
                                <label for="">Gender</label>
                                <select name="sex" id="" required>
                                    <option value="" >Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
    
                            <div class="admin-input">
                                <label for="">City/Province</label>
                                <input type="text" name="" placeholder="" value="Zamboanga City" required>
                            </div>

                            <div class="admin-input">
                                <label for="">Barangay</label>
                                <select name="barangay" id="" required>
                                    <option value="" >Select</option>
                                    <option value="tetuan">Tetuan</option>
                                    <option value="tugbungan">Tugbungan</option>
                                </select>
                            </div>
    
                            <div class="admin-input">
                                <label for="">Contact Information</label>
                                <input type="number" name="" placeholder="Input Contact Info" required>
                            </div>
    
                            <div class="admin-input">
                                <label for="">Email</label>
                                <input type="email" name="" placeholder="Input Email" required>
                            </div>

                            <div class="admin-input">
                                <label for="">Position</label>
                                <input type="Text" name="" placeholder="e.g., Barangay Captain" required>
                            </div>

                            <div class="admin-input">
                                <label for="">Proof of appointment</label>
                                <input id="fileInput" type="file" required/>
                            </div>

                            <div class="admin-input">
                                <label for="">Add barangay logo (optional) </label>
                                <input id="fileInput" type="file" />
                            </div>

                            

                        </div>

                        <div class="admin-photo">
                            <label for="">Add your photo</label>
                            <input id="fileInput" type="file" />
                        </div>

                        <div class="admin-cta_container">
                            <div class="admin-cta">
                                <button class="mainBtn adminCreate" id="create">Create</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            

            
            
            
            
        </main>

        


    </div>

    
    <!-- import sidebar -->
    <script src="../../includes/bgSidebar.js"></script>

    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>
    

    <script>
        $('#create').on('click', function() {
            Swal.fire({
            title: "Create Account?",
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
                text: "Your username and password have been sent to your email.",
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