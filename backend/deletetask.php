<?php
include('database/connection.php');
session_start();
$user = $_SESSION['user'];
$taskid = $_POST['taskID'];
$query = "SELECT * FROM $user[1] WHERE task_id = '$taskid'";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$findTask = $result->fetch_all();
$query = "DELETE FROM $user[1] WHERE task_id = '$taskid'";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
header('Location: ..');
?>