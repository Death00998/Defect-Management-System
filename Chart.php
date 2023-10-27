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
      
</div>
    <footer>Copyright © Final Year Project 2023 by Kok Zhen Heng</footer>
  </body>
</html>
