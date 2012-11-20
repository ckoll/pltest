<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Dressup Details</span>
    </div>
    <div class="bg">
        <div class="photo_cont">
        <strong>More details about this dressup</strong><br>
        <a href="/files/users/dressup-HD/<?= $item['id'] ?>.jpg" target="_blank">
            <img src="/files/users/dressup/<?= $item['id'] ?>.jpg">
        </a>
            <br>
            <br>
        <span class="likes dressup_details photo_details_buttons" data-id="<?= $item['id'] ?>" data-mode="dressup"><?= $item['like'] ?></span>
        <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>
        <span class="photo_details_buttons">
            <a class="twitt_button" href="http://twitter.com/share?text=<?=urlencode($item['name'] . ' ' . $item['dress_comment'])?>&url=<?=urlencode(current_url())?>" target="_blank"></a>
        </span>
        </div>
        <br class="clear">
        <br>
        Username: <a href="/<?= $item['username'] ?>"><?= $item['username'] ?></a><br>
        <?
        if (!empty($item['name'])) {
            echo 'Dressup: ' . $item['name'] . '<br>';
        }
        if(!empty($item['dress_comment'])){
            echo 'Dressup description: '.$item['dress_comment'];
        }
        ?>
        <br><a name="comments"></a>
        <strong>Comments</strong><br>
        <div class="comments_block">
            <form method="post">
                <p style="margin-left: 8px">Leave a comment:</p>
                <textarea name="comment" style="width: 430px"></textarea>
                <br>
                <div class="center"><input type="submit" name="add_dressup_comment" value="send"></div>
            </form>
            <? pagination($pages);
                if (!empty($comments)) {
                    echo "<div>";
                    foreach ($comments as $comment) {
                        ?>
                    <div class="comment">
                        <div class="center userinfo">
                            <div class="avatar100"><div class="border"></div><img src="<?= get_user_avatarlink($comment['uid']) ?>"></div>
                            <a href="/<?= $comment['username'] ?>"><?= $comment['username'] ?></a>
                        </div>
                        <div class="text">
                            <small class="comment"><?= time_from($comment['date']) ?>
                            <?
                            if($this->user['id'] == $comment['uid']){
                                ?>&nbsp;&nbsp;&nbsp;<a href="?rem=<?=$comment['comment_id']?>">remove</a><?
                            }
                            ?>
                            </small><br>
                            <p><?= $comment['comment'] ?></p>
                        </div>
                        <br class="clear">
                    </div>
                    <?
                }
                echo "</div>";
            }
            ?>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>