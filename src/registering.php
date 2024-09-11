<?php

ob_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('./src/connection.php');

        $email = $_POST['email'];
        $user_name = $_POST['user-name'];
        $password = $_POST['password'];
        $hash_password = md5($password);

        
        $userQuery = $db->prepare('INSERT INTO email_users (email, username, password) VALUES (:email, :user_name, :hash_password)');

        $userQuery->execute(['email' => $email, 'user_name' => $user_name, 'hash_password' => $hash_password]);


    }
ob_end_clean();

include_once('./src/email.php');

$emailTo = $email;
$emailFrom = 'register@email.com';
$subject = 'Registration';
$content = '
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
     <h1>Registration to Email Posts</h1>

     <b>HI, ' . $user_name . ' you have registered</b>
     <br>
     <p>Please click the button to verify your account</p>
     <br>
     <a href="http://email.api:8080/verify" target="_self">Verify</a>
     <br>
     <p>Thank you,</p>
     <p>Email Posts Team</p>
  </body>
</html>
';

sendEmail($emailTo, $emailFrom, $subject, $content);


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
