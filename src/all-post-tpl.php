<div class="each-post">
    <p><?php echo $_SESSION['username'] ?></p>
    <p><?php echo $a_post['content'] ?></p>
    <p><?php echo $a_post['date_posted'] ?></p>
    <p><a href='http://email.api:8080/class-post-editing?editingId="<?php echo $a_post["id"] ?>"'>Edit</a></p>
    <p><a href='http://email.api:8080/class-post-delete?deleteId="<?php $a_post["id"] ?>"'>Delete</a></p>
    <?php foreach ($a_post['comments'] as $comment): ?>
        <div class="each-comment">
            <p><?php echo $comment['content'] ?> | by: <?php echo $comment['author_id'] ?></p>
            <p>on: <?php echo $comment['date_posted'] ?></p>
        </div>
    <?php endforeach; ?>
    <button onclick="myFunction('<?php echo $formId ?>')">Comment</button>
    <div id="<?php echo $formId ?>" class="form-wrapper">
    <form method="POST" action="/comment-post" class="sign-up-form" id="<?php //echo $formId ?>">
        <h2>Make a comment</h2>
        <div class="form-div">
            <p class="message-error" id="comment-error"></p>
            <label>Comment:</label>
            <textarea name="comment"></textarea>
        </div>
        <div class="form-div">
            <input type="hidden" name="post-id" value="<?php echo $a_post["id"] ?>" />
        </div>
        <button type="submit">Submit</button>
    </form>
    </div>
</div>