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
        <a href="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>_original.<?=$photo['image_type']?>"
           target="_blank">
            <center>
                <img style="max-height: 500px; max-width: 500px;"
                     src="/files/users/uploads/<?= $photo['uid'] ?>/<?= $photo['id'] . $photo['rand_num'] ?>.<?=$photo['image_type']?>">
            </center>
        </a><br>

            <div class="hearts <?=!$photo['liked']?'grey':''?> likes" data-id="<?= $photo['id'] . $photo['rand_num'] ?>" data-mode="upload"  data-type="<?=$photo['liked']?'remove':'add'?>"><?=$photo['like']?></div>

        <span class="comments dressup_details photo_details_buttons"><?= count($comments) ?></span>

        <span class="photo_details_buttons">
            <a class="twitt_button" onclick="twitterPopup()"></a>
            <script type="text/javascript">
                function twitterPopup()
                {
                    var url = "http://twitter.com/share?text=<?=urlencode("@perfectlookorg ".$photo['caption'])?>&url=<?=urlencode(current_url())?>";
                    var mywindow = window.open (url,"share","menubar=0,resizable=1,width=550,height=450");
                    mywindow.moveTo(0, 0);
                }
            </script>
        </span>

        <span class="photo_details_buttons">
           <img src="/images/facebook.png" alt="" onclick="ShareClicked()">
        </span>

        <?php if (!empty($admin) || $photo['uid'] == $this->user['id']): ?>
        <a href="/upload/photo_upload/<?= $photo['id'] . $photo['rand_num'] ?>/edit" class="photo_details_buttons"><img
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

        function ShareClicked() {
            var requestObj = {
                method: 'feed',
                link: '<?=current_url()?>',
                picture: "<?=base_url('/files/users/uploads/'.$photo['uid'].'/'.$photo['id'] . $photo['rand_num'].'.'.$photo['image_type'])?>",
                name: "Perfect-Look",
                caption: "Perfect-Look",
                description: '<?=$photo['caption']?>',
                ref: "Public"
            };

            FB.ui(requestObj, function(response) {});


        }


    </script>