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
                <li><a href="MainMenu.php" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="Building.php" class="nav-link px-2 text-white">Building</a></li>
                <li><a href="User.php" class="nav-link px-2 text-white">User</a></li>
                <li><a href="Chart.php" class="nav-link px-2 text-white">Chart</a></li>
            </ul>

            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>
<div class="container">
<table class="table table-bordered col-md-12">
    <h1 class="text-center bg-secondary col-md-12">DEFECT LIST</h1>
    <thead>
    <tr>
        <th scope="col">Building Name</th>
        <th scope="col">Floor Unit</th>
        <th scope="col">Username</th>
        <th scope="col">Defect Area</th>
        <th scope="col">Defect Description</th>
        <th scope="col">Date Submitted</th>
        <th scope="col">Time Left (Days)</th>
        <th scope="col">Status</th>
    </tr>
    </thead>

    <?php
    include("connectDB.php");
    $query = "SELECT * FROM defect WHERE D_Confirm = 'Pending' ORDER BY U_ID ASC";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $dueDateTimestamp = strtotime($row['DueDate']);
        $currentDateTimestamp = strtotime(date("Y-m-d H:i:s"));

        // Calculate the time left in days
        $timeLeftDays = floor(($dueDateTimestamp - $currentDateTimestamp) / (60 * 60 * 24));

        ?>

        <tbody>
        <tr>
            <th scope="row"><?php echo $row['B_Name']?></th>
            <td><?php echo $row['B_FU']?></td>
            <td><?php echo $row['U_Name']?></td>
            <td><?php echo $row['D_Area']?></td>
            <td><?php echo $row['D_Description']?></td>
            <td><?php echo $row['D_Date']?></td>
            <td><?php echo $timeLeftDays; ?></td>
            <td><?php echo $row['D_Confirm']?></td>

            <td>
                <form action="MainMenu.php" method="post">
                    <input type="hidden" name= "id" value="<?php echo $row['U_ID']?>"/>
                    <input type="hidden" name= "did" value="<?php echo $row['ID']?>"/>
                    <input type="submit" name="Done" class="text-white bg-success bg-gradient rounded-pill border-success w-25" value="Done">
                    <input type="submit" name="Deny" class="text-white bg-danger bg-gradient rounded-pill border-danger w-25" value="Deny">
                </form>
            </td>
        </tr>
        </tbody>
    <?php } ?>
    </div>

    <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

error_reporting(E_ALL);

if (isset($_POST['Done']) || isset($_POST['Deny'])) {
    $id = $_POST['id'];
    $did = $_POST['did'];

    // Get defect details for the email content
    $defectQuery = "SELECT * FROM defect WHERE U_ID = '$id' AND ID = '$did'";
    $defectResult = mysqli_query($con, $defectQuery);
    $defectData = mysqli_fetch_array($defectResult);

    // Get user email from the user table
    $userQuery = "SELECT U_Email FROM user WHERE U_ID = '$id'";
    $userResult = mysqli_query($con, $userQuery);
    $userData = mysqli_fetch_array($userResult);
    $userEmail = $userData['U_Email'];

    // Update user's confirmation status
    $status = isset($_POST['Done']) ? 'Done' : 'Deny';
    $updateDefect = "UPDATE defect SET D_Confirm = '$status' WHERE U_ID = '$id' AND ID = '$did'";
    $resultDefect = mysqli_query($con, $updateDefect);

    if ($resultDefect) {
        // Send email notification using PHPMailer with SMTP
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'defectsystem600@gmail.com';  // Replace with your SMTP username
            $mail->Password = 'mrnb ebpt shqw irsq';  // Replace with your SMTP password
            $mail->SMTPSecure = 'tls';  // You can use 'tls' or 'ssl' depending on your server configuration
            $mail->Port = 587;  // Replace with your SMTP port

            $mail->setFrom('defectsystem600@gmail.com', 'Defect Management System');
            $mail->addAddress($userEmail);
            $mail->Subject = 'Defect Status Update';
            $mail->Body = "Dear user,\n\nThe status of your defect has been updated to $status.\n\nDefect Details:\nBuilding Name: {$defectData['B_Name']}\nDefect Area: {$defectData['D_Area']}\nDefect Description: {$defectData['D_Description']}\n\nThank you.";

            $mail->send();
        
            // Use JavaScript to show an alert
            echo '<script>alert("Email notification sent successfully.");</script>';
    
            // Redirect to avoid resubmission of the form
            echo '<script>window.location.href = "MainMenu.php";</script>';
            exit();
        } catch (\Exception $e) {
            echo '<script>alert("Failed to send email notification. Error: ' . $mail->ErrorInfo . '");</script>';
        }
    } else {
        echo '<script>alert("Failed to update defect status.");</script>';
    }
}
?>

</table>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
