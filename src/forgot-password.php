<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Forgotten Password</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/email-change-password" class="sign-up-form">
            <h2>Forgot Password</h2>
            <div class="form-div">
                <label>Enter Email:</label>
                <input type="email" name="email" placeholder="Enter your Email here..."/><br>
            </div>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>