<?php
require 'vendor/autoload.php'; // Include PHPMailer autoload file
include("connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include your database connection here
// Initialize your database connection, e.g., $conn = new mysqli(...);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the email (you can add more validation)
    $email = $_POST["email"];

    // Generate a temporary password
    $temporaryPassword = generateTemporaryPassword();

    // Hash the temporary password before storing it in the database
    $hashedTemporaryPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

    // Update the user's password in your database using their email address
    // Example SQL query: UPDATE users SET password = '$hashedTemporaryPassword' WHERE email = '$email'
    // You should replace this with your database update logic

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging (set to DEBUG_SERVER for debugging)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server host
        $mail->SMTPAuth = true;
        $mail->Username = 'exora5797@gmail.com'; // Your SMTP username
        $mail->Password = 'Dr.Irfan01'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, you can use PHPMailer::ENCRYPTION_SMTPS for SMTPS

        // Sender and recipient settings
        $mail->setFrom('admin@example.com', 'Admin'); // Replace with your sender's email and name
        $mail->addAddress($email); // Recipient's email address
        $mail->Subject = 'Password Reset';
        $mail->Body = "Your temporary password is: $temporaryPassword";

        // Send the email
        $mail->send();
        echo "Temporary password sent to your email. Check your inbox.";

        // Close the database connection if you have one
        // $conn->close();
    } catch (Exception $e) {
        echo "Failed to send the email. Error: {$mail->ErrorInfo}";
    }

    // Hash the new password (recommended for security)

    // Update the password in the database
    $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt) {
        $stmt->bind_param("ss", $temporaryPassword, $email);
        if ($stmt->execute()) {
            // Password updated successfully
            echo "Password updated successfully.";
        } else {
            // Error updating the password
            echo "Error updating the password: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error preparing the update statement
        echo "Error preparing the statement: " . $conn->error;
    }
}

function generateTemporaryPassword() {
    // Generate a random temporary password (you can customize this)
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $temporaryPassword = substr(str_shuffle($characters), 0, 10); // Generates a 10-character password
    return $temporaryPassword;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset</h1>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
