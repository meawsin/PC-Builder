<?php
session_start();
include 'connection.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $image = $_FILES["image"];
    $expiration_date = $_POST["expiration_date"];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($image["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($image["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    if ($upload_ok == 1) {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $stmt = $con->prepare("INSERT INTO promotions (image_url, expiration_date) VALUES (?, ?)");
            $stmt->bind_param("ss", $target_file, $expiration_date);
            $stmt->execute();
            $stmt->close();
            echo "offer is live";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PC Builder Application</title>
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
            color: #ff0000;
            margin: 0;
            cursor: pointer;
        }
        .container {
            max-width: 800px;
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
            cursor: pointer;
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
        .top-left {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #fff;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
        .form-container {
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: left;
            color: #fff;
            margin-top: 20px;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
            color: #ff0000;
        }
        .form-container input[type="file"],
        .form-container input[type="datetime-local"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: none;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #ff0000;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #cc0000;
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
    <h2>Upload Promotion Image</h2>
    <div class="form-container">
        <form action="promotion.php" method="post" enctype="multipart/form-data">
            <label for="image">Select Image:</label>
            <input type="file" name="image" id="image" required>
            <label for="expiration_date">Expiration Date and Time:</label>
            <input type="datetime-local" name="expiration_date" id="expiration_date" required>
            <input type="submit" value="Upload" class="button">
        </form>
    </div>
</div>
</body>
</html>
