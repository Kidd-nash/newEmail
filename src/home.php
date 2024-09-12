<?php
session_start();

$isLoggedIn = false;
if (isset($_SESSION['userId'])) {
 $isLoggedIn = true;
 // include_once('redirect.php');
} 
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
                <a href="http://email.api:8080/register" target="_self">Register</a>
                <a href="http://email.api:8080/login" target="_self">Log In</a>
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
                <form class="post-content" method="POST" action="/postings">
                    <label>Enter Post...</label>
                    <br>
                    <textarea name="content" rows="4" cols="50"></textarea>
                    <br>
                    <button type="submit">POST!</button>
                </form>
            <?php endif; ?>    
        </div>
        <div class="footer">
            <p>footer</p>
        </div>
    </body>
</html>