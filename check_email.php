<?php
// ... (database connection)
include('connectDB.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $checkEmailQuery = "SELECT * FROM user WHERE U_Email = '$email'";
    $result = mysqli_query($con, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "exists";
    } else {
        echo "unique";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>