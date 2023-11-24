<?php
include("connectDB.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);

    // Check if the token exists and is still valid (e.g., within a certain timeframe)
    $checkTokenQuery = "SELECT * FROM password_resets WHERE token = '$token' AND created_at >= NOW() - INTERVAL 1 HOUR";
    $result = mysqli_query($con, $checkTokenQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        // Token is valid, allow the user to reset their password
        // Display a form for the new password
        // ...

        // After the password is updated, delete the used token
        $deleteTokenQuery = "DELETE FROM password_resets WHERE token = '$token'";
        mysqli_query($con, $deleteTokenQuery);
    } else {
        echo 'Invalid or expired token.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
</head>

<body>

<header class="p-3 text-bg-dark">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex flex-wrap align-items-center justify-content-center">
            <h2>DEFECT MANAGEMENT SYSTEM</h2>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-center">
            <button type="button" class="btn btn-outline-light me-2" onclick="location.href='Login.php'">Login</button>
            <button type="button" class="btn btn-warning" onclick="location.href='SignUp.php'">Sign-up</button>
        </div>
    </div>
</header>

    <div class="container mt-5">
        <h2 class="mb-4">Reset Password</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password:</label>
                <input type="password" class="form-control" name="newPassword" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>

