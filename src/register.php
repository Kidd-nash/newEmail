<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/registering" class="sign-up-form">
            <h2>Register</h2>
            <div class="form-div">
                <label>Email:</label>
                <input type="email" name="email" placeholder="Insert Email..."/><br>
            </div>
            <div class="form-div">
                <label>Username:</label>
                <input type="text" name="user-name" placeholder="Create Username..."/><br>
            </div>
            <div class="form-div">
                <label>Password:</label>
                <input type="text" name="password" placeholder="Create Password..."/><br>
            </div>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>