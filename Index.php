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
?>
<!DOCTYPE html>

<html>
  <head>
    <title>Defect Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  </head>
  <body>
    <?php
      $sql = "SELECT defect.U_ID, defect.D_Area, defect.D_Description, defect.D_Confirm FROM defect
      LEFT JOIN user ON defect.U_ID = user.U_ID
      WHERE user.U_Email = '$email'
      UNION
      SELECT defect.U_ID, defect.D_Area, defect.D_Description, defect.D_Confirm FROM defect
      RIGHT JOIN user ON defect.U_ID = user.U_ID
      WHERE user.U_Email = '$email'";
$result = mysqli_query($con, $sql);

      $row = mysqli_fetch_array($result);
    ?>
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
    </div>
  </header>
      
<div class="card" style="width: 18rem" ng-repeat="card in cards">
<img src="./image/<?php echo $data['filename']; ?>">
  <div class="card-body">
    <h4 class="card-title"><?php echo $row['D_Area']?></h4>
    <h6 class="card-text"><?php echo $row['D_Description']?></h6>
    <p><?php echo $row['D_Confirm']?></p>
  </div>
</div>
  </table>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>
