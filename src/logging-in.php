<?php
session_start();
ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once('./src/connection.php');

    $user_name_or_email = $_POST["userNameOrEmail"];
    $password = $_POST["password"];
    $hash_password = md5($password);


    $userQuery = $db->prepare('SELECT * FROM email_users WHERE (username = :user_name_or_email OR email = :user_name_or_email) AND password = :hash_password');

    $userQuery->execute([
        'user_name_or_email' => $user_name_or_email,
        'hash_password' => $hash_password
    ]);

    $user = $userQuery->fetch(PDO::FETCH_ASSOC);

    $userId = $user['id'] ?? null;

    if (!is_null($userId)) {
        $_SESSION['userId'] = $userId;
        $_SESSION['username'] = $user['username'];
        ob_end_clean();
        header("Location: http://email.api:8080/home");
        die();
    }
}
die('test');
