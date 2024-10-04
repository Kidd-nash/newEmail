<div class="each-user-post">
    <div>
        <p>Author ID: <?php echo $a_post['author_id'] ?></p>
        <p><?php echo $a_post['date_posted'] ?></p>
        <p><?php echo $a_post['content'] ?></p>
    </div>
    <p><?php echo count($a_post['comments']) ?> comments</p>
    <div class="posts-buttons">
        <a href='http://email.api:8080/class-post-editing?editingId=<?php echo $a_post["id"] ?>'><img src="edit.svg" /></a>
        <button onclick="commentFunction('<?php echo $formId ?>')">Comment</button>
        <a href='http://email.api:8080/class-post-delete?deleteId=<?php echo $a_post["id"] ?>'><img src="delete.svg" /></a>
    </div>
    <div class="comment-form-wrapper" id="<?php echo $formId ?>">
        <?php foreach ($a_post['comments'] as $comment): ?>
            <div class="each-comment">
                <p><?php echo $comment['content'] ?> | by: <?php echo $comment['author_id'] ?></p>
                <p>on: <?php echo $comment['date_posted'] ?></p>
            </div>
        <?php endforeach; ?>
        <form class="comment-form" method="POST" action="/comment-post">
            <label>Comment:</label>
            <textarea class="comment-area" name="comment" rows="1" cols="100"></textarea>
            <button type="submit">Comment</button>
            <input type="hidden" name="post-id" value="<?php echo $a_post["id"] ?>" />
        </form>
    </div>
</div>