<?php

include 'header.php';
include 'connection.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$sql_user_id = "SELECT U_ID FROM User WHERE Name = '$username'";
$result_user_id = $con->query($sql_user_id);
if ($result_user_id->num_rows > 0) {
    $row_user_id = $result_user_id->fetch_assoc();
    $user_id = $row_user_id['U_ID'];

$sql_orders = "SELECT o.Order_ID, DATE_FORMAT(b.Build_Date, '%Y-%m-%d') AS Build_Date, b.Build_Number, o.Status
                FROM Orders o
                INNER JOIN builds b ON o.Build_ID = b.Build_ID
                WHERE o.U_ID = '$user_id'
                ORDER BY o.Order_ID DESC";
$result_orders = $con->query($sql_orders);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <style>
        /* Basic styling for demonstration purposes */
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
        }
        .top-bar h1 {
            color: #ff0000; /* Red text color */
            margin: 0;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
        .container {
            width: 80%; /* Adjust the width as needed */
            margin: 20px auto; /* Center the container horizontally */
            text-align: center;
        }
        .components-table {
            width: 100%; /* Make the table responsive */
            margin-top: 20px; /* Add space above the table */
            border-collapse: collapse; /* Collapse border spacing */
        }
        .components-table th, .components-table td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: center;
        }
        .components-table th {
            background-color: #444;
            color: #ff0000; /* Red color for table heading */
        }
        .components-table td {
            background-color: #333; /* Dark background for table cells */
        }
        .components-table a {
            color: #ff0000; /* Red color for links */
            text-decoration: none; /* Remove underline from links */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Order Status</h2>
    <?php
    if ($result_orders->num_rows > 0) {
        echo "<table class='components-table'>"; // Apply class to the table
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>Order Date</th>";
        echo "<th>Build Number</th>"; // New column header
        echo "<th>Status</th>";
        echo "<th>See Details</th>"; // New column header for details link
        echo "</tr>";
        while ($row = $result_orders->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Order_ID'] . "</td>";
            echo "<td>" . $row['Build_Date'] . "</td>";
            echo "<td>" . $row['Build_Number'] . "</td>"; // New column for Build Number
            echo "<td>" . $row['Status'] . "</td>";
            echo "<td><a href='printbuild.php?order_id=" . $row['Order_ID'] . "'>See Details</a></td>"; // New link to printbuild.php with order_id
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
    }
    ?>
</div>
</body>
</html>
    <?php
} else {
    echo "User ID not found.";
}
?>
