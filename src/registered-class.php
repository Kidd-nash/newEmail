<!DOCTYPE html>
<html>
    <head>
        <style>
            a:link, a:visited {
              background-color: rgb(115, 194, 251);
              color: white;
              padding: 14px 25px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              border-radius: 5;
            }
            
            a:hover, a:active {
              background-color: rgb(0, 128, 255);
            }
        </style>
    </head>
    <body>
        <p>
            <?php
                echo "Your username is: " . $user_name;
            ?>
        </p>
        <p>
            <?php
                echo "Your password is: " . $password;
            ?>
        </p>
        <a href="http://email.api:8080/new-home" target="_blank">Go back home to log in....</a>
    </body>
</html>