<?php
// Include necessary files
include 'header.php';
include 'connection.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Query to retrieve all builds for the current user
$username = $_SESSION['username'];
$sql_builds = "SELECT 
                b.Build_ID, b.Build_Number, b.Build_Date, b.Estimated_Price,
                c.Brand as CPU_Brand, c.Model as CPU_Model, c.Generation as CPU_Generation,
                m.Brand as Mobo_Brand, m.Model as Mobo_Model, m.Supported_CPU_gen as Mobo_CPU_Gen,
                r.Brand as RAM_Brand, r.Model as RAM_Model, r.Memory as RAM_Memory,
                s.Brand as Storage_Brand, s.Model as Storage_Model, s.Storage as Storage_Capacity,
                g.Brand as GPU_Brand, g.Model as GPU_Model, g.Memory as GPU_Memory,
                p.Brand as PSU_Brand, p.Model as PSU_Model, p.Max_power as PSU_Max_Power,
                cs.Brand as Casing_Brand, cs.Model as Casing_Model
                FROM builds b
                INNER JOIN user u ON b.User_ID = u.U_ID
                INNER JOIN CPU c ON b.CPU_ID = c.Comp_ID
                INNER JOIN Motherboard m ON b.Motherboard_ID = m.Comp_ID
                INNER JOIN RAM r ON b.RAM_ID = r.Comp_ID
                INNER JOIN Storage s ON b.Storage_ID = s.Comp_ID
                INNER JOIN Graphics_Card g ON b.Graphics_Card_ID = g.Comp_ID
                INNER JOIN Power_Supply p ON b.PSU_ID = p.Comp_ID
                INNER JOIN Casing cs ON b.Casing_ID = cs.Comp_ID
                WHERE u.Name = '$username'
                ORDER BY b.Build_Date DESC";

$result_builds = $con->query($sql_builds);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $build_id = $_POST['build_id'];
    $sql_user_id = "SELECT U_ID FROM user WHERE Name = '$username'";
    $result_user_id = $con->query($sql_user_id);
    if ($result_user_id->num_rows > 0) {
        $row_user_id = $result_user_id->fetch_assoc();
        $user_id = $row_user_id['U_ID'];
    }

    if (isset($_POST['order_build'])) {
        $sql_build = "SELECT Estimated_Price FROM builds WHERE Build_ID = '$build_id'";
        $result_build = $con->query($sql_build);
        if ($result_build->num_rows > 0) {
            $row_build = $result_build->fetch_assoc();
            $price = $row_build['Estimated_Price'];
            $sql_insert_order = "INSERT INTO `orders` (U_ID, Build_ID, Price) 
                                 VALUES ('$user_id', '$build_id',  '$price')";
            if ($con->query($sql_insert_order) === TRUE) {
                echo "Order placed successfully!";
            } else {
                echo "Error: " . $sql_insert_order . "<br>" . $con->error;
            }
        }
    } elseif (isset($_POST['delete_build'])) {
        $sql_delete_build = "DELETE FROM builds WHERE Build_ID = '$build_id'";
        if ($con->query($sql_delete_build) === TRUE) {
            echo "Build deleted successfully!";
            header("Refresh:0"); // Refresh the page to show updated list of builds
            exit;
        } else {
            echo "Error: " . $sql_delete_build . "<br>" . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Builds</title>
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
        }
        .top-bar h1 {
            color: #ff0000;
            margin: 0;
        }
        .container {
            width: 80%;
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
        .bottom-bar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .bottom-bar a {
            color: #ff0000;
            text-decoration: none;
        }
        .bottom-bar a:hover {
            text-decoration: underline;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
        .components-table {
            width: 100%;
            margin: 20px auto;
        }
        .components-table th, .components-table td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: center;
        }
        .components-table th {
            background-color: #444;
            color: #ff0000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Builds</h2>
    <?php
    if ($result_builds->num_rows > 0) {
        while ($row = $result_builds->fetch_assoc()) {
            echo "<h3>Build Number: " . $row['Build_Number'] . "</h3>";
            echo "<p>Date: " . $row['Build_Date'] . "</p>";
            echo "<p>Estimated Price: " . $row['Estimated_Price'] . " Taka</p>";
            
            echo "<table class='components-table'>";
            echo "<tr>";
            echo "<th>Component</th>";
            echo "<th>Brand</th>";
            echo "<th>Model</th>";
            echo "<th>Details</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>CPU</td>";
            echo "<td>" . $row['CPU_Brand'] . "</td>";
            echo "<td>" . $row['CPU_Model'] . "</td>";
            echo "<td>Generation: " . $row['CPU_Generation'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Motherboard</td>";
            echo "<td>" . $row['Mobo_Brand'] . "</td>";
            echo "<td>" . $row['Mobo_Model'] . "</td>";
            echo "<td>Supported CPU Gen: " . $row['Mobo_CPU_Gen'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>RAM</td>";
            echo "<td>" . $row['RAM_Brand'] . "</td>";
            echo "<td>" . $row['RAM_Model'] . "</td>";
            echo "<td>Memory: " . $row['RAM_Memory'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Storage</td>";
            echo "<td>" . $row['Storage_Brand'] . "</td>";
            echo "<td>" . $row['Storage_Model'] . "</td>";
            echo "<td>Capacity: " . $row['Storage_Capacity'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Graphics Card</td>";
            echo "<td>" . $row['GPU_Brand'] . "</td>";
            echo "<td>" . $row['GPU_Model'] . "</td>";
            echo "<td>Memory: " . $row['GPU_Memory'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Power Supply</td>";
            echo "<td>" . $row['PSU_Brand'] . "</td>";
            echo "<td>" . $row['PSU_Model'] . "</td>";
            echo "<td>Max Power: " . $row['PSU_Max_Power'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Casing</td>";
            echo "<td>" . $row['Casing_Brand'] . "</td>";
            echo "<td>" . $row['Casing_Model'] . "</td>";
            echo "<td></td>";
            echo "</tr>";
            echo "</table>";
            
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='build_id' value='" . $row['Build_ID'] . "'>";
            echo "<input type='submit' name='order_build' value='Order' class='button'>";
            echo "<input type='submit' name='delete_build' value='Delete' class='button'>";
            echo "</form>";
        }
    } else {
        echo "<p>No builds found.</p>";
    }
    ?>
</div>

</body>
</html>
