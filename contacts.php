<?php
include('connect.php');
$search_query = '';
$selected_tag = '';

if (isset($_GET['search']) && $_GET['search'] != '') {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM category WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%' ORDER BY id";
} elseif (isset($_GET['tag']) && $_GET['tag'] != '' && $_GET['tag'] != 'clear') {
    $selected_tag = $_GET['tag'];
    $query = "SELECT * FROM category WHERE FIND_IN_SET('$selected_tag', tags) ORDER BY id";
} else {
    $query = "SELECT * FROM category ORDER BY id";
}
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title> USIM News Content </title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/material.css">
    <link type="text/css" rel="stylesheet" href="fonts/font.css">
    <link rel="icon" href="images/icon1.png">
</head>

<body id="_4">
    <!-- navigation bar -->
    <?php include_once('header.php'); ?>

    <!-- content -->
    <div id="content" class="clearfix">

        <div id="box-1">
            <div class="heading">
                <center>
                    <h1 class="logo">
                        <div id="ntro">USIM News Content</div>
                    </h1>
                    <p id="tag-line">Where the USIM community discusses the latest news and trends</p>
                </center>
            </div>
        </div>
        <div id="box-2">
            <div id="text">
                <h1></h1>
                <p>
                    016-7225319<br>
                    Social: <a href="USIM.com">contact3</a>
                </p>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div id="footer">
        &copy; 2023 &bull; Discussion Content By Irfan
    </div>

</body>

</html>