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
          <li><a href="MainMenu.php" class="nav-link px-2 text-white">Home</a></li>
          <li><a href="Building.php" class="nav-link px-2 text-secondary">Building</a></li>
          <li><a href="User.php" class="nav-link px-2 text-white">User</a></li>
          <li><a href="Chart.php" class="nav-link px-2 text-white">Chart</a></li>
        </ul>
          <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
      </div>
    </div>
  </header>
      
  <form>
      <h1 class="text-center text-white bg-secondary col-md-12">ADD BUILDING</h1>
      <div class="row g-4">
        <div class="col">
          <input type="text" class="form-control" id="BuildName" name="BuildName" placeholder="Building Name" required>
        </div>
        <div class="col">
          <input type="number" class="form-control" id="FloorNum" name="FloorNum" placeholder="Insert Floor Number" required>
        </div>
        <div class="col">
          <input type="number" class="form-control"  id="UnitNum" name="UnitNum" placeholder="Insert Unit Number" required>
        </div>
      </div>

      <input type="hidden" id="OwnBy" name="OwnBy" value="NULL">
      <br>
      <div class="container">
      <div class="row align-self-end">
        <input type="submit" id="submit" name="submit">
      </div>  
      </div>
  </form>
  <?php
    include('connectDB.php');
    if(isset($_POST['submit'])){

      if (isset($_POST['BuildName']) && isset($_POST['FloorNum']) && isset($_POST['UnitNum'])) {
        $B_Name = $_POST['BuildName'];

    for ($floor = 1; $floor <= $_POST['FloorNum']; $floor++) {
      for ($unit = 1; $unit <= $_POST['UnitNum']; $unit++) {
          // Define the values to insert
          $B_FU = "$floor-$unit";
          $U_Name = "NULL";
          // Prepare and execute the SQL query
          $sql = "INSERT INTO building (B_Name, B_FU, U_Name) VALUES (?, ?, ?)";
          $stmt = $con->prepare($sql);
          $stmt->bind_param("sss", $B_Name, $B_FU, $U_Name);
          if ($stmt->execute()) {
              echo "Inserted: B_Name=$B_Name, B_FU=$B_FU, U_Name=$U_Name<br>";
          } else {
              echo "Error inserting data: " . $con->error;
          }
      }
  }
}
    }
 
  ?>

  <br><br><br>
  <table class="table table-bordered col-md-12">
      <h1 class="text-center text-white bg-success col-md-12">BUILDING INFORMATION</h1>
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Building Name</th>
            <th scope="col">Unit Number</th>
            <th scope="col">Own By</th>
        </tr>
        </thead>

        <?php
          $query = "SELECT * FROM building ORDER BY B_ID ASC";
          $result = mysqli_query($con,$query);
          while ($row=mysqli_fetch_array($result)){
        ?>

        <tbody>
          <tr>
            <th scope="row"><?php echo $row['B_ID']?></th>
            <td><?php echo $row['B_Name']?></td>
            <td><?php echo $row['B_FU']?></td>
          </tr>
        </tbody>
        <?php } ?>
  </table>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>
