<?php
// Establish a database connection (update with your database credentials)
include('connectDB.php');

// Retrieve user data from the form
$username = $_POST['U_Name'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$usertype = $_POST['types'];

// Insert user data into the database
$sql = "INSERT INTO user (U_Name, U_Contact, U_Email, U_Password, U_Types) VALUES ('$username', '$contact', '$email', '$password', '$usertype')";

if (mysqli_query($con, $sql)) {
    // Registration success
    echo "User registered successfully!";
} else {
    // Registration failed
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
?>