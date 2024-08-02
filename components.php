<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Builder Application - Components</title>
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
            color: #ff0000; /* Red text color */
            margin: 0;
        }
        .container {
        max-width: 1200px;
        margin: 20px auto; /* Center the container horizontally */
        text-align: center;
    }
    .components-table {
        width: 80%; /* Make the table responsive */
        margin: 20px auto; /* Center the table within the container */
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
        
        .components-table th, .components-table td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: center;
        }
        .components-table th {
            background-color: #444;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'connection.php'; ?>

<?php
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<div class="container">
    <h2>Components</h2>
    <div class="button-row">
        <a href="?component=processors" class="button">Processors</a>
        <a href="?component=motherboards" class="button">Motherboards</a>
		<a href="?component=storage-devices" class="button">Storage Devices</a>
		<a href="?component=ram" class="button">RAM</a>
		<a href="?component=power-supplies" class="button">Power Supplies</a>
        <a href="?component=casings" class="button">Casings</a>
		<a href="?component=graphics-cards" class="button">Graphics Cards</a>
    </div>
</div>

<?php
if (isset($_GET['component'])) {
    $component = $_GET['component'];
    
    switch ($component) {
    case 'processors':
        $sql = "SELECT Brand, Model, Generation, Price FROM cpu";
        break;
    case 'motherboards':
        $sql = "SELECT Brand, Model, Type, Form_Factor AS 'Form Factor', Ram_slot AS 'RAM Slots', Storage_slot AS 'Storage Slots' , Price FROM motherboard";
        break;
    case 'ram':
        $sql = "SELECT Brand, Model, Memory, Type , Price FROM ram";
        break;
    case 'graphics-cards':
        $sql = "SELECT Brand, Model, Type, Memory, Price FROM graphics_card";
        break;
    case 'casings':
        $sql = "SELECT Brand, Model, RGB, Extra_Fans AS 'No. of Fans', Price FROM casing";
        break;
    case 'storage-devices':
        $sql = "SELECT Brand, Model, Storage , Price FROM storage";
        break;
    case 'power-supplies':
        $sql = "SELECT Brand, Model, Rating, Max_power , Price FROM power_supply";
        break;
    default:
        $sql = "";
}

    
    if ($sql) {
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {

            echo '<table class="components-table">';
            echo '<tr>';
            while ($fieldInfo = $result->fetch_field()) {
                echo '<th>' . $fieldInfo->name . '</th>';
            }
            echo '</tr>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                foreach ($row as $data) {
                    echo '<td>' . $data . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No components found.";
        }
    }
}
?>

</body>
</html>
