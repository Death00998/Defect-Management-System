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

          <form style="width: 23rem;" name="registerform" action="registerdb.php" method="POST" onsubmit="return validateForm()" >

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>
            <?php
            if(!empty($error_message)){
            ?>
            <div class="alert alert-danger">
              <?=$error_message ?>
            </div>
            <?php
            }
            ?>
            <?php
            if(!empty($success_message)){
              ?>
            <div class= "alert alert-success">
              <strong>
                Success!!!
              </strong>
            </div>

            <?php
            }
            ?>

            <div class="form-outline mb-4">
              <input type="text" id="U_Name" name="U_Name" class="form-control form-control-lg" placeholder="Username" required="required"/>
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
              <input type="password" id="Cpassword" name="Cpassword" class="form-control form-control-lg" placeholder="Confirm Password" required/>
            </div>

            <div class="form-outline mb-4">
                    <select id="U_Types" name="types" class="form-control form-control-lg">
                    <option value="null">---</option>
                      <option value="Owner">Owner</option>
                      <option value="Tenant">Tenant</option>
                    </select>
                  </div>

            <div class="pt-1 mb-4">
              <button type="submit" class="btn btn-info btn-lg btn-block"  value="register">Register</button>
            </div>

          </form>

        </div>

      </div>
      
    </div>
  </div>
  
</section>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
        function validateForm() {
          document.registerform.submit();
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("Cpassword").value;
    var email = document.getElementById("email").value;

    if (password !== confirm_password) {
        alert("Passwords do not match.");
        
        return false;
    } 

    // Use AJAX to check if the email already exists
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check_email.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText === "exists") {
                alert("Email already exists. Please use a different email.");
            } else {
                // Email is unique, submit the form
                document.getElementById("registrationform").submit();
            }
        }
    };
    xhr.send("email=" + email);

    return false; // Prevent the form from being submitted here
      }
    </script>

</body>
</html>

