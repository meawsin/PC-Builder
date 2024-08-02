<?php
include('connection.php');

// Fetch the active promotion
$query = "SELECT image_url, expiration_date FROM promotions WHERE expiration_date > NOW() ORDER BY expiration_date LIMIT 1";
$result = $con->query($query);
$promotion = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Builder Application</title>
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
        .container {
            max-width: 700px;
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
        .promotion {
            margin: 20px auto;
            text-align: center;
        }
        .promotion img {
            max-width: 100%;
            height: auto;
        }
        .timer {
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Welcome to PC Builder Application</h2>
        
        <a href="compare.php" class="button">Compare</a>
        <a href="building.php" class="button">Build a PC</a>
        <a href="ask.php" class="button">ASK experts</a>
        <a href="components.php" class="button">See components</a>
        <a href="mybuilds.php" class="button">My builds</a>
        <a href="orderStatus.php" class="button">Check Orders</a>
        
        <div class="promotion">
            <?php if ($promotion): ?>
                Offer Ends In: <div class="timer" id="timer"></div>
                <img src="<?php echo htmlspecialchars($promotion['image_url']); ?>" alt="Promotion Image">
            <?php endif; ?>
        </div>
        
    </div>

    <?php if (!isset($_SESSION["username"])): ?>
        <div class="bottom-bar">
            <a href="login.php">Login</a> / <a href="signup.php">Sign up</a>
		</div>
	<?php endif; ?>
    
    <script>
        <?php if ($promotion): ?>
            var expirationDate = new Date("<?php echo $promotion['expiration_date']; ?>").getTime();
            
            // Update the countdown every 1 second
            var countdownFunction = setInterval(function() {
                var now = new Date().getTime();
                var distance = expirationDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result in the element with id="timer"
                document.getElementById("timer").innerHTML = days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ";

                // If the countdown is finished, write some text
                if (distance < 0) {
                    clearInterval(countdownFunction);
                    document.getElementById("timer").innerHTML = "EXPIRED";
                }
            }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>
