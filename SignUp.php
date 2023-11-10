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
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-end">
          <button type="button" class="btn btn-outline-light me-2" onclick="location.href='Login.php'">Login</button>
          <button type="button" class="btn btn-warning" onclick="location.href='SignUp.php'">Sign-up</button>
        </div>
      </div>
    </header>

    <?php
    include('connectDB.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $errors = array();

    if (isset($_POST['id'], $_POST['username'], $_POST['password'], $_POST['email'], $_POST['contact'], $_POST['types'])) {
        // Other input data
        $id = $_POST['id'];
        $BuildName = $_POST['BuildingName'];
        $BuildFU = $_POST['BuildingFloorUnit'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $types = $_POST['types'];
        $confirmation = $_POST['confirmation'];

        // Check for empty fields
        if (empty($id) || empty($username) || empty($password) || empty($email) || empty($contact) || empty($types) || empty($BuildName) || empty($BuildFU)) {
            $errors[] = "Please fill in all fields.";
        }

        // Validate IC number in Malaysia format (12 digits)
        if (!preg_match('/^\d{12}$/', $id)) {
            $errors[] = "Invalid IC number. It should be 12 digits.";
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        // Password Length Validation
        if (strlen($password) < 6) {
            $errors[] = "Password is too short. It should be at least 6 characters.";
        }

        // Password Confirmation
        if ($password !== $cpassword) {
            $errors[] = "Password and confirm password do not match.";
        }

        if (empty($errors)) {
          // Check if a user with the same IC number, email, or contact already exists
          $checkQuery = "SELECT U_ID, U_Email, U_Contact FROM user WHERE U_ID = ? OR U_Email = ? OR U_Contact = ?";
          $checkStmt = $con->prepare($checkQuery);
          $checkStmt->bind_param('sss', $id, $email, $contact);
          $checkStmt->execute();
          $checkStmt->store_result();
      
          if ($checkStmt->num_rows > 0) {
              echo '<script>alert("A user with the same IC number, email, or contact already exists.");</script>';
          } else {
              // No duplicate found, proceed with the insertion
              $password = password_hash($password, PASSWORD_DEFAULT);
              $stmt = $con->prepare('INSERT INTO user (B_Name, B_UF, U_ID, U_Name, U_Contact, U_Email, U_Password, U_Types, Confirmation) VALUES (?,?,?, ?, ?, ?, ?, ?, ?)');
              $stmt->bind_param('sssssssss',$BuildName,$BuildFU, $id, $username, $contact, $email, $password, $types, $confirmation);
      
              if ($stmt->execute()) {
                  echo '<script>alert("Successfully Registered"); window.location = "Login.php";</script>';
                  exit;
              } else {
                  echo '<script>alert("Failed to register. Please try again.");</script>';
              }
              $stmt->close();
          }
      } else {
          // Debugging: Output errors
          var_dump($errors);
      }
      
    } else {
        // Debugging: Output a message if the form is not submitted
        echo '<div class="alert alert-info">Form not submitted</div>';
    }

    $con->close();
    ?>

    <section class="vh-100">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6 px-0 d-none d-sm-block">
            <img src="background1.jpg" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
          </div>
          <div class="col-sm-6 text-black">
            <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
              <form style="width: 23rem;" name="registerform" action="SignUp.php" method="post">
                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>
                <div class="form-outline mb-4">
                  
                <div class="form-group">
                  <div class="row">
                      <div class="col-md-6">
                          <label for="BuildingName">Select Building</label>
                          <select class="form-control required" id="BuildingName" name="BuildingName">
                            <option value="" disabled="disabled" selected="selected">Select Building</option>
                            <?php 
                            include('connectDB.php');
                            $query = 'SELECT DISTINCT B_Name FROM building'; // Use DISTINCT to select unique values
                            $result = $con->query($query);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['B_Name'] . '">' . $row['B_Name'] . '</option>';
                                }
                            }
                            ?>
                        </select>

                      </div>
                      <div class="col-md-6">
    <label for="BuildingFloorUnit">Select Floor Unit</label>
    <select class="form-control required" id="BuildingFloorUnit" name="BuildingFloorUnit" placeholder="Select Floor Unit">
        <option value="" disabled="disabled" selected="selected">Select Floor Unit</option>
        <?php 
        include('connectDB.php');
        $query = 'SELECT DISTINCT B_FU FROM building WHERE U_Name IS NULL'; // Add a condition to filter rows based on your needs
        $result = $con->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['B_FU'] . '">' . $row['B_FU'] . '</option>';
            }
        }
        ?>
    </select>
</div>

                  </div>
                </div>

                </div>
                <div class="form-outline mb-4">
                  <input type="text" id="id" name="id" class="form-control form-control-lg" placeholder="Insert IC Number" required="required" pattern="\d{12}" title="Invalid IC number. It should be 12 digits." />
                </div>
                <div class="form-outline mb-4">
                  <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Username" required="required" />
                </div>
                <div class="form-outline mb-4">
                  <input type="text" id="contact" name="contact" class="form-control form-control-lg" placeholder="Contact" required="required" />
                </div>
                <div class="form-outline mb-4">
                  <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" required="required" />
                </div>
                <div class="form-outline mb-4">
                  <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required="required" />
                </div>
                <div class="form-outline mb-4">
                  <input type="password" id="cpassword" name="cpassword" class="form-control form-control-lg" placeholder="Confirm Password" required="required" />
                </div>
                <div class="form-outline mb-4">
                  <select id="U_Types" name="types" class="form-control form-control-lg" required="required">
                    <option value="Owner">Owner</option>
                    <option value="Tenant">Tenant</option>
                  </select>
                </div>
                <input type="hidden" id="confirmation" name="confirmation" value="Pending">
                <div class="pt-1 mb-4">
                  <button type="submit" class="btn btn-info btn-lg btn-block" onclick="return validateForm();">Register</button>
                </div>
                <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                <script>
                  
                  function validateForm() {
                    const id = document.getElementById("id").value;
                    const username = document.getElementById("username").value;
                    const email = document.getElementById("email").value;
                    const password = document.getElementById("password").value;
                    const cpassword = document.getElementById("cpassword").value;
                    const errorMessage = document.getElementById("error-message");
                    errorMessage.style.display = "none"; // Hide any previous error message
                    if (id === "" || username === "" || email === "" || password === "" || cpassword === "") {
                      errorMessage.innerText = "Please fill in all required fields.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    if (!/^\d{12}$/.test(id)) {
                      errorMessage.innerText = "Invalid IC number. It should be 12 digits.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    if (!/^\w+@\w+\.\w+$/.test(email)) {
                      errorMessage.innerText = "Invalid email address.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    if (password.length < 6) {
                      errorMessage.innerText = "Password is too short. It should be at least 6 characters.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    if (password !== cpassword) {
                      errorMessage.innerText = "Password and confirm password do not match.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    select = document.getElementById('BuildingName'); // or in jQuery use: select = this;
                    if (!select.value) {
                      errorMessage.innerText = "Building Not Selected.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                  select = document.getElementById('BuildingFloorUnit'); // or in jQuery use: select = this;
                    if (!select.value) {
                      errorMessage.innerText = "Building Floor Not Selected.";
                      errorMessage.style.display = "block";
                      return false; // Prevent form submission
                    }
                    return true; // Proceed with form submission
                  }
                </script>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
