<?php
session_start();
include 'connection.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PC Builder Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
        }
        .top-bar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative;
        }
        .top-bar h1 {
            color: #ff0000; 
            margin: 0;
            cursor: pointer;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff0000; 
            color: #fff;
            text-decoration: none;
            margin: 10px;
            border-radius: 5px;
            border: none;
        }
        .button:hover {
            background-color: #cc0000; 
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="top-bar">
    <h1 onclick="window.location.href='admin_dashboard.php'">PC Builder Application</h1>
    <h2>Admin view</h2>
    <div class="top-right">
        Welcome, <?php echo $_SESSION["admin_username"]; ?>! <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Admin Dashboard</h2>
    <a href="admin_responses.php" class="button">Reply to Chats</a>
	<a href="promotion.php" class="button">Start an offer</a>
</div>
<div class="container">
    <a href="manage_orders.php" class="button">Manage Orders</a>
    <a href="manage_users.php" class="button">Manage Users</a>
	<a href="manage_product.php" class="button">Manage Products</a>
</div>

</body>
</html>
