<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: profile.php");
}

if (isset($_POST["submit"])) {
    function valid($data)
    {
        $data = trim(stripslashes(htmlspecialchars($data)));
        return $data;
    }

    $email = valid($_POST["email"]);

    include("connect.php");

    // Check if the email exists in the database
    $checkQuery = "SELECT id FROM users WHERE email='$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Generate a temporary password for simplicity (in a real-world scenario, you would send a password reset link to the user's email)
        $tempPassword = bin2hex(random_bytes(8));

        // Update the user's password with the temporary password
        $updateQuery = "UPDATE users SET password='$tempPassword' WHERE email='$email'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            // Send the temporary password to the user's email (replace with your email sending logic)
            $to = $email;
            $subject = "Password Reset";
            $message = "Your temporary password is: $tempPassword";
            $headers = "From: webmaster@example.com"; // Replace with your email address

            mail($to, $subject, $message, $headers);

            echo "<script>window.alert('Temporary password sent to your email.');</script>";
        } else {
            echo "<script>window.alert('Error generating temporary password. Please try again.');</script>";
        }
    } else {
        echo "<script>window.alert('Email not found in our records.');</script>";
    }

    mysqli_close($conn);
}
?>

<!-- Rest of the HTML code remains unchanged -->
