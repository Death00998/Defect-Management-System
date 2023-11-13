<?php
include('connectDB.php');

// User data
$sqlUser = "SELECT Confirmation, COUNT(*) as count FROM user WHERE Confirmation IN ('Pending', 'Approve') GROUP BY Confirmation";
$resultUser = $con->query($sqlUser);
$dataUser = array();
while ($row = $resultUser->fetch_assoc()) {
    $dataUser[] = $row;
}

// Building data
$sqlBuildingOwned = "SELECT COUNT(*) as count FROM building WHERE U_Name IS NOT NULL";
$resultBuildingOwned = $con->query($sqlBuildingOwned);
$ownedCount = $resultBuildingOwned->fetch_assoc()['count'];

$sqlBuildingUnowned = "SELECT COUNT(*) as count FROM building WHERE U_Name IS NULL";
$resultBuildingUnowned = $con->query($sqlBuildingUnowned);
$unownedCount = $resultBuildingUnowned->fetch_assoc()['count'];

// Defect data
$sqlDefectStatus = "SELECT D_Confirm, COUNT(*) as count FROM defect GROUP BY D_Confirm";
$resultDefectStatus = $con->query($sqlDefectStatus);
$dataDefectStatus = array();
while ($row = $resultDefectStatus->fetch_assoc()) {
    $dataDefectStatus[] = $row;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="MainMenu.php" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="Building.php" class="nav-link px-2 text-white">Building</a></li>
                <li><a href="User.php" class="nav-link px-2 text-white">User</a></li>
                <li><a href="Chart.php" class="nav-link px-2 text-secondary">Chart</a></li>
            </ul>
            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>

<div class="container mt-4">
  <h1>User Chart</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="row">
                <div class="col">
                    <canvas id="chartUserContainer" style="height: 200px; width: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
  <h1>Building Chart</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="row">
                <div class="col">
                    <canvas id="chartBuildingContainer" style="height: 200px; width: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
  <h1>Defect Status Chart</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="row">
                <div class="col">
                    <canvas id="chartDefectStatusContainer" style="height: 200px; width: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    // User data from PHP
    var dataUser = <?php echo json_encode($dataUser); ?>;
    var labelsUser = dataUser.map(function(item) {
        return item.Confirmation;
    });
    var valuesUser = dataUser.map(function(item) {
        return parseInt(item.count);
    });

    // Building data from PHP
    var buildingData = {
        ownedCount: <?php echo $ownedCount; ?>,
        unownedCount: <?php echo $unownedCount; ?>
    };

    // Defect status data from PHP
    var dataDefectStatus = <?php echo json_encode($dataDefectStatus); ?>;
    var labelsDefectStatus = dataDefectStatus.map(function(item) {
        switch(item.D_Confirm) {
            case 'Done':
                return 'Done';
            case 'Deny':
                return 'Deny';
            case 'Pending':
                return 'Pending';
            default:
                return 'Undefined';
        }
    });
    var valuesDefectStatus = dataDefectStatus.map(function(item) {
        return parseInt(item.count);
    });

    // Creating the doughnut chart for users using Chart.js
    var ctxUser = document.getElementById('chartUserContainer').getContext('2d');
    var myPieChartUser = new Chart(ctxUser, {
        type: 'doughnut',
        data: {
            labels: labelsUser,
            datasets: [{
                data: valuesUser,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                ],
            }],
        },
    });

    // Creating the doughnut chart for buildings using Chart.js
    var ctxBuilding = document.getElementById('chartBuildingContainer').getContext('2d');
    var myPieChartBuilding = new Chart(ctxBuilding, {
        type: 'doughnut',
        data: {
            labels: ['Owned Buildings', 'Unowned Buildings'],
            datasets: [{
                data: [buildingData.ownedCount, buildingData.unownedCount],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                ],
            }],
        },
    });

    // Creating the doughnut chart for defect status using Chart.js
    var ctxDefectStatus = document.getElementById('chartDefectStatusContainer').getContext('2d');
    var myPieChartDefectStatus = new Chart(ctxDefectStatus, {
        type: 'doughnut',
        data: {
            labels: labelsDefectStatus,
            datasets: [{
                data: valuesDefectStatus,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                ],
            }],
        },
    });
</script>

</body>
</html>
