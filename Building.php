<!DOCTYPE html>
<html>
<head>
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex flex-wrap align-items-center justify-content-center">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="MainMenu.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="Building.php" class="nav-link px-2 text-secondary">Building</a></li>
                    <li><a href="User.php" class="nav-link px-2 text-white">User</a></li>
                    <li><a href="Chart.php" class="nav-link px-2 text-white">Chart</a></li>
                </ul>
            </div>
            <div class="text-center">
                <h1>DEFECT MANAGEMENT SYSTEM</h1>
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-center">
                <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
            </div>
        </div>
    </div>
</header>



<br>
<div class="container">
<form method="post" action="Building.php" autocomplete="off">
    <h1 class="text-center text-white bg-secondary col-md-12">ADD BUILDING</h1>
    <div class="row g-4">
        <div class="col">
            <input type="text" class="form-control" id="BuildName" name="BuildName" placeholder="Building Name" required>
        </div>
        <div class="col">
            <input type="number" class="form-control" id="FloorNum" name="FloorNum" placeholder="Insert Floor Number" required>
        </div>
        <div class="col">
            <input type="number" class="form-control" id="UnitNum" name="UnitNum" placeholder="Insert Unit Number" required>
        </div>
    </div>

    <input type="hidden" id="OwnBy" name="OwnBy" value="NULL">
    <br>
    <div class="container">
        <div class="row align-self-end">
            <input type="submit" id="submit" name="submit" value="Add Building">
        </div>
    </div>
</form>
</div>
<!-- Move the search form outside of the main form -->
<br><br>
<div class="container">
    <form method="post" action="Building.php" class="row align-items-end" autocomplete="off">
        <div class="col-md-6 mb-3">
            <div class="form-outline">
                <input id="search" type="search" class="form-control" placeholder="Search Building" name="search">
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="sort" class="mr-2">Sort By:</label>
                <select class="form-control" id="sort" name="sort">
                    <option value="B_Name_ASC">Building Name (Ascending)</option>
                    <option value="B_Name_DESC">Building Name (Descending)</option>
                    <option value="B_FU_ASC">Unit Number (Ascending)</option>
                    <option value="B_FU_DESC">Unit Number (Descending)</option>
                    <option value="U_Name_ASC">Owner Name (Ascending)</option>
                    <option value="U_Name_DESC">Owner Name (Descending)</option>
                </select>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="form-outline">
                <button type="submit" class="btn btn-primary">
                    Search <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>


<?php
session_start(); // Start the session

include('connectDB.php');

// Function to add a building
function addBuilding($con, $B_Name, $FloorNum, $UnitNum) {
    for ($floor = 1; $floor <= $FloorNum; $floor++) {
        for ($unit = 1; $unit <= $UnitNum; $unit++) {
            // Define the values to insert
            $floor= sprintf('%02d', $floor);
            $unit = sprintf('%02d', $unit);
            $B_FU = "$floor-$unit";

            // Check if the record already exists
            $checkQuery = "SELECT B_ID FROM building WHERE B_Name = ? AND B_FU = ?";
            $checkStmt = $con->prepare($checkQuery);
            $checkStmt->bind_param("ss", $B_Name, $B_FU);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows == 0) {
                // Record does not exist, so insert it
                $insertQuery = "INSERT INTO building (B_Name, B_FU, U_Name) VALUES (?, ?, NULL)";
                $insertStmt = $con->prepare($insertQuery);
                $insertStmt->bind_param("ss", $B_Name, $B_FU);

                if ($insertStmt->execute()) {
                    $_SESSION['message'] = "Building added successfully";
                } else {
                    $_SESSION['message'] = "Error adding building: " . $con->error;
                }
            } else {
                $_SESSION['message'] = "Record with $B_Name and $B_FU already exists. Skipped insertion.";
            }
        }
    }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $B_Name = $_POST['BuildName'];
    $FloorNum = $_POST['FloorNum'];
    $UnitNum = $_POST['UnitNum'];

    // Add the building
    addBuilding($con, $B_Name, $FloorNum, $UnitNum);

    // Redirect to the same page to clear the form and prevent form resubmission
    header("Location: Building.php");
    exit();
}

// Display success or failure message if set
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']); // Clear the message after displaying it
}
// Sorting logic
if (isset($_POST['sort'])) {
    $sort = $_POST['sort'];

    switch ($sort) {
        case 'B_Name_ASC':
            $orderBy = 'B_Name ASC';
            break;
        case 'B_Name_DESC':
            $orderBy = 'B_Name DESC';
            break;
        case 'B_FU_ASC':
            $orderBy = 'B_FU ASC';
            break;
        case 'B_FU_DESC':
            $orderBy = 'B_FU DESC';
            break;
        case 'U_Name_ASC':
            $orderBy = 'U_Name ASC';
            break;
        case 'U_Name_DESC':
            $orderBy = 'U_Name DESC';
            break;
        default:
            $orderBy = 'B_Name ASC';
            break;
        
    }

    // Modify the query based on the selected sorting option
    if (isset($_POST['search'])) {
        $search = mysqli_real_escape_string($con, $_POST['search']);
        $query = "SELECT * FROM building WHERE B_Name LIKE '%$search%' ORDER BY $orderBy";
        $result = mysqli_query($con, $query);
    } else {
        $query = "SELECT * FROM building ORDER BY $orderBy";
        $result = mysqli_query($con, $query);
    }

    if ($result) {
        // Display the sorted results
        ?>
        <div class="container">
            <h1 class="text-center text-white bg-success col-md-12">BUILDING INFORMATION</h1>
            <table class="table table-bordered col-md-12">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Building Name</th>
                    <th scope="col">Unit Number</th>
                    <th scope="col">Own By</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $row['B_ID'] ?></th>
                        <td><?php echo $row['B_Name'] ?></td>
                        <td><?php echo $row['B_FU'] ?></td>
                        <td><?php echo $row['U_Name'] ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo "Error executing query: " . mysqli_error($con);
    }
} else {
    // Display all buildings
    $query = "SELECT * FROM building ORDER BY B_Name ASC";
    $result = mysqli_query($con, $query);

    if ($result) {
        ?>
        <div class="container">
            <br><br><br>
            <h1 class="text-center text-white bg-success col-md-12">BUILDING INFORMATION</h1>
            <table class="table table-bordered col-md-12">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Building Name</th>
                    <th scope="col">Unit Number</th>
                    <th scope="col">Own By</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $row['B_ID'] ?></th>
                        <td><?php echo $row['B_Name'] ?></td>
                        <td><?php echo $row['B_FU'] ?></td>
                        <td><?php echo $row['U_Name'] ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo "Error executing query: " . mysqli_error($con);
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
