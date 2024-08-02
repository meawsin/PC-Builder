<?php
include 'connection.php';
include 'header.php';

$searchTerm = "";
$searchResults = [];

if (isset($_GET["query"])) {
    $searchTerm = $con->real_escape_string($_GET["query"]);

    $sql = "
    SELECT 'CPU' AS category,  Brand, Model, Price FROM CPU WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'Motherboard' AS category,  Brand, Model, Price FROM Motherboard WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'RAM' AS category,  Brand, Model, Price FROM RAM WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'Power Supply' AS category,  Brand, Model, Price FROM Power_Supply WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'Storage' AS category,  Brand, Model, Price FROM Storage WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'Graphics Card' AS category,  Brand, Model, Price FROM Graphics_Card WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    UNION
    SELECT 'Casing' AS category,  Brand, Model, Price FROM Casing WHERE Brand LIKE '%$searchTerm%' OR Model LIKE '%$searchTerm%'
    ";

    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Search Results</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
        
        <?php if (!empty($searchResults)): ?>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Price</th>
                </tr>
                <?php foreach ($searchResults as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['Brand']); ?></td>
                        <td><?php echo htmlspecialchars($product['Model']); ?></td>
                        <td><?php echo htmlspecialchars($product['Price']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No results found for your search.</p>
        <?php endif; ?>
    </div>

</body>
</html>
