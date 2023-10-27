<!DOCTYPE html>
    <head>

    </head>

    <body>
        <div class="register">
            <h1>Register</h1>
            <form action="testdb.php" method="post">
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

        
    </body>
</html>