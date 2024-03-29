<?php if (isset($this->user['id'])): ?>
<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>
<?php endif; ?>

<div id="content">

    <div class="page_title">
        <span>Dressup Details</span>
    </div>
    <div class="bg">
        <div class="photo_cont">
            <div class="details-top-buttons">
                <?php
                $shareText = urlencode("@perfectlookorg " . $item['name'] . ' ' . $item['dress_comment'] . ' ' . base_url() . 'files/users/dressup/'.$item['id'].'.jpg');
                ?>
                <span class="photo_details_buttons" style="margin-right: 20px">
                    <a class="twitt_button cool-button"
                       onclick="twitterPopup('<?=$shareText?>', '<?=urlencode(current_url())?>')">twitter</a>
                </span>

                <span class="photo_details_buttons">
                   <a class="cool-button fb-button"
                      onclick="facebookPopup('<?=urlencode(current_url())?>')">facebook</a>
                </span>
            </div>
            <div class="clear"></div>
            <strong>More details about this dressup</strong><br>
            <a href="/files/users/dressup-HD/<?= $item['id'] ?>.jpg" target="_blank">
                <img src="/files/users/dressup/<?= $item['id'] ?>.jpg">
            </a>
            <br>
            <br>
            <span style="margin-left: 10px"
                  class="hearts <?=!$item['liked'] ? 'grey' : ''?> likes dressup_details photo_details_buttons"
                  data-id="<?= $item['id'] ?>" data-mode="dressup"
                  data-type="<?=$item['liked'] ? 'remove' : 'add'?>"><?=$item['like']?></span>
            <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>
            <?php if (!empty($admin) || $item['uid'] == $this->user['id']): ?>
            <a style="float: right; margin-right: 20px" href="/dressup/dress/<?= $item['id'] ?>"
               class="photo_details_buttons"><img
                    src="/images/edit.png"></a>
            <?php endif; ?>
            <?php if (!empty($admin)):?>
            <a style="float: right; margin-right: 20px" href="/dressup/regenerate_images/<?= $item['id'] ?>"
               class="photo_details_buttons"><img
                    src="/images/error.png"></a>
            <?php endif; ?>

        </div>
        <br class="clear">
        <br>
        Username: <a href="/<?= $item['username'] ?>"><?= $item['username'] ?></a><br>
        <?
        if (!empty($item['name'])) {
            echo 'Dressup: ' . $item['name'] . '<br>';
        }
        if (!empty($item['dress_comment'])) {
            echo 'Dressup description: ' . $item['dress_comment'];
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
                            <div class="avatar100">
                                <div class="border"></div>
                                <img src="<?= get_user_avatarlink($comment['uid']) ?>"></div>
                            <a href="/<?= $comment['username'] ?>"><?= $comment['username'] ?></a>
                        </div>
                        <div class="text">
                            <small class="comment"><?= time_from($comment['date']) ?>
                                <?
                                if ($this->user['id'] == $comment['uid']) {
                                    ?>&nbsp;&nbsp;&nbsp;<a href="?rem=<?=$comment['comment_id']?>">remove</a><?
                                }
                                ?>
                            </small>
                            <br>

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

