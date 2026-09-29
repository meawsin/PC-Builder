<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="top-bar">
    <a href="index.php"><h1>PC Builder Application</h1></a>
    <form action="search.php" method="GET">
        <input type="text" name="query" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
    <?php

        if (isset($_SESSION["username"])) {
            echo '<div class="top-right">';
            echo 'Welcome, ' . htmlspecialchars($_SESSION["username"]) . '! ';
            echo '<a href="logout.php">Logout</a>';
            echo '</div>';
        } elseif (isset($_SESSION["adminname"]) || isset($_SESSION["admin_username"])) {
            $admin_display = isset($_SESSION["admin_username"]) ? $_SESSION["admin_username"] : $_SESSION["adminname"];
            echo '<div class="top-right">';
            echo 'Welcome, ' . htmlspecialchars($admin_display) . '! ';
            echo '<a href="logout.php">Logout</a>';
            echo '</div>';
        }
    ?>
</div>
