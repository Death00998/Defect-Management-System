<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


  </head>



  <body>
  <header class="p-3 text-bg-dark">
    <div class="container ">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-end">
          <button type="button" class="btn btn-outline-light me-2" onclick="location.href='Login.php'">Login</button>
          <button type="button" class="btn btn-warning" onclick="location.href='SignUp.php'">Sign-up</button>
        </div>
    </div>
  </header>

  <section class="vh-100">
  <div class="container-fluid">
    <div class="row">

    <div class="col-sm-6 px-0 d-none d-sm-block">
        <img src="background1.jpg"
          alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
      </div>
      <div class="col-sm-6 text-black">

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5" >

          <form style="width: 23rem;" name="registerform" action="" method="post" >

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>
  
            <div class="form-outline mb-4">
              <input type="text" id="userame" name="username" class="form-control form-control-lg" placeholder="Username" required="required"/>
            </div>

            <div class="form-outline mb-4">
              <input type="text" id="contact" name="contact" class="form-control form-control-lg" placeholder="Contact" required="required"/>
            </div>

            <div class="form-outline mb-4">
              <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" required="required"/>
            </div>

            <div class="form-outline mb-4">
              <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required="required"/>
            </div>

            <div class="form-outline mb-4">
              <input type="password" id="cpassword" name="cpassword" class="form-control form-control-lg" placeholder="Confirm Password" required/>
            </div>

            <div class="form-outline mb-4">
                    <select id="U_Types" name="types" id="types" class="form-control form-control-lg">
                      <option value="Owner">Owner</option>
                      <option value="Tenant">Tenant</option>
                    </select>
                  </div>

                  <input type="hidden" id="confirmation" name="confirmation" value="Pending">

            <div class="pt-1 mb-4">
              <button type="submit" class="btn btn-info btn-lg btn-block"  value="Register">Register</button>
            </div>

          </form>

        </div>

      </div>
      
    </div>
  </div>
  
</section>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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

                if($stmt = $con->prepare('INSERT INTO user (U_Name,U_Contact,U_Email,U_Password,U_Types,Confirmation) VALUES (?,?,?,?,?,?)')){
                    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                    $stmt->bind_param('ssssss',$_POST['username'],$_POST['contact'],$_POST['email'],$_POST['password'],$_POST['types'],$_POST['confirmation']);
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

