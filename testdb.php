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
            echo'<script>alert("email already exist")</script>';
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