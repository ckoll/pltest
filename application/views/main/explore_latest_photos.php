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
                        <?php  if (!empty($admin)): ?>

                        <?php endif; ?>
                        <a href="/<?= $this->user['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>">
                            <img src="/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.<?=$val['image_type']?>" alt="" width="130">
                        </a>
                        <br>

                        <span class="hearts <?=!$val['liked']?'grey':''?> likes" data-id="<?= $val['id'] . $val['rand_num'] ?>" data-mode="upload"  data-type="<?=$val['liked']?'remove':'add'?>"><?=$val['like']?></span>
                        <span class="comments"><?= $val['comments'] ?></span>
                        <span class="delete">
                        <a style="height: auto; width: auto"
                           href="/user/delete_photo/?id=<?= $val['id'] . $val['rand_num'] ?>">
                            <img src="/images/del.png">
                        </a>
                            </span>

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