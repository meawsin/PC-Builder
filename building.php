<?php
include 'header.php';
include 'connection.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['selected_components'])) {
    $_SESSION['selected_components'] = [
        'CPU' => null,
        'Motherboard' => null,
        'RAM' => null,
        'Storage' => null,
        'Graphics_Card' => null,
        'Power_Supply' => null,
        'Casing' => null
    ];
}

function saveBuild() {
    global $con;
    $username = $_SESSION['username'];

    $sql_user_id = "SELECT U_ID FROM user WHERE Name = '$username'";
    $result_user_id = $con->query($sql_user_id);

    if ($result_user_id->num_rows > 0) {
        $row_user_id = $result_user_id->fetch_assoc();
        $user_id = $row_user_id['U_ID'];

        $components = $_SESSION['selected_components'];

        $cpu_id = $components['CPU']['Comp_ID'];
        $motherboard_id = $components['Motherboard']['Comp_ID'];
        $ram_id = $components['RAM']['Comp_ID'];
        $storage_id = $components['Storage']['Comp_ID'];
        $graphics_card_id = $components['Graphics_Card']['Comp_ID'];
        $psu_id = $components['Power_Supply']['Comp_ID'];
        $casing_id = $components['Casing']['Comp_ID'];

        $estimated_price = 0;
        foreach ($_SESSION['selected_components'] as $component) {
            if (!empty($component)) {
                $estimated_price += $component['Price'];
            }
        }

        $sql_build_count = "SELECT COUNT(*) AS build_count FROM builds WHERE User_ID = '$user_id'";
        $result_build_count = $con->query($sql_build_count);
        $row_build_count = $result_build_count->fetch_assoc();
        $build_number = $row_build_count['build_count'] + 1;

        $sql_insert_build = "INSERT INTO builds (User_ID, CPU_ID, Motherboard_ID, RAM_ID, Storage_ID, Graphics_Card_ID, PSU_ID, Casing_ID, Build_Number, Estimated_Price)
                             VALUES ('$user_id', '$cpu_id', '$motherboard_id', '$ram_id', '$storage_id', '$graphics_card_id', '$psu_id', '$casing_id', '$build_number', '$estimated_price')";

        if ($con->query($sql_insert_build) === TRUE) {
            return $con->insert_id;
        } else {
            echo "Error: " . $sql_insert_build . "<br>" . $con->error;
            return false;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $sql_user_id = "SELECT U_ID FROM user WHERE Name = '$username'";
    $result_user_id = $con->query($sql_user_id);
    if ($result_user_id->num_rows > 0) {
        $row_user_id = $result_user_id->fetch_assoc();
        $user_id = $row_user_id['U_ID'];
    }

    if (isset($_POST['save_build'])) {
        if (!in_array(null, $_SESSION['selected_components'])) {
            $build_id = saveBuild();
            if ($build_id) {
                $_SESSION['selected_components'] = [
                    'CPU' => null,
                    'Motherboard' => null,
                    'RAM' => null,
                    'Storage' => null,
                    'Graphics_Card' => null,
                    'Power_Supply' => null,
                    'Casing' => null
                ];
                header("Location: index.php");
                exit;
            }
        }
    }

    foreach ($_SESSION['selected_components'] as $component => $value) {
        if (isset($_POST[$component])) {
            $selected_id = $_POST[$component];
            $sql = "SELECT * FROM $component WHERE Comp_ID = '$selected_id'";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                $_SESSION['selected_components'][$component] = $result->fetch_assoc();
            }
        }
    }
}

$estimated_price = 0;
$estimated_wattage = 0;
foreach ($_SESSION['selected_components'] as $component) {
    if (!empty($component)) {
        $estimated_price += $component['Price'];
        if (isset($component['Watt'])) {
            $estimated_wattage += intval($component['Watt']);
        }
    }
}

function displayComponents($table_name, $attribute_names, $condition = "") {
    global $con;
    echo "<h3>Select your " . ucfirst($table_name) . "</h3>";
    $sql = "SELECT Comp_ID, " . implode(", ", $attribute_names) . " FROM $table_name $condition";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        echo "<form method='POST'>";
        echo "<table class='components-table'><tr>";
        foreach ($attribute_names as $attribute) {
            echo "<th>$attribute</th>";
        }
        echo "<th>Select</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($attribute_names as $attribute) {
                echo "<td>" . $row[$attribute] . "</td>";
            }
            echo "<td><input type='radio' name='$table_name' value='" . $row['Comp_ID'] . "' required></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<button type='submit'>Next</button>";
        echo "</form>";
    } else {
        echo "No results found";
    }
}

