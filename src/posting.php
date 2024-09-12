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