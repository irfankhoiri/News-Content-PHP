<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$server = "localhost";
$user = "";
$password = "root";
$db = "news1";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Connection Failed " . mysqli_connect_error());
}
