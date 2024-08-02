<?php
session_start();
include 'connection.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM user WHERE U_ID = '$delete_id'";
    if (mysqli_query($con, $delete_sql)) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
}

// Fetch all users
$user_sql = "SELECT * FROM user";
$user_result = mysqli_query($con, $user_sql);

// Fetch orders for a specific user
if (isset($_GET['view_orders'])) {
    $user_id = $_GET['view_orders'];
    $order_sql = "SELECT * FROM orders WHERE U_ID = '$user_id'";
    $order_result = mysqli_query($con, $order_sql);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - PC Builder Application</title>
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
        .container {
            max-width: 1000px;
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
	<h2>Manage Users</h2>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($user_result) > 0) {
                while ($row = mysqli_fetch_assoc($user_result)) {
                    echo "<tr>";
                    echo "<td>" . $row["U_ID"] . "</td>";
                    echo "<td>" . $row["Name"] . "</td>";
                    echo "<td>" . $row["Email"] . "</td>";
                    echo "<td>" . $row["Phone_Number"] . "</td>";
                    echo "<td>" . $row["Address"] . "</td>";
                    echo "<td>
                            <a href='manage_users.php?delete_id=" . $row["U_ID"] . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a> |
                            <a href='manage_users.php?view_orders=" . $row["U_ID"] . "'>Orders</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    if (isset($order_result)) {
    echo "<h2>User Orders</h2>";
    echo "<table border='1' cellspacing='0' cellpadding='10'>";
    echo "<thead><tr><th>Order ID</th><th>User ID</th><th>Build ID</th><th>Price</th><th>Status</th></tr></thead>";
    echo "<tbody>";
    if (mysqli_num_rows($order_result) > 0) {
        while ($order_row = mysqli_fetch_assoc($order_result)) {
            echo "<tr>";
            echo "<td>" . $order_row["Order_ID"] . "</td>";
            echo "<td>" . $order_row["U_ID"] . "</td>";
            echo "<td>" . $order_row["Build_ID"] . "</td>";
            echo "<td>" . $order_row["Price"] . "</td>";
            echo "<td>" . $order_row["Status"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No orders found for this user</td></tr>";
    }
    echo "</tbody></table>";
}

    ?>
</div>

</body>
</html>
