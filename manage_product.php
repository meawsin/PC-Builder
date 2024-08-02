<?php
session_start();
include 'connection.php'; 

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

$categories = ['CPU', 'Motherboard', 'RAM', 'Power_Supply', 'Storage', 'Graphics_Card', 'Casing'];
$selectedCategory = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : '');
$items = [];
$editItem = null;


if ($selectedCategory) {
    $sql = "SELECT * FROM $selectedCategory";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
}

//upload
if (isset($_POST['upload'])) {
    $columns = getColumns($selectedCategory);
    $values = [];
    foreach ($columns as $column) {
        if (isset($_POST[$column])) {
            $values[] = "'" . $con->real_escape_string($_POST[$column]) . "'";
        } else {
            $values[] = "''";
        }
    }

    $columns_str = implode(",", $columns);
    $values_str = implode(",", $values);

    $sql = "INSERT INTO $selectedCategory ($columns_str) VALUES ($values_str)";
    if ($con->query($sql) === TRUE) {
        echo "New model uploaded successfully!";
        // Refresh the items list
        $result = $con->query("SELECT * FROM $selectedCategory");
        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// edit
if (isset($_POST['edit'])) {
    $component_id = $con->real_escape_string($_POST['Comp_ID']);
    $set_clauses = [];
    foreach (getColumns($selectedCategory) as $column) {
        if ($column != 'Comp_ID') {
            $value = $con->real_escape_string($_POST[$column]);
            $set_clauses[] = "$column='$value'";
        }
    }

    $set_clause_str = implode(",", $set_clauses);
    $sql = "UPDATE $selectedCategory SET $set_clause_str WHERE Comp_ID='$component_id'";
    if ($con->query($sql) === TRUE) {
        echo "Model updated successfully!";
        // Refresh the items list
        $result = $con->query("SELECT * FROM $selectedCategory");
        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $component_id = $con->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM $selectedCategory WHERE Comp_ID='$component_id'";
    if ($con->query($sql) === TRUE) {
        echo "Model deleted successfully!";
        // Refresh the items list
        $result = $con->query("SELECT * FROM $selectedCategory");
        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}


if (isset($_GET['edit'])) {
    $component_id = $con->real_escape_string($_GET['edit']);
    $sql = "SELECT * FROM $selectedCategory WHERE Comp_ID='$component_id'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $editItem = $result->fetch_assoc();
    }
}


function getColumns($category) {
    switch ($category) {
        case 'CPU':
            return ['Comp_ID', 'Generation', 'Brand', 'Model', 'Price', 'Watt'];
        case 'Motherboard':
            return ['Comp_ID', 'Brand', 'Model', 'Type', 'Form_Factor', 'RAM_slot', 'Storage_slot', 'Watt', 'Price', 'Supported_CPU_gen'];
        case 'RAM':
            return ['Comp_ID', 'Brand', 'Model', 'Type', 'Memory', 'Watt', 'Price'];
        case 'Storage':
            return ['Comp_ID', 'Type', 'Brand', 'Model', 'Storage', 'Watt', 'Price'];
        case 'Power_Supply':
            return ['Comp_ID', 'Brand', 'Model', 'Rating', 'Max_power', 'Price'];
        case 'Graphics_Card':
            return ['Comp_ID', 'Brand', 'Type', 'Model', 'Memory', 'Watt', 'Price'];
        case 'Casing':
            return ['Comp_ID', 'Brand', 'Model', 'Formfactor', 'RGB', 'Extra_Fans', 'Price'];
        default:
            return [];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Product - Admin Panel</title>
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
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
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
        input[type="text"] {
        width: calc(100% - 22px); /* Adjust width to fit container */
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc; /* Add border */
        box-sizing: border-box; /* Ensure padding doesn't increase width */
    }
        .container {
            max-width: 1200px;
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
        <a href="admin_dashboard.php"><h1>PC Builder Application</h1></a>
        <h2>Upload Product</h2>
        <div class="top-right">
            Welcome, <?php echo $_SESSION["admin_username"]; ?>! <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Upload , Edit or Delete Prodcts</h2>

        <form method="post" action="manage_product.php">
            <label for="category">Select Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>" <?php echo ($selectedCategory == $category) ? 'selected' : ''; ?>><?php echo $category; ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selectedCategory && !empty($items)): ?>
            <h3>Existing Items in <?php echo $selectedCategory; ?></h3>
            <table>
                <tr>
                    <?php foreach (getColumns($selectedCategory) as $column): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <?php foreach (getColumns($selectedCategory) as $column): ?>
                            <td><?php echo htmlspecialchars($item[$column]); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="manage_product.php?edit=<?php echo htmlspecialchars($item['Comp_ID']); ?>&category=<?php echo htmlspecialchars($selectedCategory); ?>" class="button">Edit</a>
                            <a href="manage_product.php?delete=<?php echo htmlspecialchars($item['Comp_ID']); ?>&category=<?php echo htmlspecialchars($selectedCategory); ?>" class="button" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($selectedCategory): ?>
            <p>No items found in this category.</p>
        <?php endif; ?>

        <?php if ($selectedCategory && !$editItem): ?>
            <h3>Upload New Model for <?php echo $selectedCategory; ?></h3>
            <form method="post" action="manage_product.php">
                <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
                <?php foreach (getColumns($selectedCategory) as $column): ?>
                    <label for="<?php echo $column; ?>"><?php echo $column; ?>:</label>
                    <input type="text" name="<?php echo $column; ?>" id="<?php echo $column; ?>" <?php echo ($column == 'Comp_ID') ? 'required' : ''; ?>><br>
                <?php endforeach; ?>
                <button type="submit" name="upload" class="button">Upload</button>
            </form>
        <?php endif; ?>

        <?php if ($selectedCategory && $editItem): ?>
            <h3>Edit Model for <?php echo $selectedCategory; ?></h3>
            <form method="post" action="manage_product.php">
                <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
                <input type="hidden" name="Comp_ID" value="<?php echo htmlspecialchars($editItem['Comp_ID']); ?>">
                <?php foreach (getColumns($selectedCategory) as $column): ?>
                    <label for="<?php echo $column; ?>"><?php echo $column; ?>:</label>
                    <input type="text" name="<?php echo $column; ?>" id="<?php echo $column; ?>" value="<?php echo htmlspecialchars($editItem[$column]); ?>" <?php echo ($column == 'Comp_ID') ? 'readonly' : ''; ?>><br>
                <?php endforeach; ?>
                <button type="submit" name="edit" class="button">Save Changes</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
