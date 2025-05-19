<?php
session_start();
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


$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
if (!$category_id) {
  // Redirect to a page or display an error message if the category ID is missing.
  header("Location: error_page.php");
  exit();
}

$query = "SELECT * FROM category WHERE id = '$category_id'";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result);

if (!$category) {
  // Redirect to a page or display an error message if the category does not exist.
  header("Location: error_page.php");
  exit();
}

function valid($data)
{
  $data = trim(stripslashes(htmlspecialchars($data)));
  return $data;
}

if (isset($_POST["ansubmit"])) {
  $answer = valid($_POST["answer"]);

  if ($answer == NULL) {
    echo "<script>window.alert('Please Enter something.');</script>";
  } else {
    $que = "";
    if ($_POST["nul"] == 0) {
      // There is an existing answer, so append the new answer and user
      $que = "UPDATE quans SET answer=CONCAT(answer, '<br>', '" . $answer . "', '<br><small>Replied By: @" . $_SESSION["user"] . "</small>'), answeredby=CONCAT(answeredby, ', @" . $_SESSION["user"] . "') WHERE question LIKE '%" . $category["name"] . "%'";
    } else {
      // No existing answer, just set the new answer and user
      $que = "UPDATE quans SET answer='" . $answer . " <br><small>Replied By: @" . $_SESSION["user"] . "</small>', answeredby = '@" . $_SESSION["user"] . "' WHERE question LIKE '%" . $category["name"] . "%'";
    }

    if (mysqli_query($conn, $que))
      echo "<style>#box0,.open{display: none;} #tb{display: block;}</style>";
    else
      header("Location: full_details.php?category_id=$category_id");
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>USIM News Content by Irfan - <?php echo htmlspecialchars($category['name']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/material.css">
  <link type="text/css" rel="stylesheet" href="fonts/font.css">
  <link rel="icon" href="">
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>

  <style>
    /* Add your custom styles here */
  </style>
</head>

<body id="_3">
  <?php include_once('header.php'); ?>
  <!-- content -->
  <div id="content">
    <div id="content" class="container mt-5">
      <div class="list-group">
        <div class="list-group-item flex-column align-items-start">
          <h5 class="mb-1"><?php echo htmlspecialchars($category['name']); ?></h5>
          <p><?php echo $category['description']; ?></p>
          <small class="text-muted">Posted on <?php echo $category['postedOn']; ?></small>
          <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" style="height: 100px; float: left; margin-right: 20px;">
        </div>
      </div>

      <!-- Display comments -->
      <?php
      $comments_query = "SELECT * FROM quans WHERE question LIKE '%" . $category["name"] . "%'";
      $comments_result = mysqli_query($conn, $comments_query);
      while ($comment = mysqli_fetch_assoc($comments_result)) {
      ?>
        <div class="list-group mt-3">
          <div class="list-group-item flex-column align-items-start">
            <div class="question">
              <div id="Q"><?php echo $comment['askedby']; ?>:</div>
              <?php echo $comment['question']; ?>
            </div>
            <div class="answer">
              <?php
              if ($comment["answer"]) {
                echo $comment["answer"] . "</small>";
              } else {
                echo "<em>No replies yet</em>";
              }
              ?>
            </div>
          </div>
        </div>
      <?php } ?>

  </div>
  <!-- content -->

  <!-- Hidden modal container -->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <!-- Content to be displayed goes here -->
      <span class="close">&times;</span>
    </div>
  </div>

  <!-- Footer -->
  <?php include_once('footer.php'); ?>

  <script>
    // Add your JavaScript code here
  </script>
</body>

</html>