<?php
    session_start();

    $isLoggedIn = false;
    if (isset($_SESSION['userId'])) {
    $isLoggedIn = true;
    } 
    
    if (isset($_SESSION['userId'])) {
        $id_plus = $_SESSION['userId'] + 782;
    } else {
        $id_plus = 0; 
    }

    $author_id = $id_plus;
    include_once('./src/connection.php');
    $postQuery = $db->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');

    $postQuery->execute([
        'author_id' => $author_id,
    ]);

    // $post = $postQuery->fetch(PDO::FETCH_ASSOC);

    $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body>
        <div class="header">
            <div class="header-logo">
                <p>Logo</p>
                <img />
            </div>
            <div class="header-text">
                <h1>Email Posts</h1>
            </div>
            <div class="header-signup">
                <?php if (!$isLoggedIn): ?>
                <a class="signup" href="http://email.api:8080/register" target="_self">Register</a>
                <a class="signup" href="http://email.api:8080/login" target="_self">Log In</a>
                <?php else: ?>
                    <?= $_SESSION['username'] ?>
                    <p>, hey there.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="content">
            <?php if (!$isLoggedIn): ?>
                <p>You can post stuff in here, but you have to be logged in first</p>
            <?php else: ?>
                <p>Post something?</p>
                <form class="post-content" method="POST" action="/posting">
                    <label>Enter Post...</label>
                    <br>
                    <textarea name="content" rows="4" cols="50"></textarea>
                    <br>
                    <button type="submit">POST!</button>
                </form>
                <div class="posts">
                    <?php 
                        foreach($posts as $a_post) {
                            echo '<div class="each_post">';
                                echo '<p>' . $_SESSION['username'] . '</p>';
                                echo '<p>' . $a_post['content'] . '</p>';
                                echo '<p>' . $a_post['date_posted'] . '</p>';
                                echo "<p><a href='edit.php?editId=" . $a_post["id"] . "'>Edit</a><p>";
                                echo "<p><a href='http://email.api:8080/post-delete" . $a_post["id"] . "'>Delete</a><p>";
                            echo '</div>';
                        }
                    ?>
                </div>
            <?php endif; ?>    
        </div>
        <div class="footer">
            <p>footer</p>
        </div>
    </body>
</html>