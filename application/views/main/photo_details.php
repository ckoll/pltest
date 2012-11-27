<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>
            Photo details
            <?php  if (!empty($admin)): ?>
            <a href="/user/delete_photo/?id=<?= $photo['id'] . $photo['rand_num'] ?>">Delete</a>
            <?php endif; ?>
        </span>
    </div>

    <div class="bg">
        <div class="photo_cont">
            <div class="details-top-buttons"  style="display: none">
            <span class="photo_details_buttons">
            <a class="twitt_button cool-button"
               onclick="twitterPopup('<?=urlencode("@perfectlookorg " . $photo['caption'])?>', '<?=urlencode(current_url())?>')">twitter</a>
            </span>

            <span class="photo_details_buttons">
               <a class="cool-button fb-button" onclick="facebookPopup('<?=urlencode(current_url())?>')">facebook</a>
            </span>
            </div>
            <div class="clear"></div>
            <a href="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>_original.<?=$photo['image_type']?>"
               target="_blank">
                    <img style="max-height: 500px; max-width: 500px;"
                         src="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>.<?=$photo['image_type']?>">

            </a><br>
            <br>

            <div class=" photo_details_buttons hearts <?=!$photo['liked'] ? 'grey' : ''?> likes"
                 data-id="<?= $photo['id'] . $photo['rand_num'] ?>" data-mode="upload"
                 data-type="<?=$photo['liked'] ? 'remove' : 'add'?>"><?=$photo['like']?></div>

            <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>

            <?php if (!empty($admin) || $photo['uid'] == $this->user['id']): ?>
            <a style="float: right" href="/upload/photo_upload/<?= $photo['id'] . $photo['rand_num'] ?>/edit"
               class="photo_details_buttons"><img
                    src="/images/edit.png"></a>
            <?php endif; ?>
        </div>
        <br class="clear">
        <?
        if (!empty($photo['caption'])) {
            echo $photo['caption'] . '<br>';
        }
        ?>

        <br><a name="comments"></a>
        <strong>Comments</strong><br>

        <div class="comments_block">
            <form method="post">
                <p style="margin-left: 8px">Leave a comment:</p>
                <textarea name="comment" style="width: 430px"></textarea>
                <br>

                <div class="center"><input type="submit" name="add_photo_comment" value="send"></div>
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


<script type="text/javascript">


</script>