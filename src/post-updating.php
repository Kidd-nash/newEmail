<?php

session_start();
ob_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once('./src/connection.php');

    $id = $_POST["id"] ?? null;
    $content = $_POST["content"] . '- edited' ?? null;
    
    $userQuery = $db->prepare("UPDATE post_a_note SET content = :content WHERE id = :id");
    
    $userQuery->execute(['content' => $content, 'id' => $id]);

    $_SESSION['saved'] = true;

}
ob_end_clean();
header("Location: http://email.api:8080/home");
die();