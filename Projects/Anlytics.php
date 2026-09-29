<?php
session_start();
include 'connection.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

// Queries for analytics
$total_users = 0;
$res = $con->query("SELECT COUNT(*) AS total FROM user");
if ($res) { $total_users = $res->fetch_assoc()['total']; }

$total_builds = 0;
$avg_build_price = 0;
$res = $con->query("SELECT COUNT(*) AS total, IFNULL(AVG(Estimated_Price), 0) AS avg_price FROM builds");
if ($res) {
    $row = $res->fetch_assoc();
    $total_builds = $row['total'];
    $avg_build_price = round($row['avg_price'], 2);
}

$total_orders = 0;
$total_revenue = 0;
$res = $con->query("SELECT COUNT(*) AS total, IFNULL(SUM(Price), 0) AS revenue FROM orders");
if ($res) {
    $row = $res->fetch_assoc();
    $total_orders = $row['total'];
    $total_revenue = $row['revenue'];
}

// Orders breakdown by status
$order_stats = [];
$res = $con->query("SELECT Status, COUNT(*) AS count, IFNULL(SUM(Price), 0) AS total_val FROM orders GROUP BY Status");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $order_stats[] = $row;
    }
}

// Most popular components in builds
$popular_cpus = [];
$res = $con->query("SELECT c.Brand, c.Model, COUNT(*) AS count FROM builds b JOIN cpu c ON b.CPU_ID = c.Comp_ID GROUP BY b.CPU_ID ORDER BY count DESC LIMIT 5");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $popular_cpus[] = $row;
    }
}

$popular_gpus = [];
$res = $con->query("SELECT g.Brand, g.Model, COUNT(*) AS count FROM builds b JOIN graphics_card g ON b.Graphics_Card_ID = g.Comp_ID GROUP BY b.Graphics_Card_ID ORDER BY count DESC LIMIT 5");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $popular_gpus[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Insights - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #222;
            color: #fff;
        }
        .top-bar {
            background-color: #000;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative;
        }
        .top-bar h1 {
            color: #00ff99;
            margin: 0;
            cursor: pointer;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
        .top-right a {
            color: #00ff99;
            text-decoration: none;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background-color: #333;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border-top: 4px solid #00ff99;
        }
        .card h3 {
            margin: 0 0 10px 0;
            color: #ccc;
            font-size: 15px;
            text-transform: uppercase;
        }
        .card .value {
            font-size: 28px;
            font-weight: bold;
            color: #00ff99;
        }
        .section-title {
            color: #00ff99;
            border-bottom: 2px solid #444;
            padding-bottom: 8px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #333;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #111;
            color: #00ff99;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00ff99;
            color: #000;
            text-decoration: none;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #00995c;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="top-bar">
    <h1 onclick="window.location.href='admin_dashboard.php'">PC Builder Application</h1>
    <h2>System Insights & DBMS Analytics</h2>
    <div class="top-right">
        Welcome, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?>! | 
        <a href="admin_dashboard.php">Dashboard</a> | 
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <div class="cards-grid">
        <div class="card">
            <h3>Total Registered Users</h3>
            <div class="value"><?php echo $total_users; ?></div>
        </div>
        <div class="card">
            <h3>Total Custom Builds</h3>
            <div class="value"><?php echo $total_builds; ?></div>
        </div>
        <div class="card">
            <h3>Total Orders</h3>
            <div class="value"><?php echo $total_orders; ?></div>
        </div>
        <div class="card">
            <h3>Total Revenue</h3>
            <div class="value"><?php echo number_format($total_revenue); ?> BDT</div>
        </div>
        <div class="card">
            <h3>Avg Build Price</h3>
            <div class="value"><?php echo number_format($avg_build_price); ?> BDT</div>
        </div>
    </div>

    <h3 class="section-title">Order Status Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
                <th>Total Value (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($order_stats)): ?>
                <?php foreach ($order_stats as $stat): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(ucfirst($stat['Status'])); ?></strong></td>
                        <td><?php echo htmlspecialchars($stat['count']); ?></td>
                        <td><?php echo number_format($stat['total_val']); ?> BDT</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No orders found yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 class="section-title">Most Popular CPUs in Builds</h3>
    <table>
        <thead>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Builds Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($popular_cpus)): ?>
                <?php foreach ($popular_cpus as $cpu): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cpu['Brand']); ?></td>
                        <td><?php echo htmlspecialchars($cpu['Model']); ?></td>
                        <td><?php echo htmlspecialchars($cpu['count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No builds saved yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 class="section-title">Most Popular Graphics Cards in Builds</h3>
    <table>
        <thead>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Builds Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($popular_gpus)): ?>
                <?php foreach ($popular_gpus as $gpu): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($gpu['Brand']); ?></td>
                        <td><?php echo htmlspecialchars($gpu['Model']); ?></td>
                        <td><?php echo htmlspecialchars($gpu['count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No builds saved yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="button">&larr; Back to Dashboard</a>
</div>
</body>
</html>
