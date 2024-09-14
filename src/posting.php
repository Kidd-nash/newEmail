<?php
session_start();

ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once('./src/connection.php');

    session_start();

    $isLoggedIn = false;
    if (isset($_SESSION['userId'])) {
        $isLoggedIn = true;
    } else {
        $_SESSION['accessDeniedError'];
        ob_end_clean();
        header("Location: http://email.api:8080/home");
        die();
    }

    $id_plus = $_SESSION['userId'];

    $content = $_POST['content'];
    $author_id = $id_plus;

    $userQuery = $db->prepare('INSERT INTO post_a_note (content, date_posted, author_id) VALUES (:content, :date_posted, :author_id)');
    $userQuery->execute(['content' => $content, 'date_posted' => date('Y-m-d'), 'author_id' => $author_id]);

    $_SESSION['saved'] = true;
}
ob_end_clean();

header("Location: http://email.api:8080/home");
die();
