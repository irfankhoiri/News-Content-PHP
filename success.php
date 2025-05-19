<?php
session_start();

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    echo "<h2>Welcome, $username!</h2>";
    // Add other content for successful login
} else {
    header("Location: login.php");
}
?>
