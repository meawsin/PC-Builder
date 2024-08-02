<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password']; // Password without hashing

    // Check if the username already exists
    $check_user = "SELECT * FROM user WHERE Name = '$name'";
    $result = $con->query($check_user);

    if ($result->num_rows > 0) {
        $error = "Username already taken. Please choose a different name.";
    } else {
        // Get the maximum U_ID from the database
        $result = $con->query("SELECT MAX(CAST(SUBSTRING(U_ID, 2) AS UNSIGNED)) AS max_id FROM user");
        $row = $result->fetch_assoc();
        $next_id = $row['max_id'] + 1;

        $u_id = 'U' . sprintf('%03d', $next_id);

        // SQL query to insert user into the database
        $sql = "INSERT INTO user (U_ID, Name, Email, Phone_Number, Address, Password) VALUES ('$u_id', '$name', '$email', '$phone', '$address', '$password')";

        if ($con->query($sql) === TRUE) {
            // Set session variables
            $_SESSION['username'] = $name;
            $_SESSION['email'] = $email;

            // Redirect to the index page
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $con->error;
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - PC Builder Application</title>
    <style>
        /* Basic styling for demonstration purposes */
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
        input[type="email"],
        input[type="tel"],
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Register to PC Builder Application</h2>
        <?php
            // Display error message if provided
            if (isset($error)) {
                echo '<div class="error-message">' . $error . '</div>';
            }
        ?>
        <form action="signup.php" method="POST">  
            <p>  
                <label>Name:</label>  
                <input type="text" name="name" required />  
            </p>  
            <p>  
                <label>Email:</label>  
                <input type="email" name="email" required />  
            </p>  
            <p>  
                <label>Phone Number:</label>  
                <input type="tel" name="phone" required />  
            </p>  
            <p>  
                <label>Address:</label>  
                <input type="text" name="address" required />  
            </p>  
            <p>  
                <label>Password:</label>  
                <input type="password" name="password" required />  
            </p>  
            <p>     
                <input type="submit" value="Register" />  
            </p>  
        </form> 
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
