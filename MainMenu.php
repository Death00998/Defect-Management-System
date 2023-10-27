<!DOCTYPE html>
<html>
  <head>

    <link href="styles.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
    <title>Defect Management System</title>
    <style>

      p{
        text-align: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        margin-top: auto;
        margin-left: auto;
      }

      a:link, a:visited{
        background-color: #DAC0A3;
        color: #102C57;
        padding: 14px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: large;
        font-weight: bold;
      }

      a:hover, a:active{
        background-color: #102C57;
        color: #DAC0A3;
        font-size: larger;
      }
      
      .left{
        
        top: 0;
        left:0;
      }

      .center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    </style>

  </head>
  <body>
    
    <header>Defect Management System</header>
    
      
    <div id="float-nav", class="left">
        <a href="MainMenu.php" >Home</a>
        <a href="Report.php">User Info</a>
        <a href="Chart.php">Chart</a>
        <a href="Login.php">Logout</a>
       </div>


       <article class="center">
      <?php
      include('connectDB.php');

      $sql = "select * from defect";
      $result=mysqli_query($con, $sql);
      
      if($result->num_rows>0){
        echo "<table><tr><th>ID</th><th>Defect Area</th><th><Description</th><th>User Types</th></tr>";

        while ($row = $result ->fetch_assoc()) {
          echo "<tr><td>".$row["ID"]."</td><td>".$row["D_Area"]."</td><td>".$row["D_Description"]."</td><td>".$row["U_Types"]."</td></tr>";
        }
        echo "</table>";
      }else{
        echo "No Data Found!";
      }

      ?>
      </article>

    <footer>Copyright © Final Year Project 2023 by Kok Zhen Heng</footer>
  </body>
</html>
