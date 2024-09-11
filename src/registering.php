<?php

ob_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('./src/connection.php');

        $email = $_POST['email'];
        $user_name = $_POST['user-name'];
        $password = $_POST['password'];
        $hash_password = md5($password);

        // $userQuery = $db->prepare('INSERT INTO trial_users (userName, password) VALUES (:user_name, :hashPassword);');
        
        $userQuery = $db->prepare('INSERT INTO email_users (email, username, password) VALUES (:email, :user_name, :hash_password)');

        // $userQuery->execute(['user_name' => $user_name, 'hashPassword' => $hashPassword]);
        $userQuery->execute(['email' => $email, 'user_name' => $user_name, 'hash_password' => $hash_password]);

        // echo 'Registering... username: ' . $user_name . ', password: ' . $password;

    }
ob_end_clean();

?>
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
        <a href="http://email.api:8080/home" target="_blank">Go back home to log in....</a>
    </body>
</html>
