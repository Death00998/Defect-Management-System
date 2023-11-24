<?php
session_start();
include('connectDB.php');
?>

<?php
$email = $_POST['email'];
$password = $_POST['password'];

// To prevent SQL injection
$username = mysqli_real_escape_string($con, $email);
$password = mysqli_real_escape_string($con, $password);

// Check if it's an admin login
$sql = "SELECT * FROM admin WHERE BINARY Email = '$email'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    include("MainMenu.php");
    }
     else {
    // If admin login failed, check the user login
    $sql1 = "SELECT U_Password FROM user WHERE BINARY U_Email = '$email' AND Confirmation = 'approve'";
    $result1 = mysqli_query($con, $sql1);
    $count1 = mysqli_num_rows($result1);

    if ($count1 == 1) {
        // User login
        $row1 = mysqli_fetch_assoc($result1);
        $hashedPassword = $row1['U_Password'];
        if (password_verify($password, $hashedPassword)) {
            // User login successful
            $_SESSION["email"] = $email;
            include("Index.php");

        } else {
            // User login failed
            echo '<script>alert("WRONG USERNAME AND PASSWORD")</script>';
            include("Login.php");
        }
    } else {
        // Both admin and user login failed
        echo '<script>alert("WRONG USERNAME AND PASSWORD")</script>';
        include("Login.php");
        }
     }


?>
