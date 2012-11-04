<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">

        <?
        if (!empty($gifts)) {
            foreach ($gifts as $val) {
                ?>
                <div class="friend">
                    <img src="/files/items/<?= $val['directory'].'/'.(($val['profileimage_dir']=='[default]')?'profilepics':$val['profileimage_dir']).'/'.$val['profileimage'] ?>"><br>
                    <?=$f_t?>: <?= show_username_link($val['username']) ?><br>
                    <?= time_from($val['date']) ?>
                </div>
                <?
            }
            ?><br class="clear"><?
    pagination($pages);
        } else {
            ?><em>No received gifts</em><?
    }
        ?>
            <br class="clear">
    </div>
    <div class="footer"></div>
</div>