<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
</div>

<div id="content">
    <div class="page_title">
        <span><?= $title ?></span>
    </div>
    <div class="bg">
        <div style="margin: 0 10px">
            <?
            $i = 0;
            if (!empty($items)) {
                foreach ($items as $item) {
                    ?>
                    <div class="invent_item">
                        <div>
                            <a style="display:block;" href="/item/<?= $item['shortname'] ?>">
                                <img src="/files/items/<?= $item['directory'] ?>/<?= ($item['profileimage_dir'] == '[default]') ? 'profilepics' : $item['profileimage_dir']; ?>/<?= $item['profileimage'] ?>">
                            </a>
                        </div>
                        <a href="/item/<?= $item['shortname'] ?>">(x<?= $item['counts'] ?>) <?= $item['item_name'] ?></a>
                    </div>
                    <? if ((++$i % 6) == 0) {
                        ?> <br class="clear"><hr> <?
        }
    }
    ?>
    <br class="clear">
    <? pagination($pages) ?>
<? } ?>
        </div>
    </div>
    <div class="footer"></div>
</div>
