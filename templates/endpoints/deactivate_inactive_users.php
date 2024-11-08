<?php
include("../../connection/conn.php");

$query = "UPDATE admin SET status = 'inactive' WHERE last_login < DATE_SUB(NOW(), INTERVAL 7 DAY)";
$conn->query($query);

$query = "UPDATE worker SET status = 'inactive' WHERE last_login < DATE_SUB(NOW(), INTERVAL 7 DAY)";
$conn->query($query);

?>