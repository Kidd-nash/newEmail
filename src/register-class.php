<!DOCTYPE html>
<html lang="en">
    <head>
        <title>New Register</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body class="sign-up-body">
        <form method="POST" action="/new-registering" class="sign-up-form" id="sign-up-form">
            <h2>Register</h2>
            <div class="form-div">
                <p class="message-error" id="email-error"></p>
                <label>Email:</label>
                <input type="email" name="email" id="email" placeholder="Insert Email..."/><br>
            </div>
            <div class="form-div">
                <p class="message-error" id="username-error"></p>
                <label>Username:</label>
                <input type="text" name="user-name" id="user-name" placeholder="Create Username..."/><br>
            </div>
            <div class="form-div">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Create Password..."/><br>
            </div>
            <label for="agree">
                <input type="checkbox" id="agree"> Conditions and Terms
            </label>
            <button type="submit">Submit</button>
        </form>
        <script>
            function validateForm() {
                var email = document.forms["sign-up-form"]["email"].value;
                var userName = document.forms["sign-up-form"]["user-name"].value;
                var emailPattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                var userNamePattern = /^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/;

                var emailErrorSpan = document.getElementById("email-error");
                var userNameErrorSpan = document.getElementById("username-error");

                let isValid = true;
                
                if (!emailPattern.test(email)) {
                    document.getElementById("email-error").innerHTML = "Invalid Email";
                    return false;  
                }
                if (!userNamePattern.test(userName)) {
                    document.getElementById("username-error").innerHTML = "Invalid Username";
                    return false;
                }

                return true;
            }

            document.getElementById("sign-up-form").addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    document.getElementById("email-error").innerHTML = "";
                    document.getElementById("username-error").innerHTML = "";
                    e.target.submit();
                }
            });

            document.getElementById("sign-up-form").addEventListener("submit", function(event){
                var checkbox = document.getElementById("agree");
                if (!checkbox.checked) {
                    event.preventDefault(); 
                    alert("Please read conditions and terms.");
                }
            });
            document.getElementById('email').focus();
        </script>
    </body>
</html>