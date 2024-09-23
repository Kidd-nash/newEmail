<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Forgotten Password</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/change-password-updating" class="sign-up-form">
            <h2>Change Password</h2> 
            <div class="form-div">
                <label>Enter New Password:</label>
                <input type="password" name="password" placeholder="Enter your new password here..."/><br>
                <label>Re enter New Password</label>
                <input type="password" name="password" placeholder="Re enter password..."/><br>
            </div>
            <button type="submit">Submit</button>
        </form>
        <script>

        </script>
    </body>
</html>