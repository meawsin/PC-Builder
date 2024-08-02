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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $update_sql = "UPDATE Orders SET Status = '$status' WHERE Order_ID = $order_id";
    mysqli_query($con, $update_sql);
}


$order_result = mysqli_query($con, "select * from orders order by Order_ID desc");

function getUserDetails($con, $user_id) {
    $user_sql = "SELECT Name, Phone_number, Address FROM User WHERE U_ID = '$user_id'";
    $user_result = mysqli_query($con, $user_sql);
    $user_row = mysqli_fetch_assoc($user_result);
    return $user_row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - PC Builder Application</title>
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
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User Name</th>
            <th>User Phone</th>
            <th>User Address</th>
            <th>Build ID</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($order_row = mysqli_fetch_assoc($order_result)) { ?>
            <tr>
                <td><?php echo $order_row['Order_ID']; ?></td>
                <?php
                // Get user details for the current order
                $user_id = $order_row['U_ID'];
                $user_details = getUserDetails($con, $user_id);
                ?>
                <td><?php echo $user_details['Name']; ?></td>
                <td><?php echo $user_details['Phone_number']; ?></td>
                <td><?php echo $user_details['Address']; ?></td>
                <td><?php echo $order_row['Build_ID']; ?></td>
                <td><?php echo $order_row['Price']; ?></td>
                <td><?php echo $order_row['Status']; ?></td>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order_row['Order_ID']; ?>">
                        <select name="status">
                            <option value="pending" <?php if ($order_row['Status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="dispatched" <?php if ($order_row['Status'] == 'dispatched') echo 'selected'; ?>>Dispatched</option>
                            <option value="delivered" <?php if ($order_row['Status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="canceled" <?php if ($order_row['Status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                        </select>
                        <input type="submit" value="Update">
                    </form>
                    <a href="printbuild.php?order_id=<?php echo $order_row['Order_ID']; ?>">Print Details</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
