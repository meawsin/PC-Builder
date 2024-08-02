<?php
include('connection.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['un'];
    $password = $_POST['pw'];

    $admin_sql = "SELECT * FROM admin WHERE name = '$username' AND password = '$password'";
    $admin_result = mysqli_query($con, $admin_sql);
    
    if (mysqli_num_rows($admin_result) == 1) {
        $_SESSION["admin_username"] = $username;

        header("Location: admin_dashboard.php");
        exit;
    }
    
    $user_sql = "SELECT * FROM user WHERE name = '$username' AND password = '$password'";
    $user_result = mysqli_query($con, $user_sql);

    if (mysqli_num_rows($user_result) == 1) {

        $_SESSION["username"] = $username;

        header("Location: index.php");
        exit;
    } else {

        header("Location: login.php?error=Invalid username or password");
        exit;
    }
}
?>
