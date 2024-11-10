<?php
session_start();
include("../../connection/conn.php");

if (isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Fetch the admin's details from the database
    $sql = "SELECT first_name, middle_name, last_name, extension_name, username, email, image, proof_image, gender, city, barangay, contact, position 
            FROM admin 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_name = trim($admin['first_name'] . ' ' . $admin['middle_name'] . ' ' . $admin['last_name'] . ' ' . $admin['extension_name']);
        $barangay = $admin['barangay'];
    } else {
        header("Location: ../../login.php");
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;
}
// Fetch the evacuation centers associated with this admin
$sql = "SELECT id, name, location, image, capacity 
FROM evacuation_center 
WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$centers_result = $stmt->get_result();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--styles-->

    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="../../assets/styles/utils/dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/utils/ecenter.css">
    <link rel="stylesheet" href="../../assets/styles/utils/container.css">
    <link rel="stylesheet" href="../../assets/styles/utils/addEvacuees.css">
    <link rel="stylesheet" href="../../assets/styles/utils/messagebox.css">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>One Zamboanga: Evacuation Center Management System</title>
</head>

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


                        <form action="../endpoints/admit_evacuees.php" method="POST" enctype="multipart/form-data">
                            <div class="ec-info">
                                <div class="ecInfo">
                                    <label for="evacuation_center">Evacuation Center</label>
                                    <span>:</span>
                                    <select name="evacuation_center" id="evacuation_center" required>
                                        <option value="">Select</option>
                                        <?php while ($center = $centers_result->fetch_assoc()): ?>
                                            <option value="<?= $center['id']; ?>">
                                                <?= htmlspecialchars($center['name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="ecInfo">
                                    <label for="barangay">Barangay</label>
                                    <span>:</span>
                                    <input type="text" name="barangay" id="barangay"
                                        value="<?= htmlspecialchars($barangay); ?>" required>
                                </div>

                                <div class="ecInfo">
                                    <label for="disaster">Type of Disaster</label>
                                    <span>:</span>
                                    <select name="disaster" id="dSelect" onchange="toggleInput()">
                                        <option value="">Select</option>
                                        <option value="Flood">Flood Incident</option>
                                        <option value="Fire">Fire Incident</option>
                                        <option value="others">Others</option>
                                    </select>
                                    <input type="text" name="disaster_specify" placeholder="Specify.." id="dInput"
                                        style="display: none;">
                                </div>
                                <div class="ecInfo">
                                    <label for="date">Date</label>
                                    <span>:</span>
                                    <input name="date" type="date" required>
                                </div>
                            </div>

                            <div class="headFam">
                                <label for="">Name of Family Head:</label>
                                <div class="details-container">
                                    <div class="headFam-details">
                                        <input type="text" name="last_name" id="lastName" required>
                                        <label class="details" for="last_name">Last Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" name="first_name" id="firstName" required>
                                        <label class="details" for="first_name">First Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" name="middle_name" id="middleName" required>
                                        <label class="details" for="middle_name">Middle Name</label>
                                    </div>
                                    <div class="headFam-details">
                                        <input type="text" name="extension_name" class="age" id="extensionName">
                                        <label class="details" for="extension_name">Extension</label>
                                    </div>
                                </div>

                                <div class="occupation-container">
                                    <div class="headFam-details">
                                        <input type="date" name="birthday" class="age" required>
                                        <label class="details" for="birthday">Birthdate</label>
                                    </div>

                                    <div class="headFam-details">
                                        <input type="number" name="age_head" class="age_head" required>
                                        <label class="details" for="age_head">Age</label>
                                    </div>

                                    <div class="headFam-details">
                                        <select name="gender_head" class="age" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <label class="details" for="gender_head">Sex</label>
                                    </div>
                                    <div class="occupation">
                                        <label for="occupation_head">Occupation:</label>
                                        <input type="text" name="occupation_head" required>
                                    </div>

                                    <div class="income">
                                        <label for="monthly_income">Total Monthly Income:</label>
                                        <input type="number" name="monthly_income" required>
                                    </div>
                                </div>

                                <div class="houseStatus">
                                    <div class="statusOccupancy">
                                        <div class="titleStat">
                                            <p>Status of Occupancy:</p>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="position" value="Owner" id="houseOwnerCheckbox"
                                                onclick="fillHouseOwner()">
                                            <label for="position">House Owner</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="position" value="Sharer">
                                            <label for="position">Sharer</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="position" value="Renter">
                                            <label for="position">Renter</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="position" value="Boarder">
                                            <label for="position">Boarder</label>
                                        </div>
                                    </div>

                                    <div class="damaged">
                                        <div class="titleStat">
                                            <p>Damaged:</p>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="damage[]" value="totally">
                                            <label for="damage">Totally Damaged</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <input type="checkbox" name="damage[]" value="partially">
                                            <label for="damage">Partially Damaged</label>
                                        </div>
                                        <div class="checkbox-info">
                                            <label for="cost_damage">Estimated Cost of Damaged:</label>
                                            <input type="number" name="cost_damage">
                                        </div>
                                    </div>
                                </div>

                                <div class="owner">
                                    <label for="house_owner">Name of House owner:</label>
                                    <input type="text" name="house_owner" id="houseOwner" required>
                                </div>
                            </div>

                            <div class="addMembers-container" id="addMembersContainer">
                                <p class="memberTitle">Add Members:</p>
                                <div class="member-details" id="memberDetailsContainer">
                                    <div class="member-input">
                                        <label>Last Name: </label>
                                        <input type="text" name="lastName[]">
                                    </div>
                                    <div class="member-input">
                                        <label>First Name: </label>
                                        <input type="text" name="firstName[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Middle Name: </label>
                                        <input type="text" name="middleName[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Extension: </label>
                                        <input type="text" placeholder="e.g., Jr." name="extension[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Relation to Family Head:</label>
                                        <input type="text" name="relation[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Age:</label>
                                        <input type="number" name="age[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Gender:</label>
                                        <select name="gender[]">
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="member-input">
                                        <label>Education:</label>
                                        <input type="text" name="education[]">
                                    </div>
                                    <div class="member-input">
                                        <label>Occupation:</label>
                                        <input type="text" name="occupation[]">
                                    </div>
                                </div>
                            </div>

                            <div id="membersContainer"></div>
                            <button onclick="addMemberForm(event)" class="addBtn">Add Member</button>

                            <div class="addEvacuees">
                                <button type="submit" class="mainBtn" id="create">Admit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>

    </div>


    <script>
        function validateForm(event) {
            event.preventDefault(); // Prevent form submission until validated

            // Validate required fields (adjust field names as needed)
            const evacuationCenter = document.getElementById('evacuation_center').value;
            const barangay = document.getElementById('barangay').value;
            const firstName = document.querySelector('input[name="first_name"]').value;
            const lastName = document.querySelector('input[name="last_name"]').value;

            if (!evacuationCenter || !barangay || !firstName || !lastName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.',
                });
            } else {
                Swal.fire({
                    icon: 'question',
                    title: 'Confirm Submission',
                    text: 'Are you sure you want to submit this form?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector('form').submit(); // Submit the form if confirmed
                    }
                });
            }
        }

        // Attach event listener to the form's submit button
        document.querySelector('form').addEventListener('submit', validateForm);
    </script>

    <!-- sidebar import js -->
    <script src="../../includes/bgSidebar.js"></script>

    <!-- import logo -->
    <script src="../../includes/logo.js"></script>

    <script src="../../includes/logout.js"></script>


    <!-- sidebar menu -->
    <script src="../../assets/src/utils/menu-btn.js"></script>

    <script>
        let memberCount = 1;

        function addMemberForm(event) {
            event.preventDefault();

            if (memberCount >= 20) {
                alert("Maximum of 20 members reached.");
                return;
            }

            // Clone the member form
            const memberContainer = document.getElementById("memberDetailsContainer");
            const clone = memberContainer.cloneNode(true);

            // Create a wrapper div for each member form and add a delete button
            const memberWrapper = document.createElement("div");
            memberWrapper.classList.add("member-wrapper");

            // Add the cloned form and delete button to the wrapper
            memberWrapper.appendChild(clone);

            // Add a delete button with Fssont Awesome icon
            const deleteButton = document.createElement("button");
            deleteButton.innerHTML = '<i class="fa-solid fa-xmark"></i> Remove';
            deleteButton.classList.add("deleteBtn");
            deleteButton.onclick = function () {
                memberWrapper.remove();
                memberCount--;
            };

            // Append delete button and line break after the form
            memberWrapper.appendChild(deleteButton);
            memberWrapper.appendChild(document.createElement("br"));

            // Append the member wrapper to the main container
            document.getElementById("membersContainer").appendChild(memberWrapper);
            memberCount++;
        }
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

        document.addEventListener('click', function (event) {
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

        function fillHouseOwner() {
            const checkbox = document.getElementById('houseOwnerCheckbox');
            const lastName = document.getElementById('lastName').value;
            const firstName = document.getElementById('firstName').value;
            const middleName = document.getElementById('middleName').value;
            const extensionName = document.getElementById('extensionName').value;
            const houseOwnerField = document.getElementById('houseOwner');

            if (checkbox.checked) {
                houseOwnerField.value = ` ${firstName} ${middleName} ${lastName} ${extensionName}`.trim();
            } else {
                houseOwnerField.value = ''; // Clear the field if the checkbox is unchecked
            }
        }
    </script>

    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>