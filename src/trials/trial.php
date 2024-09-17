<?php 
    // $userNameError = false;
    // // check if post
    // if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     // validate input
    //     // start $emailError = false, check preg match, $emailError = true
    //     $emailError = false;

    //     $email_submitted = $_POST['email'];
    //     $filtered_email = filter_var($email_submitted, FILTER_SANITIZE_EMAIL);

    //     $user_name = $_POST['user-name'];
    //     $password = $_POST['password'];
    //     $hash_password = md5($password);
        
        
    //     $pattern_for_username = '^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$^';
        
    //     if (!preg_match($pattern_for_username, $user_name)){
    //         $userNameError = true;
    //     }
    //     // if (!error) {
    //     //     // save to database
    //     //     // redirect, die
    //     // }

    // }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/submit" class="sign-up-form" id="sign-up-form" >
            <h2>Register</h2>
            <div class="form-div">
                <p class="message-error" id="email-error"></p>
                <label>Email:</label>
                <input type="email" name="email" id="email" placeholder="Insert Email..."/><br>
            </div>
            <div class="form-div">
                <p class="message-error" id="username-error"></p>
                <label class="<?php echo $userNameError ? 'error-class' : '' ?>">Username:</label>
                <input type="text" name="user-name" id="user-name" placeholder="Create Username..."/><br>
            </div>
            <div class="form-div">
                <label>Password:</label>
                <input type="text" name="password" placeholder="Create Password..."/><br>
            </div>
            <button type="submit">Submit</button>
        </form>
        <script>
            function validateForm() {
                var email = document.forms["sign-up-form"]["email"].value;
                var userName = document.forms["sign-up-form"]["user-name"].value;
                var emailPattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                var userNamePattern = /^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/;
                var emailErrorSpan = document.forms["sign-up-form"]["email-error"];
                var userNameErrorSpan = document.forms["sign-up-form"]["username-error"];
                
                if (!emailPattern.test(email)) {
                    // alert("Please enter a valid email.");
                    document.getElementById("email-error").innerHTML = "Invalid Email";
                    return false;  
                }
                if (!userNamePattern.test(userName)) {
                    // alert("Please enter a valid username.");
                    document.getElementById("username-error").innerHTML = "Invalid Username";
                    return false;
                }

                return true;
            }

            document.forms["sign-up-form"].addEventListener('submit', function(e) {
                e.preventDefault();
                var isValid = validateForm();
                if (isValid) {
                    document.forms["sign-up-form"].submit;
                }
            });
        </script>
    </body>
</html>

