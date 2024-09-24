
        <form method="POST" action="/comment-post" class="sign-up-form" id="sign-up-form">
            <h2>Make a comment</h2>
            <div class="form-div">
                <p class="message-error" id="comment-error"></p>
                <label>Comment:</label>
                <textarea name="comment"></textarea>
            </div>
            <div class="form-div">
                <input type="hidden" name="post-id" value="<?php echo $a_post["id"] ?>"/>
            </div>
            <button type="submit">Submit</button>
        </form>
    