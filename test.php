<!DOCTYPE html>
    <head>

    </head>

    <body>
        <div class="register">
            <h1>Register</h1>
            <form action="" method="post">
                <label for="username"></label>
                <input type="text" name="username" placeholder="username" id="username" required><br>
                <label for="password"></label>
                <input type="password" name="password" placeholder="Password" id="password" required><br>
                <label for="password"></label>
                <input type="password" name="cpassword" placeholder="Confirm Password" id="cpassword" required><br>
                <label for="email"></label>
                <input type="email" name="email" placeholder="Email" id="email"required><br>
                <label for="contact"></label>
                <input type="contact" name="contact" placeholder="contact" id="contact"required><br>
                <label for="Types"></label>
                <select id="U_Types" name="types" id="types">
                      <option value="Owner">Owner</option>
                      <option value="Tenant">Tenant</option><br>
                
                <input type="submit" value="Register">
            </form>
        </div>

        <?php
    include('connectDB.php');

    if(!isset($_POST['username'],$_POST['password'],$_POST['email'],$_POST['contact'],$_POST['types'])){
        exit('Empty Field(s)');
    }
    if(empty($_POST['username'] || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['contact']) || empty($_POST['types']))){
        exit('Values Empty');
    }
    if($stmt = $con->prepare('SELECT U_Email FROM user WHERE U_Email = ?')){
        $stmt->bind_param('s',$_POST['email']);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows>0){
            echo'<script>alert("Email already exist")</script>';
        }else{
            $password = $_POST['password'];
            if(strlen($password)<6){
                echo '<script>alert("Password too short")</script>';

            }else{
                if($_POST['password'] !== $_POST['cpassword']){
                    echo '<script>alert("Password Not Match")</script>';
                }else{

                if($stmt = $con->prepare('INSERT INTO user (U_Name,U_Contact,U_Email,U_Password,U_Types) VALUES (?,?,?,?,?)')){
                    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                    $stmt->bind_param('sssss',$_POST['username'],$_POST['contact'],$_POST['email'],$_POST['password'],$_POST['types']);
                    $stmt->execute();
                    echo '<script>alert("Successfully Register")</script>';
                }else{
                    echo '<script>alert("Error Occured")</script>';
                }
            }
            }
    }
        $stmt->close();
    }
    else{
        echo '<script>alert("Error Occured")</script>';
    }
    $con->close();

?>
    </body>
</html>