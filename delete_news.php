<?php
session_start();
include('connect.php');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Prepare the delete statement to prevent SQL injection
  $stmt = $conn->prepare("DELETE FROM category WHERE id = ?");
  $stmt->bind_param("i", $id); // 'i' specifies the variable type => 'integer'

  // Attempt to execute the prepared statement
  if ($stmt->execute()) {
    // Record was deleted successfully. Redirect to the news dashboard page
    header("Location: news.php");
    exit();
  } else {
    echo "Oops! Something went wrong. Please try again later.";
  }

  // Close statement
  $stmt->close();
}

// Close connection
$conn->close();
