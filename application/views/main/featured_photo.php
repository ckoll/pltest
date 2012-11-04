<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Featured photos</span>
    </div>
   
    <div class="bg">
        <? pagination($pages) ?>
        <?
        if (!empty($photo)) {
            foreach ($photo as $val) {
                ?>
                <div style="margin-bottom: 20px; ">
                    <div class="featured_photo">
                            <img src="/files/featured/<?= $val['type'] ?>/<?= $val['imagename'] ?>">
                    </div>
                </div>
                <?
            }
        }
        ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>