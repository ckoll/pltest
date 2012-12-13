<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Latest uploaded photos</span>
    </div>
    <div class="bg">
        <div style="margin-left: 50px">
            <?
            if (!empty($photos)) {
                foreach ($photos as $val) {
                    ?>

                    <div class="photo_tumb">



                        <a href="/<?= $val['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>">
                            <img src="/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.<?=$val['image_type']?>" alt="" width="130">
                        </a>
                        <br>

                        <span class="hearts <?=!$val['liked']?'grey':''?> likes" data-id="<?= $val['id'] . $val['rand_num'] ?>" data-mode="upload"  data-type="<?=$val['liked']?'remove':'add'?>"><?=$val['like']?></span>
                        <span class="comments"><?= $val['comments'] ?></span>
                    <?php  if (!empty($admin)): ?>
                        <span class="delete">
                        <a style="height: auto; width: auto"
                           href="/user/delete_photo/?id=<?= $val['id'] . $val['rand_num'] ?>" onclick="return confirm('Are you sure you want to delete this photo?')">
                            <img src="/images/del.gif">
                        </a>
                        <a style="height: auto; width: auto"
                           href="/upload/photo_upload/<?= $val['id'] . $val['rand_num'] ?>/edit">
                            <img src="/images/edit.png">
                        </a>
                            </span>

                        <?php endif; ?>

                    </div>
                    <?
                }
            } else {
                ?><em>*No photos</em><?
            }
            ?>
        </div>
        <br class="clear">
        <?pagination($pages)?>
    </div>
    <div class="footer"></div>
</div>