if (empty($_SESSION['selected_components']['CPU'])) {
    displayComponents('CPU', ['Brand', 'Model', 'Generation', 'Price']);
} elseif (empty($_SESSION['selected_components']['Motherboard'])) {
    $cpu_generation = $_SESSION['selected_components']['CPU']['Generation'];
    displayComponents('Motherboard', ['Brand', 'Model', 'Price', 'Type'], "WHERE Supported_CPU_gen = '$cpu_generation'");
} elseif (empty($_SESSION['selected_components']['RAM'])) {
    $mobo_type = $_SESSION['selected_components']['Motherboard']['Type'];
    displayComponents('RAM', ['Brand', 'Model', 'Memory', 'Type', 'Price'], "WHERE Type = '$mobo_type'");
} elseif (empty($_SESSION['selected_components']['Storage'])) {
    displayComponents('Storage', ['Brand', 'Model', 'Storage', 'Price']);
} elseif (empty($_SESSION['selected_components']['Graphics_Card'])) {
    $mobo_type = $_SESSION['selected_components']['Motherboard']['Type'];
    displayComponents('Graphics_Card', ['Brand', 'Model', 'Price'], "WHERE Type = '$mobo_type'");
} elseif (empty($_SESSION['selected_components']['Power_Supply'])) {
    displayComponents('Power_Supply', ['Brand', 'Model', 'Rating', 'Max_Power', 'Price'], "WHERE Max_Power > $estimated_wattage");
} elseif (empty($_SESSION['selected_components']['Casing'])) {
    $formfactor = $_SESSION['selected_components']['Motherboard']['Form_Factor'];
    displayComponents('Casing', ['Brand', 'Model', 'Price', 'RGB', 'Extra_fans'], "WHERE Formfactor = '$formfactor'");
} else {
    echo "<h2>All components selected, to save, click the save button </h2>";
}

echo "<h2>Estimated Price: $estimated_price taka </h2>";
echo "<h2>Estimated Wattage: $estimated_wattage W</h2>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Builder Application - Build Your PC</title>
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
            margin: 50px auto; /* Center the container horizontally */
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
        .next-button {
            display: inline-block;
            padding: 50px 20px;
            background-color: #ff0000; /* Red background color */
            color: #fff;
            text-decoration: none;
            margin: 10px;
            border-radius: 5px;
            border: none;
        }
        .components-table {
            width: 80%; /* Scale the table to 80% */
            margin: 20px auto; /* Center the table within the container */
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
        .selected-components {
            margin: 20px auto;
            padding: 20px;
            background-color: #222;
            border-radius: 10px;
            width: 50%; /* Adjust the width as needed */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Selected Components</h2>
    <div class="components-table">
        <table>
            <tr>
                <th>Component</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Price</th>
            </tr>

            <?php if (!empty($_SESSION['selected_components']['CPU'])) : ?>
                <tr>
                    <td>CPU</td>
                    <td><?php echo $_SESSION['selected_components']['CPU']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['CPU']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['CPU']['Price']; ?></td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($_SESSION['selected_components']['Motherboard'])) : ?>
                <tr>
                    <td>Motherboard</td>
                    <td><?php echo $_SESSION['selected_components']['Motherboard']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Motherboard']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Motherboard']['Price']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($_SESSION['selected_components']['RAM'])) : ?>
                <tr>
                    <td>RAM</td>
                    <td><?php echo $_SESSION['selected_components']['RAM']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['RAM']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['RAM']['Price']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($_SESSION['selected_components']['Storage'])) : ?>
                <tr>
                    <td>Storage</td>
                    <td><?php echo $_SESSION['selected_components']['Storage']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Storage']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Storage']['Price']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($_SESSION['selected_components']['Graphics_Card'])) : ?>
                <tr>
                    <td>Graphics Card</td>
                    <td><?php echo $_SESSION['selected_components']['Graphics_Card']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Graphics_Card']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Graphics_Card']['Price']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($_SESSION['selected_components']['Power_Supply'])) : ?>
                <tr>
                    <td>PSU</td>
                    <td><?php echo $_SESSION['selected_components']['Power_Supply']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Power_Supply']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Power_Supply']['Price']; ?></td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($_SESSION['selected_components']['Casing'])) : ?>
                <tr>
                    <td>Casing</td>
                    <td><?php echo $_SESSION['selected_components']['Casing']['Brand']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Casing']['Model']; ?></td>
                    <td><?php echo $_SESSION['selected_components']['Casing']['Price']; ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <?php if (!in_array(null, $_SESSION['selected_components'])) : ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <button class="button" type="submit" name="save_build">Save</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
