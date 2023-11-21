<?php
include("database/connection.php");
session_start();
$user = $_SESSION['user'];
$id = $_POST['taskId'];
$tit = $_POST['taskname'];
$des = $_POST['taskdesc'];
$query = "UPDATE $user[1] SET task_title = '$tit', task_desc = '$des' WHERE task_id = $id";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
header('Location: ..')
    ?>