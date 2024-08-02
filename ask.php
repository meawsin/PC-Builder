<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];
$user_sql = "SELECT U_ID FROM user WHERE Name = '$username'";
$user_result = mysqli_query($con, $user_sql);
$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['U_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $insert_sql = "INSERT INTO messages (User_ID, Message) VALUES ('$user_id', '$message')";
    mysqli_query($con, $insert_sql);
}

$message_sql = "SELECT * FROM messages WHERE User_ID = '$user_id'";
$message_result = mysqli_query($con, $message_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask the Expert - PC Builder Application</title>
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
            margin: 50px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }
        textarea, input[type="submit"] {
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
        .message {
            background-color: #444;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .response {
            background-color: #555;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ask the Expert</h2>
        <form action="ask.php" method="POST">
            <textarea name="message" placeholder="Type your question here..." required></textarea>
            <input type="submit" value="Send">
        </form>
        <h3>Your Questions and Responses</h3>
        <?php while ($row = mysqli_fetch_assoc($message_result)) { ?>
            <div class="message">
                <p><strong>Question:</strong> <?php echo $row['Message']; ?></p>
                <?php if ($row['Response']) { ?>
                    <div class="response">
                        <p><strong>Response:</strong> <?php echo $row['Response']; ?></p>
                    </div>
                <?php } else { ?>
                    <p><em>No response yet</em></p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
