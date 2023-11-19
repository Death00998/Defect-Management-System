<!DOCTYPE html>
<html>

<head>
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
</head>

<body>

    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="MainMenu.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="Building.php" class="nav-link px-2 text-white">Building</a></li>
                    <li><a href="User.php" class="nav-link px-2 text-secondary">User</a></li>
                    <li><a href="Chart.php" class="nav-link px-2 text-white">Chart</a></li>
                </ul>

                <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
            </div>
        </div>
    </header>
    <div class="container">
    <table class="table table-bordered col-md-12">
        <h1 class="text-center bg-secondary col-md-12">PENDING LIST</h1>
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email Address</th>
                <th scope="col">Contact Number</th>
                <th scope="col">Building Name</th>
                <th scope="col">Unit Number</th>
                <th scope="col">User Type</th>
                <th scope="col">Status</th>
            </tr>
        </thead>

        <?php
        include("connectDB.php");
        $query = "SELECT * FROM user WHERE Confirmation = 'Pending' ORDER BY U_ID ASC";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) {
        ?>

            <tbody>
                <tr>
                    <th scope="row"><?php echo $row['U_ID'] ?></th>
                    <td><?php echo $row['U_Name'] ?></td>
                    <td><?php echo $row['U_Email'] ?></td>
                    <td><?php echo $row['U_Contact'] ?></td>
                    <td><?php echo $row['B_Name'] ?></td>
                    <td><?php echo $row['B_UF'] ?></td>
                    <td><?php echo $row['U_Types'] ?></td>
                    <td><?php echo $row['Confirmation'] ?></td>

                    <td>
                        <form action="User.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $row['U_ID'] ?>" />
                            <input type="submit" name="approve"
                                class="text-white bg-success bg-gradient rounded-pill border-success w-25" value="Approve">
                            <input type="submit" name="delete"
                                class="text-white bg-danger bg-gradient rounded-pill border-danger w-25" value="delete">
                        </form>
                    </td>
                </tr>
            </tbody>
        <?php } ?>

        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'phpmailer/src/Exception.php';
        require 'phpmailer/src/PHPMailer.php';
        require 'phpmailer/src/SMTP.php';
        
        error_reporting(E_ALL);

        if (isset($_POST['approve'])) {
            $id = $_POST['id'];

            // Update user's confirmation status
            $updateUser = "UPDATE user SET Confirmation = 'approve' WHERE U_ID = '$id'";
            $resultUser = mysqli_query($con, $updateUser);

            if (!$resultUser) {
                echo 'Error updating user: ' . mysqli_error($con);
            } else {
                // Retrieve the user's name and floor unit
                $nameQuery = "SELECT U_Name, B_UF, U_Email FROM user WHERE U_ID = '$id'";
                $nameResult = mysqli_query($con, $nameQuery);

                if (!$nameResult) {
                    echo 'Error fetching user details: ' . mysqli_error($con);
                } else {
                    $row = mysqli_fetch_assoc($nameResult);
                    $userName = $row['U_Name'];
                    $floorUnit = $row['B_UF'];
                    $userEmail = $row['U_Email'];

                    // Update the building based on user's name and floor unit
                    $updateBuilding = "UPDATE building SET U_Name = '$userName' WHERE U_Name IS NULL AND B_FU = '$floorUnit'";
                    $resultBuilding = mysqli_query($con, $updateBuilding);

                    if (!$resultBuilding) {
                        echo 'Error updating building: ' . mysqli_error($con);
                    } else {
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
            $mail->Body = "Dear $userName,\n\nYour account has been approved.\n\nThank you.";

            $mail->send();
        
            // Use JavaScript to show an alert
            echo '<script>alert("Email notification sent successfully.");</script>';
    
            // Redirect to avoid resubmission of the form
            echo '<script>window.location.href = "MainMenu.php";</script>';
            exit();
        } catch (\Exception $e) {
            echo '<script>alert("Failed to send email notification. Error: ' . $mail->ErrorInfo . '");</script>';
        }
                    }
                }
            }
        }

        if (isset($_POST['delete'])) {
            $id = $_POST['id'];

            // Delete the user with the selected ID
            $deleteQuery = "DELETE FROM user WHERE U_ID = $id";
            $result = mysqli_query($con, $deleteQuery);

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
                $mail->Body = "Dear $userName,\n\nYour account has been Deny.\n\nThank you.";
    
                $mail->send();
            
                // Use JavaScript to show an alert
                echo '<script>alert("Email notification sent successfully.");</script>';
        
                // Redirect to avoid resubmission of the form
                echo '<script>window.location.href = "MainMenu.php";</script>';
                exit();
            } catch (\Exception $e) {
                echo '<script>alert("Failed to send email notification. Error: ' . $mail->ErrorInfo . '");</script>';
            }

            // Update the IDs for remaining users
            $updateQuery = "UPDATE user SET U_ID = U_ID - 1 WHERE U_ID > $id";
            $result = mysqli_query($con, $updateQuery);

            header('Location: User.php');
        }
        ?>
        </div>
        <div class="container">
        <table class="table table-bordered col-md-12">
            <h1 class="text-center text-white bg-success col-md-12">APPROVED LIST</h1>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Building Name</th>
                    <th scope="col">Unit Number</th>
                    <th scope="col">User Type</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>

            <?php
            $query = "SELECT * FROM user WHERE Confirmation = 'Approve' ORDER BY U_ID ASC";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_array($result)) {
            ?>

                <tbody>
                    <tr>
                        <th scope="row"><?php echo $row['U_ID'] ?></th>
                        <td><?php echo $row['U_Name'] ?></td>
                        <td><?php echo $row['U_Email'] ?></td>
                        <td><?php echo $row['U_Contact'] ?></td>
                        <td><?php echo $row['B_Name'] ?></td>
                        <td><?php echo $row['B_UF'] ?></td>
                        <td><?php echo $row['U_Types'] ?></td>
                        <td><?php echo $row['Confirmation'] ?></td>
                    </tr>
                </tbody>
            <?php } ?>

    </table>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
