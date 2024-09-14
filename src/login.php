<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log In</title>
    </head>
    <body>
        <form method="POST" action="/logging-in">
            <h2>Log In</h2>
            <label>Email or Username:</label>
            <input type="text" name="userNameOrEmail" placeholder="Insert Email or Username..."/><br>
            <label>Password:</label>
            <input type="text" name="password" placeholder="Insert Password..."/><br>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>
