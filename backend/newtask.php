<?php
session_start();
$user = $_SESSION['user'];
include('database/connection.php');
$title = $_POST['task_title'];
$desc = $_POST['task_desc'];
$query = "INSERT INTO $user[1] (task_title, task_desc, task_done) VALUES ('$title', '$desc', '0')";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$_SESSION['new_task'] = true;
header('Location: ..');
?>