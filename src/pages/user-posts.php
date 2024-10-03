<div class="each-user-post">
    <div>
        <p><?php echo $_SESSION['username'] ?></p>
        <p><?php echo $a_post['date_posted'] ?></p>
        <p><?php echo $a_post['content'] ?></p>
    </div>
    <div class="posts-buttons">
        <a href='http://email.api:8080/class-post-editing?editingId=<?php echo $a_post["id"] ?>'><img src="edit.svg" /></a>
        <button>Comment</button>
        <a href='http://email.api:8080/class-post-delete?deleteId=<?php echo $a_post["id"] ?>'><img src="delete.svg" /></a>
    </div>
</div>