<ul id="nav-bar">
  <!-- Tag items -->
  <?php
  $query = "SELECT tags FROM category";
  $result = mysqli_query($conn, $query);

  $allTags = [];

  // Fetch the tags
  while ($row = mysqli_fetch_assoc($result)) {
    // Add the tag to the array
    $allTags[] = $row['tags'];
  }

  // Remove duplicates to get unique tags
  $uniqueTags = array_unique($allTags);

  // Generate the HTML for the tags
  foreach ($uniqueTags as $tag) {
    $activeClass = ($tag == $selected_tag) ? 'active' : '';
    echo '<a href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?tag=' . $tag . '"><li class="' . $activeClass . '">' . ucfirst($tag) . '</li></a>';
  }
  ?>
  <!-- Rest of the navigation items -->
  <a href="index.php">
    <li id="home" class="split">Main News</li>
  </a>
  <a href="likes.php">
    <li id="like" class="split">Bookmarks</li>
  </a>

  <?php if (isset($_SESSION['user']) && $_SESSION['user'] == "admin") : ?>
    <a href="manage_student.php">
      <li>Manage Student</li>
    </a>
  <?php endif; ?>
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