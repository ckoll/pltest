<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Top 10 photos</span>
    </div>
    <div class="bg">
        <?
        if (!empty($photo)) {
            foreach ($photo as $val) {
                ?>
                <div style="margin-bottom: 20px; ">
                    <div class="img_bg left top_photo">
                        <a href="/<?= $val['username'] ?>/photo/<?= $val['id'] . $val['rand_num'] ?>" style="background-image: url('/files/users/uploads/<?= $val['uid'] ?>/<?= $val['id'] . $val['rand_num'] ?>.<?=$val['image_type']?>');">
                        </a>
                    </div>
                    <div class="left">
                        <a href="/<?= $val['username'] ?>"><?= $val['username'] ?></a><br>
                        <span class="likes" style="margin: 4px" data-id="<?= $val['id'] . $val['rand_num'] ?>" data-mode="upload"><?= $val['like'] ?></span>
                        <span class="comments" style="margin: 4px"><?= intval($val['comments']) ?></span>
                    </div>
                    <br class="clear">
                </div>
                <?
            }
        }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>