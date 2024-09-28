<style>
    * {
        margin: 0;
        padding: 0;
    }
    table {
        border: 1px solid rgb(100, 100, 100);
        border-spacing: 0;
        margin: 10px;
    }

    thead {
        border: 1px solid black;
        border-color: black;
    }

    th {
        padding: 4px 8px;
    }

    .dates {
        padding: 3px 11px;
    }
    
    .contents {
        padding: 5px;
    }

    .upvotes {
        padding: 3px 35px;
    }



    .odd-row { background-color: #eee; }
</style>

<table rules="groups">
    <thead>
        <th>Date</th>
        <th>Content</th>
        <th>Upvotes</th>
    </thead>
    <?php $ctr = 1 ?>
    <?php foreach($posts as $post) { ?>
    <tr 
        class="<?php echo $ctr%2 == 0 ? 'odd-row' : '' ?>"
    >
        <td class="dates" nowrap><?php echo $post['date_posted'] ?></td>
        <td class="contents"><?php echo $post['content'] ?></td>
        <td class="upvotes"><?php echo $post['upvotes'] ?></td>
    </tr>
    <?php $ctr++ ?>
    <?php } ?>
</table>
