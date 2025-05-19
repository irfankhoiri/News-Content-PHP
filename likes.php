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
include('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  header("Location: login.php"); // Redirect to the login page if the user is not logged in
  exit();
}

$user_id = $_SESSION['id'];

// Retrieve liked items for the current user
$query = "SELECT category.name, category.description, category.postedOn, category.image_url
          FROM category
          INNER JOIN category_likes ON category.id = category_likes.category_id
          WHERE category_likes.user_id = '$user_id'
          ORDER BY category_likes.created_at DESC";

$liked_items = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
  <title> USIM News Content by Irfan </title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/material.css">
  <link type="text/css" rel="stylesheet" href="fonts/font.css">
  <link rel="icon" href="">
  <!-- Sripts -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <!-- <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script> -->
  <script type="text/javascript" src="js/script.js"></script>
  <!-- Add your CSS styles or include external CSS files here -->
  <style>
    .button-style {
      padding: 8px 12px;
      /* Example padding */
      margin: 0 5px;
      /* Example margin between buttons */
      font-size: 1rem;
      /* Example font size */
      border: none;
      /* No border */
      cursor: pointer;
      /* Cursor changes to pointer on hover */
      outline: none;
      /* Removes the outline to keep the design clean */
      background-color: #007bff;
      /* Bootstrap primary color for example */
      color: white;
      /* Text color */
      border-radius: 0.25rem;
      /* Bootstrap border-radius for buttons */
      text-decoration: none;
      /* Remove underline from links */
    }

    .button-style:hover {
      background-color: #0056b3;
      /* Darker shade on hover for example */
    }

    .or-separator {
      font-weight: normal;
      color: #777;
      /* other styles */
    }

    textarea {
      width: 300px;
      height: 50px;
      color: black;
      padding: 10px;
      margin: 5px 0 -14px;
    }

    .ans_sub {
      padding: 0 10px;
      height: 30px;
      line-height: 30px;
    }

    .pop {
      display: none;
      text-align: center;
      margin: 195.5px auto;
      font-size: 12px;
    }

    /* Center the modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 70%;
      /* You can adjust this value to control the width of the modal */
      max-width: 600px;
      /* Maximum width of the modal */
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      text-align: center;
      position: relative;
    }

    /* Close button (X) */
    .close {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 20px;
      font-weight: bold;
      color: black;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <?php include_once('header.php'); ?>
  <!-- Add your page header or navigation here -->

  <div class="container mt-5">
    <h2>Bookmarked News</h2>
    <div class="list-group">
      <?php while ($category = mysqli_fetch_assoc($liked_items)) : ?>
        <div class="list-group-item flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?php echo htmlspecialchars($category['name']); ?></h5>
            <!-- You can add any actions or buttons here for each liked item -->
          </div>
          <p class="mb-1"><?php echo htmlspecialchars($category['description']); ?></p>
          <small class="text-muted">Posted on <?php echo $category['postedOn']; ?></small>
          <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" style="height: 100px; float: left; margin-right: 20px;">
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Add your footer or scripts here -->

</body>

</html>