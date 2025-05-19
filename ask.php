<?php
session_start();
include('connect.php');
if (!isset($_SESSION['user']))
    header("location: login.php");
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
?>

<!DOCTYPE html>
<html>

<head>
    <title> USIM News Content </title> 
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="css/material.css">
    <link type="text/css" rel="stylesheet" href="fonts/font.css">
    <link rel="icon" href="images/icon.png">
    <style>
        #category-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .category-card {
            width: 300px;
            height: 200px;
            margin: 10px;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .category-content {
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            color: white;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
        }

        .category-content h3 {
            margin-top: 0;
        }

        .category-content p {
            margin-bottom: 0;
        }
    </style>
</head>

<body id="ask">
    <!-- navigation bar -->
    <a href="index.php">
        <div id="log">
            <div id="i">i</div>
            <div id="cir">i</div>
            <div id="ntro">USIM News Content</div>
        </div>
    </a>
    <?php include_once('header.php'); ?>

    <!-- content -->
    <div id="content">
        <div id="sf">
            <center>
                <!-- Categories as cards or list -->
                <div id="category-cards">
                    <?php
                    // Fetch categories with descriptions and image URLs from the database
                    $cat_query = "SELECT name, description, image_url FROM category";
                    $cat_result = mysqli_query($conn, $cat_query);

                    // Generate the category cards dynamically
                    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                        $category_name = $cat_row['name'];
                        $category_description = $cat_row['description'];
                        $category_image_url = $cat_row['image_url'];

                        // Display each category as a card
                        echo '<div class="category-card" style="background-image: url(' . $category_image_url . ');">
                              <div class="category-content">
                                  <h3>' . $category_name . '</h3>
                              </div>
                          </div>';
                    }
                    ?>
                </div>

                <!-- ... -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input name="question" type="text" title="Your Question..." placeholder="Ask something" id="question">

                    <select name="cat">
                        <option value="Category">News</option>
                        <?php
                        // Fetch categories from the database
                        $cat_query = "SELECT name FROM category";
                        $cat_result = mysqli_query($conn, $cat_query);

                        // Generate the options dynamically
                        while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                            $category_name = $cat_row['name'];
                            echo '<option value="' . $category_name . '">' . $category_name . '</option>';
                        }
                        ?>
                    </select>
                    <input name="submit" type="submit" class="btn btn-success" id="ask_submit">
                </form>
                <!-- ... -->

            </center>
        </div>
    </div>

    <div id="ask-ta">
        <h1>Thank You.<br>Stay tunned for updates.</h1>
    </div>

    <?php

    if (isset($_POST["submit"])) {

        function valid($data)
        {
            $data = trim(stripslashes(htmlspecialchars($data)));
            return $data;
        }
        $question = valid($_POST["question"]);

        $no = valid($_POST["cat"]);
        $question = addslashes($question);
        $q = "SELECT * FROM quans WHERE question = '$question'";
        $result = mysqli_query($conn, $q);
        if (mysqli_error($conn))
            echo "<script>window.alert('Some Error Occured. Try Again or Contact Us.');</script>";
        else if ($no == "Category") {
            echo "<script>window.alert('Choose a Category.');</script>";
        } else if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO quans VALUES(NULL, '$question', NULL,'" . $_SESSION['user'] . "',NULL)";
            $query1 = "INSERT INTO quacat SELECT q.id, c.name FROM quans as q, category as c WHERE q.question = '" . $question . "' AND c.name = '" . $_POST['cat'] . "'";
            mysqli_query($conn, $query);
            if (mysqli_query($conn, $query1)) {
                echo "<style>#sf{display: none;} #ask-ta{display:block;}</style>";
            } else {
                echo "<script>window.alert('Some Error Occured. Try Again or Contact Us.');</script>";
            }
        } else {
            echo "<script>window.alert('Question was already Asked. Search it on Home Page.');</script>";
        }

        mysqli_close($conn);
    }

    ?>

    <!-- Footer -->
    <div id="footer" style="padding:30px;">
        &copy; 2023 &bull; USIM News Content by Irfan .
    </div>

    <!-- Sripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script type="text/javascript" src="js/jquery-3.2.1.min.js"><\/script>')
    </script>
    <script type="text/javascript" src="js/script.js"></script>

</body>

</html>