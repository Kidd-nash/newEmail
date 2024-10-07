<div class="each-user-post">
    <div class="contents-div">
        <p class="each-post each-post-contents">Author ID: <?php echo $a_post['author_id'] ?></p>
        <p class="each-post each-post-contents"><?php echo $a_post['date_posted'] ?></p>
        <p class="each-post each-post-contents"><?php echo $a_post['content'] ?></p>
        <?php if (!empty($a_post['img_path'])): ?>
        <div class="image-posted-wrapper">
            <img 
            src="<?php echo $a_post['img_path'] ?>" 
            class="image-posted" />
        </div>
        <?php endif; ?>
    </div>
    <p class="each-post each-post-contents"><?php echo count($a_post['comments']) ?> comments</p>
    <div class="posts-buttons each-post">
        <a class="button-link" href='http://email.api:8080/class-post-editing?editingId=<?php echo $a_post["id"] ?>'>
            <img class="each-post post-button each-post-button" src="edit.svg" />
        </a>
        <button class="each-post comment-button-post each-post-button" onclick="commentFunction('<?php echo $formId ?>')">Comment</button>
        <a class="button-link" href='http://email.api:8080/class-post-delete?deleteId=<?php echo $a_post["id"] ?>'>
            <img class="each-post post-button each-post-button" src="delete.svg" />
        </a>
    </div>
    <div class="comment-form-wrapper" id="<?php echo $formId ?>">
        <?php foreach ($a_post['comments'] as $comment): ?>
            <div class="each-comment">
                <p class="comment-content"><?php echo $comment['content'] ?> | by: <?php echo $comment['author_id'] ?></p>
                <p class="comment-content">on: <?php echo $comment['date_posted'] ?></p>
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