<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="bg">
        <div class="page_title">
            <span>Saved outfits</span>
        </div>
        <span class="message" style="display: none"></span>
        <?
        if (!empty($outfits)) {
            ?>
        <a href="/files/users/dressup-HD/<?= $outfits[0]['id'] ?>.jpg" target="_blank">
                <div class="outfit_preview" style="background-image: url(/files/users/dressup/<?= $outfits[0]['id'] ?>.jpg);"></div>
            </a>        
            <br class="clear">
            <div id="scrollbar_outfit">
                <div class="viewport">
                    <div class="overview" <?
        if (count($outfits) > 6) {
            $pages = ceil(count($outfits) / 6);
            echo 'style="width:' . ($pages * 336) . 'px"';
        }
            ?>>
                             <?
                             foreach ($outfits as $key => $val) {
                                 ?>
                            <div class="outfit <? if ($key == 0) echo'active' ?>">
                                <img src="/files/users/dressup/<?= $val['id'] ?>.jpg" class="preview">
                                <small class="center"><?= $val['name'] ?>&nbsp;
                                    <a data-id="<?= $val['id'] ?>" class="wear_dressup">wear</a>
                                </small>
                                <img src="/images/del.png" class="remove_outfit" data-id="<?= $val['id'] ?>">
                            </div>
                            <?
                        }
                        ?>
                        <br class="clear">
                    </div>
                    <div class="scrollbar"><div class="track"><div class="thumb"></div></div></div>
                </div>
            </div>
            <?
        }else {
            ?><em>You haven't saved outfits</em><?
    }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>