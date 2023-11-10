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
} else {
    // Redirect the user to the login page if not authenticated
    header('Location: login.php');
    exit();
}

$sql = "SELECT defect.U_ID, defect.D_Area, defect.D_Description, defect.D_Confirm, defect.D_Pic FROM defect
        LEFT JOIN user ON defect.U_ID = user.U_ID
        WHERE user.U_Email = '$email'
        UNION
        SELECT defect.U_ID, defect.D_Area, defect.D_Description, defect.D_Confirm, defect.D_Pic FROM defect
        RIGHT JOIN user ON defect.U_ID = user.U_ID
        WHERE user.U_Email = '$email'";
$result = mysqli_query($con, $sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="Index.php" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="Report.php" class="nav-link px-2 text-white">Report</a></li>
                    <li><a href="Info.php" class="nav-link px-2 text-white">Info</a></li>
                </ul>
                <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-3 g-4" id="defectContainer">
            <?php while ($row = mysqli_fetch_array($result)) { ?>
                <div class="col">
                    <div class="card">
                        <img src="./uploads/<?php echo $row['D_Pic']; ?>" class="card-img-top" alt="Defect Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['D_Area']?></h5>
                            <p class="card-text"><?php echo $row['D_Description']?></p>
                            <p class="card-text"><?php echo $row['D_Confirm']?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Function to dynamically add a new defect card
        function addDefectCard(data) {
            const container = document.getElementById('defectContainer');

            const newCard = document.createElement('div');
            newCard.classList.add('col');

            newCard.innerHTML = `
                <div class="card">
                    <img src="./uploads/${data.D_Pic}" class="card-img-top" alt="Defect Image">
                    <div class="card-body">
                        <h5 class="card-title">${data.D_Area}</h5>
                        <p class="card-text">${data.D_Description}</p>
                        <p class="card-text">${data.D_Confirm}</p>
                    </div>
                </div>
            `;

            container.appendChild(newCard);
        }

        // Example: You can call this function with new defect data to add a new card
        // const newDefectData = {
        //     D_Pic: 'new_defect_image.jpg',
        //     D_Area: 'New Defect Area',
        //     D_Description: 'New Defect Description',
        //     D_Confirm: 'New Defect Confirmation',
        // };
        // addDefectCard(newDefectData);
    </script>
</body>
</html>
