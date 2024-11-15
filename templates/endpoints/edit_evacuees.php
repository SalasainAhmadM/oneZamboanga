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
    $date = $_POST['date'];
    $gender = $_POST['gender_head'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age_head'];
    $occupation = $_POST['occupation_head'];
    $contact = $_POST['contact'];
    $monthly_income = $_POST['monthly_income'];
    $damage = isset($_POST['damage'])
        ? (is_array($_POST['damage']) ? implode(", ", $_POST['damage']) : htmlspecialchars($_POST['damage']))
        : null;

    $cost_damage = $_POST['cost_damage'];
    $position = isset($_POST['position']) ? implode(",", $_POST['position']) : '';
    $house_owner = $_POST['house_owner'];
    $evacuees_id = $_POST['evacuees_id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Update evacuee's data in the `evacuees` table
        $sql = "UPDATE evacuees 
        SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, date = ?, gender = ?, disaster_type = ?, 
            barangay = ?, birthday = ?, age = ?, occupation = ?, contact = ?, monthly_income = ?, damage = ?, 
            cost_damage = ?, position = ?, house_owner = ?, evacuation_center_id = ?
        WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssisisssssii",
            $first_name,
            $middle_name,
            $last_name,
            $extension_name,
            $date,
            $gender,
            $disaster_type,
            $barangay,
            $birthday,
            $age,
            $occupation,
            $contact,
            $monthly_income,
            $damage,
            $cost_damage,
            $position,
            $house_owner,
            $evacuation_center,
            $evacuees_id
        );
        $stmt->execute();


        // Update members
        // First, delete all existing members for the evacuee
        $delete_sql = "DELETE FROM members WHERE evacuees_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $evacuees_id);
        $delete_stmt->execute();

        // Insert updated members data
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

        // Insert into `evacuees_log` table
        $log_msg = "Updated";
        $log_sql = "INSERT INTO evacuees_log (log_msg, status, evacuees_id) VALUES (?, 'notify', ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("si", $log_msg, $evacuees_id);
        $log_stmt->execute();

        // Commit transaction
        $conn->commit();

        // Set session success message
        $_SESSION['message'] = "Evacuee information updated successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: ../barangay/evacueesFormEdit.php?id=$evacuees_id");
        exit();

    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollback();

        // Set session failure message
        $_SESSION['message'] = "Failed to update evacuee: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/evacueesFormEdit.php?id=$evacuees_id");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: ../barangay/evacueesFormEdit.php?id=$evacuees_id");
    exit();
}
?>