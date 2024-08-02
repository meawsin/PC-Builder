<?php
session_start();
include 'connection.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

$adminname = $_SESSION["admin_username"];
$admin_sql = "SELECT A_ID FROM admin WHERE Name = '$adminname'";
$admin_result = mysqli_query($con, $admin_sql);
$admin_row = mysqli_fetch_assoc($admin_result);
$admin_id = $admin_row['A_ID'];

$update_sql = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = $_POST['message_id'];
    $response = $_POST['response'];
    $update_sql = "UPDATE messages SET Response = ?, Admin_ID = ? WHERE Message_ID = ?";
    $stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($stmt, "sss", $response, $admin_id, $message_id);
    mysqli_stmt_execute($stmt);
}

$message_sql = "SELECT messages.*, user.Name as UserName FROM messages JOIN user ON messages.User_ID = user.U_ID WHERE Response IS NULL";
$message_result = mysqli_query($con, $message_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Responses - PC Builder Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .top-bar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative;
        }
        .top-bar h1 {
            margin: 0;
            color: #ff0000;
            cursor: pointer;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
		textarea, input[type="submit"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }
        input[type="submit"] {
            background-color: #ff0000;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #cc0000;
        }
        .message {
            background-color: #444;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #444;
            color: #ff0000;
            font-weight: bold;
        }
        td {
            background-color: #555;
        }
        a {
            color: #ff0000;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        select {
            padding: 5px;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            background-color: #ff0000;
            color: #fff;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #cc0000;
        }
        .bottom-bar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
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
        <h2>Respond to User Questions</h2>
        <?php while ($row = mysqli_fetch_assoc($message_result)) { ?>
            <div class="message">
                <p><strong>User:</strong> <?php echo $row['UserName']; ?></p>
                <p><strong>Question:</strong> <?php echo $row['Message']; ?></p>
                <form action="admin_responses.php" method="POST">
                    <textarea name="response" placeholder="Type your response here..." required></textarea>
                    <input type="hidden" name="message_id" value="<?php echo $row['Message_ID']; ?>">
                    <input type="submit" value="Send Response">
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>
