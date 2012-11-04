<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?=$title?></span>
    </div>
    <div class="bg">
        <div class="brands" style="border:0;">
             <? $i = 0;
            if(!empty($brands)){
            foreach ($brands as $val) { ?>
                <div>
                    <img src="/files/brands/<?= (empty($val['imagename']) || !file_exists(APPPATH.'files/brands/'.$val['imagename']))?'default.png':urlencode($val['imagename']) ?>" width="75"><br>
                    <a href="/brands/<?= str_replace(' ', '-', strtolower($val['title'])) ?>"><?= $val['title'] ?></a>
                </div>
                <? if ((++$i % 6) == 0) {
                    ?> <br class="clear"><hr> <?
        }
    }
            }
            if(($i % 6) != 0) {?><br class="clear"><hr><?}
            ?>
            <br class="clear">
            <? pagination($pages)?>
        </div>
    </div>
    <div class="footer"></div>
</div>
