<?php
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
    </head>
    <body>
        <form method="POST" action="/registering">
            <h2>Register</h2>
            <label>Email:</label>
            <input type="email" name="email" placeholder="Insert Email..."/><br>
            <label>Username:</label>
            <input type="text" name="user-name" placeholder="Create Username..."/><br>
            <label>Password:</label>
            <input type="text" name="password" placeholder="Create Password..."/><br>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>