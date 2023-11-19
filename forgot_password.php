<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

include("connectDB.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = bin2hex(random_bytes(32)); // Generate a random token

    // Insert the token into the password_resets table
    $insertTokenQuery = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
    $result = mysqli_query($con, $insertTokenQuery);

    if ($result) {
        // Send an email with a link containing the token
        try {
            $mail = new PHPMailer(true);

            // Configure PHPMailer with Gmail settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'defectsystem600@gmail.com'; // Your Gmail email address
            $mail->Password = 'mrnb ebpt shqw irsq'; // Your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('your@gmail.com', 'Your Name'); // Replace with your name
            $mail->addAddress($email);

            // Email content
            $resetLink = "http://localhost/DMS/reset_password.php?token=$token";
            $mail->Subject = 'Password Reset';
            $mail->Body = "Dear user,\n\nYou can reset your password by clicking the following link:\n$resetLink\n\nIf you didn't request a password reset, please ignore this email.";

            // Send email
            $mail->send();
            echo '<script>alert("Password reset instructions sent to your email.");</script>';
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Error generating password reset token.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
</head>

<body>

    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-end">
                <button type="button" class="btn btn-outline-light me-2" onclick="location.href='Login.php'">Login</button>
                <button type="button" class="btn btn-warning" onclick="location.href='SignUp.php'">Sign-up</button>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="mb-4">Forgot Password</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
