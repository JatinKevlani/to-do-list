<?php
include('database/connection.php');
session_start();
$user = $_SESSION['user'];
$taskid = $_POST['taskDONEID'];
$query = "UPDATE $user[1] SET task_done = '1' WHERE task_id = $taskid;";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
header('Location: ..');
?>