<?php
include 'header.php';
include 'connection.php';

$products = [];
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    if ($category === 'cpu') {
        $sql = "SELECT Comp_ID, Brand, Model, Generation FROM $category";
    } else {
        $sql = "SELECT Comp_ID, Brand, Model FROM $category";
    }
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Products - PC Builder Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
            text-align: center;
        }
        .top-bar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative; /* Added for top-right alignment */
        }
        .top-bar h1 {
            color: #ff0000; /* Red text color */
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff0000; /* Red background color */
            color: #fff;
            text-decoration: none;
            margin: 10px;
            border-radius: 5px;
            border: none;
        }
        .button:hover {
            background-color: #cc0000; /* Darker red on hover */
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
            color: #ff0000; /* Red text color for links */
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
        select, input[type="submit"] {
            width: 100%;
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
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin: 50px 0;
        }
        .comparison-table th, .comparison-table td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: center;
        }
        .comparison-table th {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Compare Products</h2>
        <form method="GET">
            <select name="category" id="category" required onchange="this.form.submit()">
                <option value="">Select Category</option>
                <option value="casing" <?= isset($_GET['category']) && $_GET['category'] == 'casing' ? 'selected' : '' ?>>Casing</option>
                <option value="cpu" <?= isset($_GET['category']) && $_GET['category'] == 'cpu' ? 'selected' : '' ?>>CPU</option>
                <option value="graphics_card" <?= isset($_GET['category']) && $_GET['category'] == 'graphics_card' ? 'selected' : '' ?>>Graphics Card</option>
                <option value="motherboard" <?= isset($_GET['category']) && $_GET['category'] == 'motherboard' ? 'selected' : '' ?>>Motherboard</option>
                <option value="power_supply" <?= isset($_GET['category']) && $_GET['category'] == 'power_supply' ? 'selected' : '' ?>>Power Supply</option>
                <option value="ram" <?= isset($_GET['category']) && $_GET['category'] == 'ram' ? 'selected' : '' ?>>RAM</option>
                <option value="storage" <?= isset($_GET['category']) && $_GET['category'] == 'storage' ? 'selected' : '' ?>>Storage</option>
            </select>
            <select name="product1" id="product1" required>
                <option value="">Select Product 1</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['Comp_ID'] ?>" <?= isset($_GET['product1']) && $_GET['product1'] == $product['Comp_ID'] ? 'selected' : '' ?>>
                        <?= $product['Brand'] . ' ' . $product['Model'] . ($category === 'cpu' ? ' (' . $product['Generation'] . ')' : '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="product2" id="product2" required>
                <option value="">Select Product 2</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['Comp_ID'] ?>" <?= isset($_GET['product2']) && $_GET['product2'] == $product['Comp_ID'] ? 'selected' : '' ?>>
                        <?= $product['Brand'] . ' ' . $product['Model'] . ($category === 'cpu' ? ' (' . $product['Generation'] . ')' : '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Compare">
        </form>

        <?php
        if (isset($_GET['category']) && isset($_GET['product1']) && isset($_GET['product2'])) {
            $category = $_GET['category'];
            $product1 = $_GET['product1'];
            $product2 = $_GET['product2'];

            // Query to fetch details of the selected products
            $sql1 = "SELECT * FROM $category WHERE Comp_ID = '$product1'";
            $sql2 = "SELECT * FROM $category WHERE Comp_ID = '$product2'";

            $result1 = mysqli_query($con, $sql1);
            $result2 = mysqli_query($con, $sql2);

            if (mysqli_num_rows($result1) == 1 && mysqli_num_rows($result2) == 1) {
                $product1_details = mysqli_fetch_assoc($result1);
                $product2_details = mysqli_fetch_assoc($result2);

                echo '<table class="comparison-table">';
                echo '<tr><th>Feature</th><th>Product 1</th><th>Product 2</th></tr>';

                foreach ($product1_details as $key => $value) {
                    if ($key != 'Comp_ID') {
                        echo '<tr>';
                        echo '<td>' . $key . '</td>';
                        echo '<td>' . $value . '</td>';
                        echo '<td>' . $product2_details[$key] . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
            }
        }
        ?>
    </div>

    <div class="bottom-bar">
        <?php if (!isset($_SESSION["username"])): ?>
            <!-- Hide login and sign up links if logged in -->
            <a href="login.php">Login</a> / <a href="signup.php">Sign up</a>
        <?php endif; ?>
    </div>
</body>
</html>
