<?php
include('database/connection.php');
session_start();
$user = $_SESSION['user'];
$taskid = $_POST['taskNOTDONEID'];
$query = "UPDATE $user[1] SET task_done = '0' WHERE task_id = $taskid;";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
header('Location: ..');
?>