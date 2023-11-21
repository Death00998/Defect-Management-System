<?php
// Start the session or resume the existing session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

    <style>
        /* Custom styles for the image */
        .defect-card-img {
            max-height: 200px; /* Adjust the maximum height as needed */
            object-fit: cover; /* or "contain" based on your preference */
        }
    </style>

</head>
<body>
<header class="p-3 bg-dark text-white">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex flex-wrap align-items-center">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="Index.php" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="Report.php" class="nav-link px-2 text-white">Report</a></li>
                <li><a href="Info.php" class="nav-link px-2 text-white">Info</a></li>
            </ul>
        </div>
        <div class="text-center">
            <h1>DEFECT MANAGEMENT SYSTEM</h1>
        </div>
        <div>
            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>


    <div class="container mt-4">
    <?php
    // Check if there are rows in the result
    if ($result && mysqli_num_rows($result) > 0) {
    ?>
        <div class="row row-cols-1 row-cols-md-3 g-4" id="defectContainer">
            <?php
            while ($row = mysqli_fetch_array($result)) {
                // Adjust the image path based on the stored value
                $imagePath = (strpos($row['D_Pic'], 'uploads/') === false) ? "./uploads/{$row['D_Pic']}" : "./{$row['D_Pic']}";
            ?>
                <div class="col">
                    <div class="card">
                        <!-- Add the "defect-card-img" class to the img element -->
                        <img src="<?php echo $imagePath; ?>" class="card-img-top defect-card-img" alt="Defect Image" onerror="console.error('Error loading image:', this.src);">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['D_Area']?></h5>
                            <p class="card-text"><?php echo $row['D_Description']?></p>
                            <p class="card-text"><?php echo $row['D_Confirm']?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php
    } else {
        // Display a message when there is no data
        echo '<p class="text-center">No defects found.</p>';
    }
    ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Function to dynamically add a new defect card
        function addDefectCard(data) {
        const container = document.getElementById('defectContainer');

        const newCard = document.createElement('div');
        newCard.classList.add('col');

        const imagePath = "./uploads/" + data.D_Pic;

        newCard.innerHTML = `
            <div class="card">
                <img src="${imagePath}" class="card-img-top" alt="Defect Image" onerror="console.error('Error loading image:', this.src);">
                <div class="card-body">
                    <h5 class="card-title">${data.D_Area}</h5>
                    <p class="card-text">${data.D_Description}</p>
                    <p class="card-text">${data.D_Confirm}</p>
                </div>
            </div>
        `;

        container.appendChild(newCard);
    }

    </script>
</body>
</html>
