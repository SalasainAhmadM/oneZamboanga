<?php
session_start();
require_once '../../connection/auth.php';
date_default_timezone_set('Asia/Manila');

// Set the timeout duration (in seconds)
define('INACTIVITY_LIMIT', 300); // 5 minutes

// Check if the user has been inactive for the defined limit
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_LIMIT)) {
    // Destroy the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: ../../login.php?message=Session expired due to inactivity.");
    exit();
}

// Update the last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Validate session role
validateSession('superadmin');

?>
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
    <!-- Include SweetAlert2 and Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>
<style>
    .dropdown-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-barangay,
    .input-position {
        width: 100%;
        padding: 8px 32px 8px 8px;
        box-sizing: border-box;
    }

    .add-icon {
        position: absolute;
        right: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: var(--clr-slate600);
        border: 1px solid #ccc;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: background-color 0.3s, color 0.3s;
    }

    .add-icon:hover {
        background-color: #000;
        color: #fff;
    }

    .dropdown-list {
        list-style-type: none;
        margin: 0;
        padding: 0;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .dropdown-list li {
        padding: 8px;
        cursor: pointer;
    }

    .dropdown-list li:hover {
        background-color: #f0f0f0;
    }
</style>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>
            Swal.fire({
                icon: '{$_SESSION['message_type']}',
                title: '{$_SESSION['message']}'
            });
          </script>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
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
                            <a href="barangayAcc.php">Accounts</a>

                            <!-- next page -->
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="#">Create barangay admin</a>
                        </div>




                        <!-- <a class="addBg-admin" href="../admin/addAdmin.php">
                            <i class="fa-solid fa-user-plus"></i>
                        </a> -->
                    </div>
                </div>
            </header>

            <div class="main-wrapper">
                <div class="main-container">
                    <h2 class="admin-reg">Admin Registration</h2>

                    <form id="adminForm" action="../endpoints/create_admin.php" method="POST"
                        enctype="multipart/form-data">
                        <div class="admin-input_container">
                            <div class="admin-input">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="input-lastName"
                                    placeholder="Input Last Name" required>
                            </div>

                            <div class="admin-input">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="input-firstName"
                                    placeholder="Input First Name" required>
                            </div>

                            <div class="admin-input">
                                <label for="middleName">Middle Name</label>
                                <input type="text" id="middleName" name="middleName" class="input-middleName"
                                    placeholder="Input Middle Initial">
                            </div>

                            <div class="admin-input">
                                <label for="extensionName">Extension Name</label>
                                <input type="text" id="extensionName" name="extensionName" class="input-extensionName"
                                    placeholder="e.g., Jr.">
                            </div>

                            <div class="admin-input">
                                <label for="gender">Sex</label>
                                <select id="gender" name="gender" class="input-gender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="admin-input">
                                <label for="birthday">Birthday</label>
                                <input type="date" id="birthday" name="birthday" class="input-birthday"
                                    placeholder="Select Birthday" required>
                            </div>

                            <div class="admin-input">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" class="input-age" placeholder="Input Age"
                                    required readonly>
                            </div>

                            <script>

                                document.getElementById('birthday').addEventListener('change', function () {
                                    const birthday = new Date(this.value);

                                    // Get the current date in Asia/Manila timezone
                                    const now = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" }));

                                    if (birthday > now) {
                                        document.getElementById('age').value = 0;
                                        return;
                                    }

                                    let age = now.getFullYear() - birthday.getFullYear();
                                    const isBirthdayPassedThisYear =
                                        now.getMonth() > birthday.getMonth() ||
                                        (now.getMonth() === birthday.getMonth() && now.getDate() >= birthday.getDate());

                                    if (!isBirthdayPassedThisYear) {
                                        age--;
                                    }

                                    document.getElementById('age').value = age > 0 ? age : 0;
                                });
                            </script>

                            <div class="admin-input">
                                <label for="city">City/Province</label>
                                <input type="text" id="city" name="city" class="input-city" placeholder=""
                                    value="Zamboanga City" required>
                            </div>

                            <div class="admin-input">
                                <label for="barangay">Barangay</label>
                                <div class="dropdown-container">
                                    <input type="text" id="barangay" name="barangay" class="input-barangay"
                                        placeholder="Enter Barangay" required autocomplete="off">
                                    <span class="add-icon" id="add-barangay-btn">+</span>
                                    <ul id="barangay-dropdown" class="dropdown-list"></ul>
                                </div>
                            </div>


                            <div class="admin-input">
                                <label for="contactInfo">Contact Information</label>
                                <input type="number" id="contactInfo" name="contactInfo" class="input-contactInfo"
                                    placeholder="Input Contact Info" required>
                            </div>

                            <div class="admin-input">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="input-email"
                                    placeholder="Input Email" required>
                            </div>

                            <div class="admin-input">
                                <label for="position">Position</label>
                                <div class="dropdown-container">
                                    <input type="text" id="position" name="position" class="input-position"
                                        placeholder="e.g., Barangay Captain" required autocomplete="off">
                                    <ul id="position-dropdown" class="dropdown-list">
                                        <li>Barangay Captain</li>
                                        <li>SK Kagawad</li>
                                        <li>Intern</li>
                                        <li>Volunteer</li>
                                    </ul>
                                </div>
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    const positionInput = document.getElementById("position");
                                    const positionDropdown = document.getElementById("position-dropdown");

                                    // Show dropdown when input is focused
                                    positionInput.addEventListener("focus", function () {
                                        positionDropdown.style.display = "block";
                                    });

                                    // Set input value when a position is selected
                                    positionDropdown.addEventListener("click", function (event) {
                                        if (event.target.tagName === "LI") {
                                            positionInput.value = event.target.textContent;
                                            positionDropdown.style.display = "none"; // Hide dropdown
                                        }
                                    });

                                    // Hide dropdown if clicked outside
                                    document.addEventListener("click", function (event) {
                                        if (!positionInput.contains(event.target) && !positionDropdown.contains(event.target)) {
                                            positionDropdown.style.display = "none";
                                        }
                                    });
                                });

                            </script>

                            <div class="admin-input">
                                <label for="proofOfAppointment">Proof of appointment</label>
                                <input type="file" id="proofOfAppointment" name="proofOfAppointment"
                                    class="input-proofOfAppointment" required>
                            </div>

                            <div class="admin-input">
                                <label for="barangay_logo">Add barangay logo (optional)</label>
                                <input type="file" id="barangay_logo" name="barangay_logo" class="input-barangay_logo">
                            </div>

                            <div class="admin-input">
                                <label for="photo">Add your photo</label>
                                <input type="file" id="photo" name="photo" class="input-photo">
                            </div>
                        </div>

                        <!-- <div class="admin-photo">
                            <label for="photo">Add your photo</label>
                            <input type="file" id="photo" name="photo" class="input-photo">
                        </div> -->

                        <div class="admin-cta_container">
                            <div class="admin-cta">
                                <button type="button" class="mainBtn adminCreate" id="create">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </main>

    </div>
    <div class="loader"></div>

    <script>
        document.getElementById('create').addEventListener('click', function () {
            const form = document.getElementById('adminForm');

            // Check if form is valid
            if (form.checkValidity()) {
                // Trigger SweetAlert
                Swal.fire({
                    title: 'Confirm Registration',
                    text: 'Please confirm that you would like to proceed',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                form.reportValidity();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const barangayInput = document.getElementById("barangay");
            const dropdownList = document.getElementById("barangay-dropdown");

            // Fetch barangays from the server
            async function fetchBarangays() {
                try {
                    const response = await fetch('../endpoints/fetch_barangays.php'); // PHP endpoint to get barangays
                    const barangays = await response.json();
                    return barangays;
                } catch (error) {
                    console.error("Error fetching barangays:", error);
                    return [];
                }
            }

            // Populate and show dropdown
            function populateDropdown(barangays, query = "") {
                const filteredBarangays = barangays.filter(barangay =>
                    barangay.name.toLowerCase().includes(query.toLowerCase())
                );

                dropdownList.innerHTML = ""; // Clear previous list

                filteredBarangays.forEach(barangay => {
                    const listItem = document.createElement("li");
                    listItem.textContent = barangay.name;
                    listItem.addEventListener("click", function () {
                        barangayInput.value = barangay.name; // Set input value
                        dropdownList.style.display = "none"; // Hide dropdown
                    });
                    dropdownList.appendChild(listItem);
                });

                dropdownList.style.display = filteredBarangays.length ? "block" : "none";
            }

            // Initial fetching and setup
            let barangays = [];
            fetchBarangays().then(fetchedBarangays => {
                barangays = fetchedBarangays;

                // Show all barangays when input is focused
                barangayInput.addEventListener("focus", function () {
                    populateDropdown(barangays);
                });

                // Filter barangays when typing
                barangayInput.addEventListener("input", function () {
                    populateDropdown(barangays, barangayInput.value);
                });

                // Hide dropdown if clicked outside
                document.addEventListener("click", function (event) {
                    if (!barangayInput.contains(event.target) && !dropdownList.contains(event.target)) {
                        dropdownList.style.display = "none";
                    }
                });
            });

            document.getElementById("add-barangay-btn").addEventListener("click", function () {
                Swal.fire({
                    title: 'Add Barangay',
                    input: 'text',
                    inputLabel: 'Barangay Name',
                    inputPlaceholder: 'Enter barangay name',
                    showCancelButton: false, // Hide default cancel button
                    showConfirmButton: true,
                    showDenyButton: true, // Adds a second button
                    confirmButtonText: 'Add',
                    denyButtonText: 'Manage',
                    allowOutsideClick: false,
                    customClass: {
                        container: 'swal-custom-container',
                        confirmButton: 'swal2-confirm',
                        denyButton: 'swal2-manage'
                    },
                    preConfirm: (barangayName) => {
                        if (!barangayName) {
                            Swal.showValidationMessage('Please enter a barangay name');
                        } else {
                            return barangayName;
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const barangayName = result.value;

                        // Send the new barangay to the server
                        fetch('../endpoints/add_barangay.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name: barangayName })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Barangay Added',
                                        text: `${barangayName} has been added!`,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else if (data.error === 'Barangay already exists') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Duplicate Barangay',
                                        text: `${barangayName} already exists. Please add a different barangay.`
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to add barangay. Please try again.'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error("Error adding barangay:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.'
                                });
                            });
                    } else if (result.isDenied) {
                        // Fetch and display barangays when "Manage" is clicked
                        fetch('../endpoints/fetch_barangays.php')
                            .then(response => response.json())
                            .then(data => {
                                // Generate a table for barangays
                                let tableHtml = `
                                                        <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
                                                        <thead>
                                                        <tr>
                                                        <th style="display: none">ID</th>
                                                        <th>Name</th>
                                                        <th style="width: 100px; text-align: center;">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        ${data.map(barangay => `
                                                        <tr>
                                                        <td style="display: none">${barangay.id}</td>
                                                        <td>${barangay.name}</td>
                                                        <td style="text-align: center; padding: 8px;">
                                                        <button class="edit-btn" data-id="${barangay.id}" title="Edit" style="padding: 4px;">✏️</button>
                                                        <button class="delete-btn" data-id="${barangay.id}" title="Delete" style="padding: 4px;">🗑️</button>
                                                        </td>
                                                        </tr>
                                                        `).join('')}
                                                        </tbody>
                                                        </table>
                                                        `;


                                Swal.fire({
                                    title: 'Manage Barangays',
                                    html: tableHtml,
                                    width: '600px',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    confirmButtonText: 'Close',
                                    didOpen: () => {
                                        // Add event listeners for Edit and Delete buttons
                                        document.querySelectorAll(".edit-btn").forEach(btn => {
                                            btn.addEventListener("click", function () {
                                                const barangayId = this.dataset.id;
                                                Swal.fire({
                                                    title: 'Edit Barangay',
                                                    input: 'text',
                                                    inputLabel: 'Barangay Name',
                                                    inputValue: data.find(b => b.id == barangayId).name,
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Save',
                                                    preConfirm: (newName) => {
                                                        if (!newName) {
                                                            Swal.showValidationMessage('Please enter a barangay name');
                                                        } else {
                                                            return fetch('../endpoints/edit_barangay.php', {
                                                                method: 'POST',
                                                                headers: { 'Content-Type': 'application/json' },
                                                                body: JSON.stringify({ id: barangayId, name: newName })
                                                            })
                                                                .then(response => response.json())
                                                                .then(result => {
                                                                    if (result.success) {
                                                                        Swal.fire('Saved!', '', 'success');
                                                                    } else {
                                                                        Swal.showValidationMessage('Error saving barangay');
                                                                    }
                                                                });
                                                        }
                                                    }
                                                });
                                            });
                                        });

                                        document.querySelectorAll(".delete-btn").forEach(btn => {
                                            btn.addEventListener("click", function () {
                                                const barangayId = this.dataset.id;
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'This action cannot be undone.',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Delete',
                                                    preConfirm: () => {
                                                        return fetch('../endpoints/delete_barangay.php', {
                                                            method: 'POST',
                                                            headers: { 'Content-Type': 'application/json' },
                                                            body: JSON.stringify({ id: barangayId })
                                                        })
                                                            .then(response => response.json())
                                                            .then(result => {
                                                                if (result.success) {
                                                                    Swal.fire('Deleted!', '', 'success');
                                                                } else {
                                                                    Swal.showValidationMessage('Error deleting barangay');
                                                                }
                                                            });
                                                    }
                                                });
                                            });
                                        });
                                    }
                                });
                            })
                            .catch(error => {
                                console.error("Error fetching barangays:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load barangays. Please try again.'
                                });
                            });
                    }
                });
            });
        });

        // Save form data in localStorage
        document.querySelectorAll("#adminForm input, #adminForm select").forEach((input) => {
            input.addEventListener("input", function () {
                localStorage.setItem(this.id, this.value);
            });
        });

        // Restore form data from localStorage
        window.addEventListener("load", function () {
            document.querySelectorAll("#adminForm input, #adminForm select").forEach((input) => {
                if (localStorage.getItem(input.id)) {
                    input.value = localStorage.getItem(input.id);
                }
            });
        });

        // Clear localStorage and reload on successful submission
        document.getElementById("adminForm").addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent default submission
            const formData = new FormData(this);
            fetch(this.action, { method: "POST", body: formData })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        localStorage.clear(); // Clear saved data
                        location.reload(); // Reload page
                    } else {
                        alert("Failed to submit the form: " + data.message);
                    }
                })
                .catch((err) => console.error("Error:", err));
        });
    </script>
    <!-- import sidebar -->
    <script src="../../includes/sidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <!-- import logout -->
    <script src="../../includes/logout.js"></script>

    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</body>

</html>