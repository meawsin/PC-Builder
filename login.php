<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PC Builder Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            margin-top: 0;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
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
        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
        .success-message {
            color: #00ff00;
            margin-top: 10px;
        }
        a {
            color: #ff0000;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login to PC Builder Application</h2>
        <?php
            if (isset($_GET['error'])) {
                echo '<div class="error-message">' . $_GET['error'] . '</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="success-message">' . $_GET['success'] . '</div>';
            }
        ?>
        <form name="f1" action="authentication.php" method="POST">  
            <p>  
                <label> UserName: </label>  
                <input type="text" id="user" name="un" required />  
            </p>  
            <p>  
                <label> Password: </label>  
                <input type="password" id="pass" name="pw" required />  
            </p>  
            <p>     
                <input type="submit" id="btn" value="Login" />  
            </p>  
        </form> 
        <p>Don't have an account? <a href="signup.php">Register now</a></p>
        <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
    </div>
</body>
</html>
