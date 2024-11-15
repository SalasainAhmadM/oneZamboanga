<?php
session_start();
require_once '../../connection/conn.php';

if (isset($_GET['id'])) {
    $evacuationId = $_GET['id'];

    // Fetch the evacuation center's name, admin_id, and admin's barangay for the notification message
    $fetchDetailsQuery = "SELECT ec.name, ec.admin_id, a.barangay 
FROM evacuation_center ec 
JOIN admin a ON ec.admin_id = a.id 
WHERE ec.id = ?";
    $stmtFetchDetails = $conn->prepare($fetchDetailsQuery);
    $stmtFetchDetails->bind_param("i", $evacuationId);
    $stmtFetchDetails->execute();
    $resultFetchDetails = $stmtFetchDetails->get_result();

    if ($resultFetchDetails->num_rows > 0) {
        $details = $resultFetchDetails->fetch_assoc();
        $name = $details['name'];
        $logged_in_id = $details['admin_id'];
        $barangayAdmin = $details['barangay'];  // Capture the barangay from the admin table
    } else {
        $_SESSION['message'] = "Evacuation center not found.";
        $_SESSION['message_type'] = "error";
        header("Location: ../barangay/viewEC.php");
        exit();
    }
    $stmtFetchDetails->close();


    // Prepare the SQL query to delete the record
    $sqlDelete = "DELETE FROM evacuation_center WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $evacuationId);

    if ($stmtDelete->execute()) {
        // Set success message and type for deletion
        $_SESSION['message'] = "Evacuation center deleted successfully!";
        $_SESSION['message_type'] = "success";

        // Insert notification
        $notification_msg = "Evacuation Center Deleted: " . $name;
        $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                              VALUES ('$logged_in_id', 'admin', '$notification_msg', 'notify')";

        if (!mysqli_query($conn, $notificationQuery)) {
            // Set warning message if the notification fails
            $_SESSION['message'] = "Evacuation center deleted, but notification failed.";
            $_SESSION['message_type'] = "warning";
        }

        // Insert notification for superadmin 
        $notification_msg = "Evacuation Center Deleted: " . $name . " at Barangay: " . $barangayAdmin;
        $notificationQuery = "INSERT INTO notifications (logged_in_id, user_type, notification_msg, status) 
                      VALUES ('1', 'admin', '$notification_msg', 'notify')";

        if (!mysqli_query($conn, $notificationQuery)) {
            $_SESSION['message'] = "Evacuation center deleted, but notification failed.";
            $_SESSION['message_type'] = "warning";
        }

    } else {
        // Set error message and type
        $_SESSION['message'] = "Error deleting record: " . $conn->error;
        $_SESSION['message_type'] = "error";
    }

    $stmtDelete->close();
    // Redirect back to the view page with the appropriate ID
    header("Location: ../barangay/evacuation.php");
    exit();
} else {
    // Set error message if no ID was provided
    $_SESSION['message'] = "No evacuation center ID provided.";
    $_SESSION['message_type'] = "error";
    header("Location: ../barangay/viewEC.php");
    exit();
}

?>