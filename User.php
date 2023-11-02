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
          <li><a href="Building.php" class="nav-link px-2 text-white">Building</a></li>
          <li><a href="User.php" class="nav-link px-2 text-secondary">User</a></li>
          <li><a href="Chart.php" class="nav-link px-2 text-white">Chart</a></li>
        </ul>

        
          <button type="button" class="btn btn-warning" onclick="location.href='Logout.php'">Logout</button>
        </div>
      </div>
    </div>
  </header>
          
    <table class="table table-bordered col-md-12">
    <h1 class="text-center bg-secondary col-md-12">PENDING LIST</h1>
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email Address</th>
            <th scope="col">Contact Number</th>
            <th scope="col">User Type</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
        </thead>

        <?php
        include("connectDB.php");
          $query = "SELECT * FROM user WHERE Confirmation = 'Pending' ORDER BY U_ID ASC";
          $result = mysqli_query($con,$query);
          while ($row=mysqli_fetch_array($result)){
        ?>

        <tbody>
          <tr>
            <th scope="row"><?php echo $row['U_ID']?></th>
            <td><?php echo $row['U_Name']?></td>
            <td><?php echo $row['U_Email']?></td>
            <td><?php echo $row['U_Contact']?></td>
            <td><?php echo $row['U_Types']?></td>
            <td><?php echo $row['Confirmation']?>

            <td>
              <form action="User.php" method="post">
                <input type="hidden" name= "id" value="<?php echo $row['U_ID']?>"/>
                <input type="submit" name="approve" class="text-white bg-success bg-gradient rounded-pill border-success w-25" value="Approve">
                <input type="submit" name="delete" class="text-white bg-danger bg-gradient rounded-pill border-danger w-25" value="delete">
              </form>
            </td>
          </tr>
        </tbody>
        <?php } ?>
        
        <?php
          if(isset($_POST['approve'])){
            $id=$_POST['id'];
            $select = "UPDATE user SET Confirmation = 'approve' WHERE U_ID = '$id' ";
            $result = mysqli_query($con, $select);
            header('Location: User.php');
          }

          if(isset($_POST['delete'])){
            $id=$_POST['id'];
            $select = "DELETE FROM user WHERE U_ID ='$id' ";
            $result = mysqli_query($con, $select);
            header('Location: User.php');
          }
        ?>      

      <table class="table table-bordered col-md-12">
      <h1 class="text-center text-white bg-success col-md-12">APPROVED LIST</h1>
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email Address</th>
            <th scope="col">Contact Number</th>
            <th scope="col">User Type</th>
            <th scope="col">Status</th>
        </tr>
        </thead>

        <?php
          $query = "SELECT * FROM user WHERE Confirmation = 'Approve' ORDER BY U_ID ASC";
          $result = mysqli_query($con,$query);
          while ($row=mysqli_fetch_array($result)){
        ?>

        <tbody>
          <tr>
            <th scope="row"><?php echo $row['U_ID']?></th>
            <td><?php echo $row['U_Name']?></td>
            <td><?php echo $row['U_Email']?></td>
            <td><?php echo $row['U_Contact']?></td>
            <td><?php echo $row['U_Types']?></td>
            <td><?php echo $row['Confirmation']?>
          </tr>
        </tbody>
        <?php } ?>

    </table>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  </body>

</html>
