<?php
session_start();
include('connect.php');

// Check if the 'id' GET variable is set
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Fetch the news item data
  $query = "SELECT * FROM category WHERE id = ?";
  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news_item = $result->fetch_assoc();
    $stmt->close();
  }
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  // Assign variables from posted data
  $news_title = mysqli_real_escape_string($conn, $_POST['news_title']);
  $news_description = mysqli_real_escape_string($conn, $_POST['news_description']);
  $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
  $id = mysqli_real_escape_string($conn, $_POST['id']); // Make sure to retrieve the id from the form

  // SQL query to update the news item in the database
  $query = "UPDATE category SET name = ?, description = ?, image_url = ? WHERE id = ?";

  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("sssi", $news_title, $news_description, $image_url, $id);
    if ($stmt->execute()) {
      // Redirect to news dashboard or display a success message
      header("Location: news.php");
      exit();
    } else {
      // Error handling
      echo "Error: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <title> USIM News Content by Irfan </title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/material.css">
  <link type="text/css" rel="stylesheet" href="fonts/font.css">
  <link rel="icon" href="">
  <!-- Sripts -->
  <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
  <style>
    textarea {
      display: none;
      width: 300px;
      height: 50px;
      background: #333;
      color: #ddd;
      padding: 10px;
      margin: 5px 0 -14px;
    }

    .ans_sub {
      display: none;
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
  </style>
</head>

<body id="_3">
  <!-- ... existing navigation ... -->
  <ul id="nav-bar">
    <!-- <a href="index.php">
      <li>Home</li>
    </a> -->
    <a href="index.php">
      <li id="home">News</li>
      <a href="contacts.php">
        <li>Contact</li>
      </a>
      <a href="ask.php">
        <li>Ask Question</li>
      </a>
      <?php
      if (!isset($_SESSION['user'])) {
      ?>
        <a href="login.php">
          <li>Log In</li>
        </a>

      <?php
      } else {
      ?>
        <a href="profile.php">
          <li>Hi, <?php echo $_SESSION["user"]; ?></li>
        </a>
        <a href="logout.php">
          <li>Log Out</li>
        </a>
      <?php
      }
      ?>
  </ul>
  <div id="content" class="container mt-5">
    <h2>Edit News</h2>
    <?php if (isset($news_item)) : ?>
      <form action="edit_news.php" method="post">
        <input type="hidden" name="id" value="<?php echo $news_item['id']; ?>">
        <div class="form-group">
          <label for="news_title">Title:</label>
          <input type="text" class="form-control" name="news_title" id="news_title" value="<?php echo $news_item['name']; ?>" required>
        </div>
        <div class="form-group">
          <label for="news_description">Description:</label>
          <textarea class="form-control" name="news_description" id="news_description" rows="3" required><?php echo $news_item['description']; ?></textarea>
        </div>
        <div class="form-group">
          <label for="image_url">Image URL:</label>
          <input type="text" class="form-control" name="image_url" id="image_url" value="<?php echo $news_item['image_url']; ?>">
        </div>
        <div class="form-group">
        <label for="news_tags">Tags:</label>
        <select multiple class="form-control" name="news_tags[]" id="news_tags">
                        <option value="tag1">FST</option>
                        <option value="tag2">FPSK</option>
                        <option value="tag3">FKP</option>     
          <!-- Add more tag options here -->
        </select>
      </div>
        <button type="submit" class="btn btn-primary" name="submit">Update News</button>
        <button class="btn btn-primary" onclick="window.history.back()">Cancel</button>
      </form>
    <?php else : ?>
      <p>News item not found.</p>
    <?php endif; ?>
  </div>
  <!-- ... existing footer ... -->
  <?php include_once('footer.php'); ?>
</body>

</html>