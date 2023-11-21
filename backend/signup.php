<?php
session_start();
if ($_POST) {
    include('database/connection.php');
    $newusername = $_POST['user_Name'];
    $email = $_POST['user_Email'];
    $password = $_POST['user_Password'];
    $query = "SELECT * FROM users WHERE user_name = '$newusername'";
    $result = $conn->query($query);
    if (!$result) {
        $conn->close();
        die("Query failed: " . mysqli_error($conn));
    }
    if ($result->num_rows > 0) {
        $_SESSION['same_name'] = true;
        $conn->close();
        header('Location: ..');
    }
    date_default_timezone_set('Asia/Calcutta');
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO users (user_name, user_email, user_password, user_created_at) VALUES ('$newusername', '$email', '$password', '$date')";
    $result = $conn->query($query);
    if (!$result) {
        $conn->close();
        die("Query failed: " . mysqli_error($conn));
    }
    $query = "SELECT * FROM users WHERE user_name='$newusername' AND user_password='$password'";
    $result = $conn->query($query);
    if (!$result) {
        $conn->close();
        die("Query failed: " . mysqli_error($conn));
    }
    if ($result->num_rows > 0) {
        $user = $result->fetch_all()[0];
        $_SESSION['user'] = $user;
        $_SESSION['first_login'] = true;
    }
    $query = "CREATE TABLE $newusername ( task_id INT AUTO_INCREMENT PRIMARY KEY, task_title VARCHAR(255) NOT NULL, task_desc TEXT, task_done BOOLEAN NOT NULL)";
    $result = $conn->query($query);
    if (!$result) {
        $conn->close();
        die("Query failed: " . mysqli_error($conn));
    }
    header('Location: ..');
}
?>