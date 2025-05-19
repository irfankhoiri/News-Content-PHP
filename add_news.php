<?php
session_start();
include('connect.php');

// This PHP block would handle the form submission.
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_FILES["image_upload"]) && $_FILES["image_upload"]["error"] == 0) {
    $target_dir = "uploads/"; // Specify the directory where you want to store uploaded images
    $target_file = $target_dir . basename($_FILES["image_upload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
    if ($check !== false) {
      // Check and limit file size if needed (e.g., 2MB)
      if ($_FILES["image_upload"]["size"] > 2097152) { // 2MB in bytes
        echo "Sorry, your image is too large.";
      } else {
        // Generate a unique name for the uploaded image
        $unique_name = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Move the uploaded image to the specified directory
        if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
          $image_url = $target_file; // Set the image URL to the uploaded file path
          // Assign variables from posted data
          $news_title = mysqli_real_escape_string($conn, $_POST['news_title']);
          $news_description = mysqli_real_escape_string($conn, $_POST['news_description']);
          $news_tags = isset($_POST['news_tags']) ? $_POST['news_tags'] : [];

          // Convert the tags array into a string to insert into the ENUM column
          // Assuming your ENUM column can store a comma-separated list of tags
          $tags_string = implode(",", $news_tags);

          // SQL query to insert the new news into the database with tags
          $tags_string = implode(",", $news_tags); // 'tag1,tag2'
          $query = "INSERT INTO category (name, description, image_url, tags) VALUES ('$news_title', '$news_description', '$image_url', '$tags_string')";

          // Execute the query
          if (mysqli_query($conn, $query)) {
            // Redirect to news dashboard or display a success message

            header("Location: news.php");
            exit();
          } else {
            // Error handling
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
          }
        } else {
          echo "Sorry, there was an error uploading your image.";
        }
      }
    } else {
      echo "File is not an image.";
    }
  } else {
    echo "No image file uploaded.";
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
  <div class="container mt-5">
    <h2 class="mb-4">Add News</h2>
    <form action="add_news.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="news_title">Title:</label>
        <input type="text" class="form-control" name="news_title" id="news_title" required>
      </div>
      <div class="form-group">
        <label for="news_description">Description:</label>
        <textarea class="form-control" name="news_description" id="news_description" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label for="image_upload">Upload Image:</label>
        <input type="file" class="form-control-file" name="image_upload" id="image_upload">
        <small class="form-text text-muted">Upload an image for your news.</small>
      </div>
      <div class="form-group">
        <label for="news_tags">Tags:</label>
        <select multiple class="form-control" name="news_tags[]" id="news_tags">
          <option value="FST">FST</option>
          <option value="FPSK">FPSK</option>
          <option value="FSU">FSU</option>
          <option value="FPQS">FPQS</option>
          <option value="FKP">FKP</option>
          <!-- Add more tag options here -->
        </select>
      </div>
      <button type="submit" class="btn btn-primary" name="submit">Add News</button>
      <button class="btn btn-primary" onclick="window.history.back()">Cancel</button>
    </form>
  </div>

</body>

</html>