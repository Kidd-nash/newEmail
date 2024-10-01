<!DOCTYPE html>
<html lang="en">
    <head>
        <title>New Log In</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/new-loggingin" class="sign-up-form">
            <h2>Log In</h2>
            <div class="form-div">
                <label>Email or Username:</label>
                <input id="user_email" type="text" name="userNameOrEmail" placeholder="Insert Email or Username..."/><br>
            </div>
            <div class="form-div">
                <label>Password:</label>
                <input type="text" name="password" placeholder="Insert Password..."/><br>
            </div>
            <a class="forgot-password" href="/forgot-password">Forgot Password?</a>
            <button type="submit">Submit</button>
        </form>
        <script>
            document.getElementById('user_email').focus();
        </script>
    </body>
</html>