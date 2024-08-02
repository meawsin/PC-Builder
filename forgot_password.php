<?php
session_start();
include 'connection.php';

$step = isset($_GET['step']) ? $_GET['step'] : 'forgot_password';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($step == 'forgot_password') {
        $name = $con->real_escape_string($_POST['name']);
        $email = $con->real_escape_string($_POST['email']);

        $sql_user = $con->prepare("SELECT * FROM User WHERE Name=? AND Email=?");
        $sql_user->bind_param("ss", $name, $email);
        $sql_user->execute();
        $result_user = $sql_user->get_result();

        if ($result_user->num_rows > 0) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: forgot_password.php?step=new_password&name=" . urlencode($name) . "&email=" . urlencode($email));
            exit();
        } else {
            $error = "Invalid name or email";
        }
    } elseif ($step == 'new_password') {
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
            die("CSRF token validation failed");
        }

        $name = $con->real_escape_string($_POST['name']);
        $email = $con->real_escape_string($_POST['email']);
        $new_password = $con->real_escape_string($_POST['new_password']);
        $retype_password = $con->real_escape_string($_POST['retype_password']);

        if ($new_password === $retype_password) {
            $sql_user = $con->prepare("UPDATE User SET Password=? WHERE Name=? AND Email=?");
            $sql_user->bind_param("sss", $new_password, $name, $email);

            if ($sql_user->execute()) {
                session_destroy();
                header("Location: login.php?success=Password successfully updated");
                exit();
            } else {
                $error = "Error updating password: " . $con->error;
            }
        } else {
            $error = "Passwords do not match";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($step == 'forgot_password') ? 'Forgot Password' : 'Set New Password'; ?>PC Builder Application</title>
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
        input[type="email"],
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
    </style>
</head>
<body>
    <div class="container">
        <?php if ($step == 'forgot_password'): ?>
            <h2>Reset Your Password</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="forgot_password.php?step=forgot_password" method="POST">
                <p>
                    <label>Name: </label>
                    <input type="text" name="name" required />
                </p>
                <p>
                    <label>Email: </label>
                    <input type="email" name="email" required />
                </p>
                <p>
                    <input type="submit" value="Reset Password" />
                </p>
            </form>
        <?php elseif ($step == 'new_password'): ?>
            <h2>Set Your New Password</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="forgot_password.php?step=new_password" method="POST">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($_GET['name']); ?>" />
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" />
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" />
                <p>
                    <label>New Password: </label>
                    <input type="password" name="new_password" required />
                </p>
                <p>
                    <label>Retype New Password: </label>
                    <input type="password" name="retype_password" required />
                </p>
                <p>
                    <input type="submit" value="Submit Change" />
                </p>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
