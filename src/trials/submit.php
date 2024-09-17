<?php

// ob_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('./src/connection.php');

        $email_submitted = $_POST['email'];
        $filtered_email = filter_var($email_submitted, FILTER_SANITIZE_EMAIL);

        $user_name = $_POST['user-name'];
        $password = $_POST['password'];
        $hash_password = md5($password);
        
        echo nl2br("you submitted this email: " . $email_submitted) ;
        
        echo nl2br("\nthis is the filtered email: " . $filtered_email);

        echo '<br>';
        
        
        $pattern_for_username = '^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$^';

        if (preg_match($pattern_for_username, $user_name)){
            echo "username is allowed";
        } else {
            echo "username is not allowed";
        }

    }
// ob_end_clean();