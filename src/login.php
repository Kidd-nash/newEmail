<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log In</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/logging-in" class="sign-up-form">
            <h2>Log In</h2>
            <div class="form-div">
                <label>Email or Username:</label>
                <input type="text" name="userNameOrEmail" placeholder="Insert Email or Username..."/><br>
            </div>
            <div class="form-div">
                <label>Password:</label>
                <input type="text" name="password" placeholder="Insert Password..."/><br>
            </div>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>
