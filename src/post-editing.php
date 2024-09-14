<?php

include_once('./src/connection.php');

echo 'editing page';

$editingId = $_GET["editingId"] ?? null;

echo "Editing Post id:" . $editingId;

$postQuery = $db->prepare('SELECT * FROM post_a_note WHERE id = :editId');

$postQuery->execute(['editId' => $editingId]);

$post = $postQuery->fetch(PDO::FETCH_ASSOC);

$editing_post = $post["content"];

?>

<html lang="en">
    <head></head>
    <body>
        <?php if (isset($_SESSION['userName'])): ?>
            <p>Hi, <?php echo $_SESSION['userName'] ?>
        <?php endif; ?>
        <form method="POST" action="/post-updating">
            <label>Brand:</label>
            <textarea type="" name="content" rows="5" cols="50" ><?php echo $editing_post ?></textarea>
      
            <input type="hidden" name="id" value="<?php echo $editingId ?>" />
            
            <input type="submit" value="submit" />
        </form>
    </body>
</html>