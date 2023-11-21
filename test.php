<!DOCTYPE html>
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

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="background1.jpg" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
                <div class="col-sm-6 text-black">
                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
                        <form style="width: 23rem;" name="registerform" action="SignUp.php" method="post" autocomplete="off">
                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>

                            <!-- Building Selection -->
                            <div class="form-group">
                                <label for="BuildingName">Select Building</label>
                                <select class="form-control required" id="BuildingName" name="BuildingName" onchange="updateFloorUnits()">
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
                            <div class="form-group">
                                <label for="BuildingFloorUnit">Select Floor Unit (Floor - Unit)</label>
                                <select class="form-control required" id="BuildingFloorUnit" name="BuildingFloorUnit" placeholder="Select Floor Unit">
                                    <option value="" disabled="disabled" selected="selected">Select Floor Unit</option>
                                    <!-- Options will be populated dynamically using JavaScript -->
                                </select>
                            </div>
                            <!-- End Building Selection -->

                            <div class="form-outline mb-4">
                                <input type="text" id="id" name="id" class="form-control form-control-lg" placeholder="Insert IC Number" required="required" pattern="\d{12}" title="Invalid IC number. It should be 12 digits." />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Full Name" required="required" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" id="contact" name="contact" class="form-control form-control-lg" placeholder="Contact Number (exp: 0123456789)" required="required" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email (exp: xx@gmail.com)" required="required" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" id="password" name="password" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" class="form-control form-control-lg" placeholder="Password" required="required" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" id="cpassword" name="cpassword" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" class="form-control form-control-lg" placeholder="Confirm Password" required="required" />
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
                                function updateFloorUnits() {
                                   var selectedBuilding = document.getElementById("BuildingName").value;

                                        if (selectedBuilding !== "") {
                                        // If a building is selected, fetch corresponding floor units
                                        var xhr = new XMLHttpRequest();
                                        xhr.onreadystatechange = function() {
                                             if (xhr.readyState === 4 && xhr.status === 200) {
                                                  // Update the options in BuildingFloorUnit dropdown
                                                  document.getElementById("BuildingFloorUnit").innerHTML = xhr.responseText;
                                             }
                                        };

                                        // Make a request to fetch floor units based on the selected building
                                        xhr.open("GET", "fetch_floor_units.php?building=" + selectedBuilding, true);
                                        xhr.send();
                                        } else {
                                        // If no building is selected, reset the BuildingFloorUnit dropdown
                                        document.getElementById("BuildingFloorUnit").innerHTML = '<option value="" disabled="disabled" selected="selected">Select Floor Unit</option>';
                                        }

                        }

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
