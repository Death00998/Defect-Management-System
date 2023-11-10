<?php
session_start();
include "connectDB.php";
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    // Redirect the user to the login page if not authenticated
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM user WHERE U_Email = '$email'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);

$error = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['D_Area'], $_POST['D_Description'], $_POST['Date'])) {
        $DArea = mysqli_real_escape_string($con, $_POST['D_Area']);
        $DDesc = mysqli_real_escape_string($con, $_POST['D_Description']);
        $Date = mysqli_real_escape_string($con, $_POST['Date']);
        $DDate = date('Y-m-d', strtotime($Date . '+30 days'));
        $UName = mysqli_real_escape_string($con, $row['U_Name']);
        $BFU = mysqli_real_escape_string($con, $row['B_UF']);
        $BName = mysqli_real_escape_string($con, $row['B_Name']);
        $UID = mysqli_real_escape_string($con, $row['U_ID']);
        $UTypes = mysqli_real_escape_string($con, $row['U_Types']);
        $DCon = $_POST['DConfirm'];

        $DPic = $_FILES['D_Pic'];

        if (!empty($DPic["name"])) {
            $fileName = basename($DPic["name"]);
            $targetDirectory = "uploads/";
            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($DPic["tmp_name"], $targetFilePath)) {
                    // Use prepared statements to prevent SQL injection
                    $insertQuery = "INSERT INTO defect (B_Name, B_FU, U_ID, U_Name, D_Pic, D_Area, D_Description, U_Types, D_Date, DueDate, D_Confirm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($con, $insertQuery);

                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, 'sssssssssss', $BName, $BFU, $UID, $UName, $targetFilePath, $DArea, $DDesc, $UTypes, $Date, $DDate, $DCon);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>alert("Defect Submit Succcessfully!!!");</script>';
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $error[] = '<script>alert("Failed to upload the files!");</script>';
                }
            } else {
                $error[] = '<script>alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.");</script>';
            }
        }

        // Check for any errors or missing data
        if (empty($DArea) || empty($DDesc)) {
            $error[] = "Please fill in all the fields";
        }
    }
}

// Handle and display the errors to the user
if (!empty($error)) {
    foreach ($error as $err) {
        echo $err . "<br>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Defect Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
</head>
<body>
<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="Index.php" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="Report.php" class="nav-link px-2 text-secondary">Report</a></li>
                <li><a href="Info.php" class="nav-link px-2 text-white">Info</a></li>
            </ul>
            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <form class="col" method="post" action="Report.php" enctype="multipart/form-data">
        <h1 class="mt-5">Submit A New Defect</h1>
        <div class="mb-3">
            <input type="text" class="form-control" id="D_Area" name="D_Area" placeholder="Defect Area" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="D_Description" name="D_Description"
                   placeholder="Defect Description" required>
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" id="D_Pic" name="D_Pic" accept="image/*" onchange="previewImage(event)">
        </div>
        <img id="imagePreview" src="" alt="Preview"
             style="max-width: 100%; max-height: 300px; display: none;">
        <input type="hidden" id="Date" name="Date" readonly>
        <input type="hidden" id="UName" name="UName" value="<?php echo $row['U_Name'] ?>" readonly>
        <input type="hidden" id="BFU" name="BFU" value="<?php echo $row['B_UF'] ?>" readonly>
        <input type="hidden" id="BName" name="BName" value="<?php echo $row['B_Name'] ?>" readonly>
        <input type="hidden" id="UID" name="UID" value="<?php echo $row['U_ID'] ?>" readonly>
        <input type="hidden" id="UType" name="UType" value="<?php echo $row['U_Types'] ?>" readonly>
        <input type="hidden" id="DConfirm" name="DConfirm" value="Pending" readonly>
        <div class="pt-1 mb-4">
            <button type="submit" class="btn btn-dark btn-lg btn-block">Report</button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const fileInput = event.target;
        const imagePreview = document.getElementById("imagePreview");

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = "block";
            };

            reader.readAsDataURL(fileInput.files[0]);
        } else {
            imagePreview.src = "";
            imagePreview.style.display = "none";
        }
    }

    // Get the current date and time
    var currentDate = new Date();

    // Format the date as a string (you can customize the format)
    var formattedDate = currentDate.toISOString(); // This will give you the date in ISO format (e.g., "2023-11-01T10:15:30.000Z")

    // Set the formatted date as the value for the hidden input field
    document.getElementById("Date").value = formattedDate;
</script>
</body>
</html>
