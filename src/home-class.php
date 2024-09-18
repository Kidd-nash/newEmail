<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="src/style.css">
    <script type="text/javascript" src="src/dark-mode.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header body-div" id="header-div">
        <div class="header-logo" id="header-logo">
            <p class="p-logo">Logo</p>
            <img />
        </div>
        <div class="header-text" id="header-text">
            <h1>Email Posts</h1>
        </div>
        <div class="header-signup" id="header-signup">
            <?php if (!$isLoggedIn): ?>
                <a class="signup" href="/new-register" target="_self">Register</a>
                <a class="signup" href="/new-login" target="_self">Log In</a>
            <?php else: ?>
                <?= $_SESSION['username'] ?>
                <p>, hey there.</p>
                <a class="signout" href="/log-out">Log out</a>
                <button onclick="darkMode()">
                    <img src="bulb.svg" />
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="content body-div" id="content-div">
        <div class="content-posts">
            <?php if (isset($_SESSION['accessDeniedError'])): ?>
                <p>You can only post if you are logged in. <a href="/login">Log in here</a> </p>
                <?php unset($_SESSION['accessDeniedError']) ?>
            <?php endif; ?>

            <?php if (!$isLoggedIn): ?>
                <p>You can post stuff in here, but you have to be logged in first</p>
            <?php else: ?>                
                <form class="post-content" method="POST" action="/posting" id="content-form">
                    <p>Post something?</p>
                    <label>Enter Post...</label>
                    <br>
                    <textarea name="content" rows="4" cols="50"></textarea>
                    <br>
                    <button type="submit">POST!</button>
                </form>
                <?php if (isset($isSaved)): ?>
                    <p>Saved succesfully</p>
                <?php endif; ?>
                <div class="posts" id="content-posts">
                    <?php
                    foreach ($posts as $a_post) {
                        echo '<div class="each-post">';
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
    </div>
    <div class="footer body-div" id="footer-div">
        <p>footer</p>
    </div>
</body>

</html>