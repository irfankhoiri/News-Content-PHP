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
  <!-- Navigation bar from provided header -->
  <?php include_once('header.php'); ?>


  <!-- Content -->
  <div id="content">
    <h2>News Dashboard</h2>
    <button onclick="window.location.href='add_news.php'" class="btn btn-primary" id="addNewsBtn">Add New News</button>

    <table id="newsTable" class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Image URL</th>
          <th>Actions</th> <!-- Added Actions Column -->
        </tr>
      </thead>
      <tbody>
        <?php
        // Replace 'category' with the actual name of your table that contains the news
        $query = "SELECT * FROM category"; // Make sure to select the image_url column as well.
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
          // Fetch each row and output table data
          while ($row = mysqli_fetch_assoc($result)) {
            $id = htmlspecialchars($row['id']); // Sanitize the ID for safe output
            echo "<tr>";
            echo "<td>" . $id . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['image_url']) . "' alt='News Image' height='50'></td>";

            // Edit and Delete buttons
            echo "<td>";
            // The edit_news.php and delete_news.php scripts need to be created to handle these actions
            echo "<a href='edit_news.php?id=" . $id . "' class='btn btn-secondary btn-sm'>Edit</a> ";
            echo "<a href='delete_news.php?id=" . $id . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this news item?\")'>Delete</a>";
            echo "</td>";

            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No news found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

  </div><!-- content -->

  <!-- Footer from provided footer -->
  <?php include_once('footer.php'); ?>

  <!-- Bootstrap JS and other necessary scripts-->
  <!-- jQuery library -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- DataTables JS -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
  <!-- Initialize DataTables -->
  <script>
    $(document).ready(function() {
      $('#newsTable').DataTable();
    });
  </script>
  <!-- ... -->
</body>

</html>