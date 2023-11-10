<?php
include('connectDB.php');

$sql = "SELECT Confirmation, COUNT(*) as count FROM user WHERE Confirmation IN ('Pending', 'Approve') GROUP BY Confirmation";
$result = $con->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
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
                <li><a href="BUilding.php" class="nav-link px-2 text-white">Building</a></li>
                <li><a href="User.php" class="nav-link px-2 text-white">User</a></li>
                <li><a href="Chart.php" class="nav-link px-2 text-secondary ">Chart</a></li>
            </ul>
            <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
    </div>
</header>

<div class="container mt-4">
  <h1>User Chart</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
        <canvas id="chartContainer" style="height: 15px; width: px;"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    // Data from PHP
    var data = <?php echo json_encode($data); ?>;
    console.log(data); // Debugging statement

    // Extracting labels and values
    var labels = data.map(function(item) {
        return item.Confirmation;
    });

    var values = data.map(function(item) {
        return parseInt(item.count);
    });

    // Creating the pie chart using Chart.js
    var ctx = document.getElementById('chartContainer').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                ],
            }],
        },
    });
</script>

</body>
</html>
