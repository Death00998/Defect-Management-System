<?php
// Start the session or resume the existing session
session_start();
include "connectDB.php";

// Check if the database connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Retrieve user information from the database based on the email
    $sql = "SELECT * FROM user WHERE U_Email = '$email'";
    $result = mysqli_query($con, $sql);

    // Fetch the user details
    $userDetails = mysqli_fetch_assoc($result);
} else {
    // Redirect the user to the login page if not authenticated
    header('Location: login.php');
    exit();
}

// Function to update the user password
function updateUserPassword($newPassword) {
    global $con, $email;

    // Update the user password in the database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateSql = "UPDATE user SET U_Password = '$hashedPassword' WHERE U_Email = '$email'";
    mysqli_query($con, $updateSql);
}

// Function to update user information
function updateUserInfo($fullName, $contact) {
    global $con, $email;

    // Update user information in the database
    $updateSql = "UPDATE user SET U_Name = '$fullName', U_Contact = '$contact' WHERE U_Email = '$email'";
    mysqli_query($con, $updateSql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['newPassword'])) {
        // Update the user password
        updateUserPassword($_POST['newPassword']);

        // Show the success message modal
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#successModal").modal("show");
                });
              </script>';
    } elseif (isset($_POST['fullName']) && isset($_POST['contact'])) {
        // Update user information
        $fullName = $_POST['fullName'];
        $contact = $_POST['contact'];
        updateUserInfo($fullName, $contact);

        // Show the success message modal
        echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#successModal").modal("show");
                });
              </script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="Index.php" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="Report.php" class="nav-link px-2 text-white">Report</a></li>
                <li><a href="Info.php" class="nav-link px-2 text-secondary">Info</a></li>
            </ul>
            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>

<div class="container mt-4">
    <h2>User Information</h2>
    <form id="userInfoForm" method="post" action="">
        <div class="mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $userDetails['U_Name']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" value="<?php echo $userDetails['U_Email']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $userDetails['U_Contact']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="userType" class="form-label">User Type</label>
            <input type="text" class="form-control" id="userType" value="<?php echo $userDetails['U_Types']; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
        </div>

        <button type="button" class="btn btn-primary" onclick="editUserInfo()">Edit</button>
        <button type="submit" class="btn btn-success" style="display: none;">Save</button>
    </form>
</div>

<!-- Success Message Popup -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                User information updated successfully!
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript library -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    function editUserInfo() {
        // Implement your logic to enable editing of user information
        // For example, you can enable form fields for editing
        // You may use JavaScript or a combination of JavaScript and PHP for this purpose
        document.getElementById('fullName').readOnly = false;
        document.getElementById('contact').readOnly = false;
        document.getElementById('newPassword').readOnly = false;

        // Show the "Save" button
        document.querySelector('button[type="submit"]').style.display = 'block';

        // Hide the "Edit" button
        document.querySelector('button[type="button"]').style.display = 'none';
    }
</script>

</body>
</html>