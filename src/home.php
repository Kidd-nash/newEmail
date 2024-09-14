<?php
session_start();


$isLoggedIn = false;
if (isset($_SESSION['userId'])) {
    $isLoggedIn = true;
    $id_plus = $_SESSION['userId'];
    if (isset($_SESSION['saved'])) {
        unset($_SESSION['saved']);
        $isSaved = true;
    }
} else {
    $id_plus = 0;
}

$author_id = $id_plus;
include_once('./src/connection.php');
$postQuery = $db->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');

$postQuery->execute([
    'author_id' => $author_id,
]);

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
                <a class="signup" href="/register" target="_self">Register</a>
                <a class="signup" href="/login" target="_self">Log In</a>
            <?php else: ?>
                <?= $_SESSION['username'] ?>
                <p>, hey there.</p>
                <a href="/log-out">Log out</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="content">
        <?php if (isset($_SESSION['accessDeniedError'])): ?>
            <p>You can only post if you are logged in. <a href="/login">Log in here</a> </p>
            <?php unset($_SESSION['accessDeniedError']) ?>
        <?php endif; ?>

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
            <?php if (isset($isSaved)): ?>
                <p>Saved succesfully</p>
            <?php endif; ?>
            <div class="posts">
                <?php
                foreach ($posts as $a_post) {
                    echo '<div class="each_post">';
                    echo '<p>' . $_SESSION['username'] . '</p>';
                    echo '<p>' . $a_post['content'] . '</p>';
                    echo '<p>' . $a_post['date_posted'] . '</p>';
                    echo "<p><a href='http://email.api:8080/post-editing?editingId=" . $a_post["id"] . "'>Edit</a><p>";
                    echo "<p><a href='http://email.api:8080/post-delete?deleteId=" . $a_post["id"] . "'>Delete</a><p>";
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