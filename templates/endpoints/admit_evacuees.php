<?php
session_start();
include("../../connection/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Retrieve and sanitize inputs for the main evacuee
    $evacuation_center = $_POST['evacuation_center'];
    $barangay = $_POST['barangay'];
    $disaster = $_POST['disaster'];
    $disaster_type = ($disaster == "others") ? $_POST['disaster_specify'] : $disaster; // Assume "others" specify input
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $extension_name = $_POST['extension_name'];
    $gender = $_POST['gender_head'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age_head'];
    $occupation = $_POST['occupation_head'];
    $monthly_income = $_POST['monthly_income'];
    $damage = $_POST['damage'];
    $cost_damage = $_POST['cost_damage'];
    $position = $_POST['position'];
    $house_owner = $_POST['house_owner'];

    // Check if the evacuee already exists in the database
    $check_sql = "SELECT * FROM evacuees WHERE first_name = ? AND middle_name = ? AND last_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("sss", $first_name, $middle_name, $last_name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Evacuee already exists
        $_SESSION['message'] = "Family Head already admitted.";
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/evacueesForm.php");
        exit();
    }
    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Insert into `evacuees` table including `evacuation_center`
        $sql = "INSERT INTO evacuees (first_name, middle_name, last_name, extension_name, gender, disaster_type, barangay, birthday, age, occupation, monthly_income, damage, cost_damage, position, house_owner, admin_id, evacuation_center_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssississsii", $first_name, $middle_name, $last_name, $extension_name, $gender, $disaster_type, $barangay, $birthday, $age, $occupation, $monthly_income, $damage, $cost_damage, $position, $house_owner, $admin_id, $evacuation_center);
        $stmt->execute();
        $evacuees_id = $stmt->insert_id;

        // Insert members if any
        if (!empty($_POST['firstName'])) {
            $member_sql = "INSERT INTO members (first_name, middle_name, last_name, extension_name, relation, education, gender, age, occupation, evacuees_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $member_stmt = $conn->prepare($member_sql);

            foreach ($_POST['firstName'] as $index => $member_first_name) {
                $member_middle_name = $_POST['middleName'][$index];
                $member_last_name = $_POST['lastName'][$index];
                $member_extension = $_POST['extension'][$index];
                $member_relation = $_POST['relation'][$index];
                $member_education = $_POST['education'][$index];
                $member_gender = $_POST['gender'][$index];
                $member_age = $_POST['age'][$index];
                $member_occupation = $_POST['occupation'][$index];

                if (!empty($member_first_name) && !empty($member_last_name) && !empty($member_relation) && !empty($member_gender) && !empty($member_age)) {
                    $member_stmt->bind_param("sssssssisi", $member_first_name, $member_middle_name, $member_last_name, $member_extension, $member_relation, $member_education, $member_gender, $member_age, $member_occupation, $evacuees_id);
                    $member_stmt->execute();
                }
            }
        }

        // Commit transaction if all inserts were successful
        $conn->commit();

        // Set session success message
        $_SESSION['message'] = "Evacuees admitted successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: ../barangay/evacueesForm.php");
        exit();

    } catch (Exception $e) {
        // Rollback if any errors occur
        $conn->rollback();

        // Set session failure message
        $_SESSION['message'] = "Failed to admit evacuee: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/evacueesForm.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: ../barangay/evacueesForm.php");
    exit();
}
?>