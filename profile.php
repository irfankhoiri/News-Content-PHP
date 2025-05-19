<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
$message = '';
// Fetch user details from the database
// PHP Code above here ...

// Fetch user details from the database
$userData = array();
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id']; // Assuming you store user ID in session after login
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        $message = "Error fetching user data: " . mysqli_error($conn);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize user inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Update query
    $query = "UPDATE users SET name='$name', email='$email', phone_number='$phone_number', password='$password' WHERE id='" . $_SESSION['id'] . "'";

    // Execute query
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        // Error handling
        // You can add an error message if you want
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Profile Update</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="images/icon1.png">
</head>
<?php include "header.php"?>
<body>

    <!-- Ensure your PHP block above is correctly fetching data before this HTML block -->
    <div class="container mt-4">
        <div class="col">
            
            <h1 class="text-center">Profile Update for <?php echo htmlspecialchars($userData['username'] ?? ''); ?></h1>
        </div>
        <?php if (!empty($userData['profile_pic'])) : ?>
            <div class="text-center">
                <img src="<?php echo htmlspecialchars($userData['profile_pic']); ?>" alt="Profile Picture" class="img-thumbnail" style="max-width: 200px;">
            </div>
        <?php endif; ?>
        <?php if ($message) : ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($userData['password'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <button class="btn btn-primary" onclick="window.history.back()">Cancel</button>
        </form>
    </div>

</body>

</html>