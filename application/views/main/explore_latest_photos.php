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
                    <a href="/<?= $this->user['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>" style="background-image: url('/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.jpg')"></a><br>
                    <span class="likes" data-id="<?=$val['id'] . $val['rand_num']?>" data-mode="upload"><?= $val['like'] ?></span>
                    <span class="comments"><?= $val['comments'] ?></span>
                    <!--<img src="/images/del.png" data-id="<?= $val['id'] . $val['rand_num'] ?>" class="right del_photo">-->
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