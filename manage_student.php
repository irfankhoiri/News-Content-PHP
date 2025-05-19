<?php
session_start();
include "connect.php"; // your database connection file
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
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin') {
  header("Location: login.php");
  exit;
}

// Check if a delete request has been made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
  $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
  // SQL query to delete the user
  $delete_query = "DELETE FROM users WHERE id = '$delete_id'";
  mysqli_query($conn, $delete_query);
  // Redirect to the same page to refresh the list of users
  header("Location: manage_student.php");
  exit;
}

// Fetch users from the database
$user_query = "SELECT * FROM users"; // replace with your table name
$result = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html>

<head>
  <title>USIM News Content - Admin Panel</title>
  <!-- DataTables CSS and JS -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
  <!-- Your other head elements -->
</head>

<body id="_4">
  <!-- Your navigation bar -->
  
  <ul id="nav-bar">
    <!-- <a href="index.php">
      <li id="home">Home</li>
    </a> -->

    <a href="contacts.php">
      <li>Contact</li>
    </a>

    <?php
    if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
    ?>
      <a href="manage_student.php">
        <li>Manage Student</li>
      </a>
    <?php
    }
    ?>
    <?php
    if (!isset($_SESSION['user'])) {
    ?>
      <a href="login.php">
        <li>Log In</li>
      </a>
    <?php
    } else {
    ?>
      <a href="ask.php">
        <li>Ask Question</li>
      </a>
      <a href="index.php">
        <li>News</li>
      </a>
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
  <!-- content -->
  <div class="container">
    <h2>User Management</h2>
    <table id="userTable" class="display">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Name</th>
          <th>Email</th>
          <th>Join Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['join_date']; ?></td>
            <td>
              <form method="post" action="">
                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Footer -->
  

  <!-- DataTables script to enhance the table -->
  <script>
    $(document).ready(function() {
      $('#userTable').DataTable();
    });
  </script>
  
</body>

</html>