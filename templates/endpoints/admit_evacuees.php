<?php
session_start();
include("../../connection/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    // Retrieve and sanitize inputs for the main evacuee
    $evacuation_center = $_POST['evacuation_center'];
    $barangay = $_POST['barangay'];
    $disaster = $_POST['disaster'];
    $disaster_type = ($disaster == "others") ? $_POST['disaster_specify'] : $disaster;
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $extension_name = $_POST['extension_name'];
    $gender = $_POST['gender_head'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age_head'];
    $occupation = $_POST['occupation_head'];
    $contact = $_POST['contact'];
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
        header("Location: ../barangay/evacueesForm.php?id=$evacuation_center");
        exit();
    }

    // Fetch the admin_id associated with the evacuation center
    $evacuation_center_admin_query = "SELECT admin_id FROM evacuation_center WHERE id = ?";
    $evacuation_center_admin_stmt = $conn->prepare($evacuation_center_admin_query);
    $evacuation_center_admin_stmt->bind_param("i", $evacuation_center);
    $evacuation_center_admin_stmt->execute();
    $evacuation_center_admin_result = $evacuation_center_admin_stmt->get_result();
    $evacuation_center_admin_data = $evacuation_center_admin_result->fetch_assoc();
    $evacuation_center_admin_id = $evacuation_center_admin_data['admin_id'] ?? null;

    // Check if the current admin matches the evacuation center's admin
    if ($admin_id != $evacuation_center_admin_id) {
        // If admin_id doesn't match, update the admin_id to the one in the evacuation center
        $admin_id = $evacuation_center_admin_id;
        $status = "Admit"; // Set the status to Admit if admin_id doesn't match
    } else {
        $status = "Admitted"; // Otherwise, keep the status as Admitted
    }

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Insert into `evacuees` table including `evacuation_center`
        $sql = "INSERT INTO evacuees (first_name, middle_name, last_name, extension_name, gender, disaster_type, barangay, birthday, age, occupation, contact, monthly_income, damage, cost_damage, position, house_owner, admin_id, evacuation_center_id, origin_evacuation_center_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssisisisssiiis", $first_name, $middle_name, $last_name, $extension_name, $gender, $disaster_type, $barangay, $birthday, $age, $occupation, $contact, $monthly_income, $damage, $cost_damage, $position, $house_owner, $admin_id, $evacuation_center, $evacuation_center, $status);
        $stmt->execute();
        $evacuees_id = $stmt->insert_id;

        // Insert into `evacuees_log` table
        $log_msg = ($status == "Admit") ? "Pending for approval" : "Admitted";
        $log_sql = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, 'notify', ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("si", $log_msg, $evacuees_id);
        $log_stmt->execute();

        // Insert members if any
        if (!empty($_POST['firstName'])) {
            $member_sql = "INSERT INTO members (first_name, middle_name, last_name, extension_name, relation, education, gender, birthdate, age, occupation, evacuees_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $member_stmt = $conn->prepare($member_sql);

            foreach ($_POST['firstName'] as $index => $member_first_name) {
                $member_middle_name = $_POST['middleName'][$index];
                $member_last_name = $_POST['lastName'][$index];
                $member_extension = $_POST['extension'][$index];
                $member_relation = $_POST['relation'][$index];
                $member_education = $_POST['education'][$index];
                $member_gender = $_POST['gender'][$index];
                $member_birthdate = $_POST['birthdate'][$index];
                $member_age = $_POST['age'][$index];
                $member_occupation = $_POST['occupation'][$index];

                if (!empty($member_first_name) && !empty($member_last_name) && !empty($member_relation) && !empty($member_gender) && !empty($member_age)) {
                    $member_stmt->bind_param("ssssssssisi", $member_first_name, $member_middle_name, $member_last_name, $member_extension, $member_relation, $member_education, $member_gender, $member_birthdate, $member_age, $member_occupation, $evacuees_id);
                    $member_stmt->execute();
                }
            }
        }

        // Commit transaction if all inserts were successful
        $conn->commit();

        // Retrieve the name of the evacuation center based on the evacuation_center ID
        $evacuation_center_name_query = "SELECT name FROM evacuation_center WHERE id = ?";
        $evacuation_center_name_stmt = $conn->prepare($evacuation_center_name_query);
        $evacuation_center_name_stmt->bind_param("i", $evacuation_center);
        $evacuation_center_name_stmt->execute();
        $evacuation_center_name_result = $evacuation_center_name_stmt->get_result();
        $evacuation_center_name_data = $evacuation_center_name_result->fetch_assoc();
        $evacuation_center_name = $evacuation_center_name_data['name'] ?? '';

        // Insert into `feeds` table
        $feed_msg = "$first_name $middle_name $last_name admitted to $evacuation_center_name.";
        if ($status == "Admit") {
            $feed_msg = "$first_name $middle_name $last_name is pending for approval in $evacuation_center_name.";
        }
        $feeds_sql = "INSERT INTO feeds (logged_in_id, user_type, feed_msg, status) VALUES (?, 'admin', ?, 'notify')";
        $feeds_stmt = $conn->prepare($feeds_sql);
        $feeds_stmt->bind_param("is", $admin_id, $feed_msg);
        $feeds_stmt->execute();

        // Set session success message
        if ($status == "Admit") {
            $_SESSION['message'] = "Evacuees are pending for approval.";
        } else {
            $_SESSION['message'] = "Evacuees admitted successfully.";
        }
        $_SESSION['message_type'] = "success";
        header("Location: ../barangay/evacueesForm.php?id=$evacuation_center");
        exit();

    } catch (Exception $e) {
        // Rollback if any errors occur
        $conn->rollback();

        // Set session failure message
        $_SESSION['message'] = "Failed to admit evacuee: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/evacueesForm.php?id=$evacuation_center");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: ../barangay/evacueesForm.php?id=$evacuation_center");
    exit();
}
