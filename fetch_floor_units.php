<?php
include('connectDB.php');

if (isset($_GET['building'])) {
    $selectedBuilding = mysqli_real_escape_string($con, $_GET['building']);
    
    $query = "SELECT DISTINCT B_FU FROM building WHERE U_Name IS NULL AND B_Name = '$selectedBuilding'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['B_FU'] . '">' . $row['B_FU'] . '</option>';
        }
    }
}

$con->close();
?>
