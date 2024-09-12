<?php

ob_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('./src/connection.php');
        session_start();
        $isLoggedIn = false;
        if (isset($_SESSION['userId'])) {
        $isLoggedIn = true;
        } 

        $id_plus = $_SESSION['userId'] + 782;

        // $hashed_id= hexdec(sha1($id_plus));

        $content = $_POST['content'];
        $author_id = $id_plush;
        // $date = $_POST['password'];

        // echo 'author_id is:' . $author_id ;
        

        $userQuery = $db->prepare('INSERT INTO post_a_note (content, date_posted, author_id) VALUES (:content, :date_posted, :author_id)');

        $userQuery->execute(['content' => $content, 'date_posted' => date('Y-m-d'), 'author_id' => $author_id]);


    }
ob_end_clean();
