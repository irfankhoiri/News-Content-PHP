<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "news";

    // Create connection
    $conn = new mysqli($server, "root", "", $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Successful login
        $_SESSION["username"] = $username;
        header("Location: success.php");
    } else {
        // Failed login
        header("Location: login.php");
    }

    // Close connection
    $conn->close();
} else {
    header("Location: login.php");
}
?>
