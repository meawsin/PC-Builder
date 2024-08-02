<?php
include 'connection.php';

$order_id = $_GET['order_id'];

$sql_order = "SELECT o.Order_ID, o.Price as Order_Price, u.Name as User_Name, u.Phone_Number, u.Address
              FROM Orders o
              INNER JOIN user u ON o.U_ID = u.U_ID
              WHERE o.Order_ID = '$order_id'";

$result_order = $con->query($sql_order);
$order = $result_order->fetch_assoc();

if (!$order) {
    die("Order not found");
}

$sql_builds = "SELECT 
                b.Build_ID, b.Build_Number, b.Build_Date, b.Estimated_Price,
                c.Brand as CPU_Brand, c.Model as CPU_Model, c.Generation as CPU_Generation, c.Price as CPU_Price,
                m.Brand as Mobo_Brand, m.Model as Mobo_Model, m.Type as Mobo_type, m.Price as Mobo_Price,
                r.Brand as RAM_Brand, r.Model as RAM_Model, r.Memory as RAM_Memory, r.Price as RAM_Price,
                s.Brand as Storage_Brand, s.Model as Storage_Model, s.Storage as Storage_Capacity, s.Price as Storage_Price,
                g.Brand as GPU_Brand, g.Model as GPU_Model, g.Memory as GPU_Memory, g.Price as GPU_Price,
                p.Brand as PSU_Brand, p.Model as PSU_Model, p.Max_power as PSU_Max_Power, p.Price as PSU_Price,
                cs.Brand as Casing_Brand, cs.Model as Casing_Model, cs.Price as Casing_Price
                FROM builds b
                INNER JOIN CPU c ON b.CPU_ID = c.Comp_ID
                INNER JOIN Motherboard m ON b.Motherboard_ID = m.Comp_ID
                INNER JOIN RAM r ON b.RAM_ID = r.Comp_ID
                INNER JOIN Storage s ON b.Storage_ID = s.Comp_ID
                INNER JOIN Graphics_Card g ON b.Graphics_Card_ID = g.Comp_ID
                INNER JOIN Power_Supply p ON b.PSU_ID = p.Comp_ID
                INNER JOIN Casing cs ON b.Casing_ID = cs.Comp_ID
                WHERE b.Build_ID = (SELECT Build_ID FROM Orders WHERE Order_ID = '$order_id')
                ORDER BY b.Build_Date DESC";

$result_builds = $con->query($sql_builds);
$build = $result_builds->fetch_assoc();

if (!$build) {
    die("Build not found for the given order");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
        }
        .header {
            background-color: red;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            padding: 20px;
        }
        .user-info, .build-info {
            margin-bottom: 20px;
        }
        .user-info h2, .build-info h2 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        .user-info p, .build-info p {
            margin: 5px 0;
        }
        .build-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .build-table th, .build-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .build-table th {
            background-color: #f2f2f2;
        }
        .total-price {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        PC Builder App
    </div>
    <div class="container">
        <div class="user-info">
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> <?= $order['Order_ID'] ?></p>
            <p><strong>Name:</strong> <?= $order['User_Name'] ?></p>
            <p><strong>Mobile no:</strong> <?= $order['Phone_Number'] ?></p>
            <p><strong>Address:</strong> <?= $order['Address'] ?></p>
        </div>
        <div class="build-info">
            <h2>BUILD</h2>
            <table class="build-table">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th>Component</th>
                        <th>Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $components = [
                        ['type' => 'CPU', 'brand' => 'CPU_Brand', 'model' => 'CPU_Model', 'extra' => 'CPU_Generation', 'price' => 'CPU_Price'],
                        ['type' => 'Motherboard', 'brand' => 'Mobo_Brand', 'model' => 'Mobo_Model', 'extra' => 'Mobo_type', 'price' => 'Mobo_Price'],
                        ['type' => 'RAM', 'brand' => 'RAM_Brand', 'model' => 'RAM_Model', 'extra' => 'RAM_Memory', 'price' => 'RAM_Price'],
                        ['type' => 'Storage', 'brand' => 'Storage_Brand', 'model' => 'Storage_Model', 'extra' => 'Storage_Capacity', 'price' => 'Storage_Price'],
                        ['type' => 'Graphics Card', 'brand' => 'GPU_Brand', 'model' => 'GPU_Model', 'extra' => 'GPU_Memory', 'price' => 'GPU_Price'],
                        ['type' => 'Power Supply', 'brand' => 'PSU_Brand', 'model' => 'PSU_Model', 'extra' => 'PSU_Max_Power', 'price' => 'PSU_Price'],
                        ['type' => 'Casing', 'brand' => 'Casing_Brand', 'model' => 'Casing_Model', 'extra' => '', 'price' => 'Casing_Price']
                    ];

                    foreach ($components as $index => $component) {
                        echo "<tr>";
                        echo "<td>" . ($index + 1) . "</td>";
                        echo "<td>" . $component['type'] . "</td>";
                        echo "<td>" . $build[$component['brand']] . " " . $build[$component['model']] . ($component['extra'] ? " " . $build[$component['extra']] : "") . "</td>";
                        echo "<td>" . $build[$component['price']] . " taka</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="total-price">
                Total Price: <?= $order['Order_Price'] ?> taka
            </div>
        </div>
    </div>
</body>
</html>
