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
    </div>
  </header>
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
            <th scope="col">Due Date</th>
            <th scope="col">Status</th>
        </tr>
        </thead>

        <?php
        include("connectDB.php");
          $query = "SELECT * FROM defect WHERE D_Confirm = 'Pending' ORDER BY U_ID ASC";
          $result = mysqli_query($con,$query);
          while ($row=mysqli_fetch_array($result)){
        ?>

        <tbody>
          <tr>
            <th scope="row"><?php echo $row['B_Name']?></th>
            <td><?php echo $row['B_FU']?></td>
            <td><?php echo $row['U_Name']?></td>
            <td><?php echo $row['D_Area']?></td>
            <td><?php echo $row['D_Description']?></td>
            <td><?php echo $row['D_Date']?></td>
            <td><?php echo $row['DueDate']?></td>
            <td><?php echo $row['D_Confirm']?></td>

            <td>
              <form action="MainMenu.php" method="post">
                <input type="hidden" name= "id" value="<?php echo $row['U_ID']?>"/>
                <input type="submit" name="Done" class="text-white bg-success bg-gradient rounded-pill border-success w-25" value="Done">
                <input type="submit" name="Deny" class="text-white bg-danger bg-gradient rounded-pill border-danger w-25" value="Deny">
              </form>
            </td>
          </tr>
        </tbody>
        <?php } ?>
        
        <?php
          if (isset($_POST['Done'])) {
            $id = $_POST['id'];
            
            
            // Update user's confirmation status
            $updateDefect = "UPDATE defect SET D_Confirm = 'approve' WHERE ID = '$id'";
            $resultDefect = mysqli_query($con, $updateDefect);
        
        }

          if(isset($_POST['Deny'])){
            $id = $_POST['id'];

            $updateDefect = "UPDATE defect SET D_Confirm = 'approve' WHERE ID = '$id'";
            $resultDefect = mysqli_query($con, $updateDefect);
        
        
            header('Location: MainMenu.php');
          }
        ?>      
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  </body>
</html>
