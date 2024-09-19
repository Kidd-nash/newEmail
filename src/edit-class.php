<html lang="en">
    <head>
        <title>Edit Page</title>
        <link rel="stylesheet" href="src/style.css">
    </head>
    <body>
        <?php if (isset($_SESSION['userName'])): ?>
            <p>Hi, <?php echo $_SESSION['userName'] ?>
        <?php endif; ?>
        <form method="POST" action="/class-post-updating">
            <label>Brand:</label>
            <textarea type="" name="content" rows="5" cols="50" ><?php echo $editing_post ?></textarea>
      
            <input type="hidden" name="id" value="<?php echo $editingId ?>" />
            
            <input type="submit" value="submit" />
        </form>
    </body>
</html>