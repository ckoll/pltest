<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span><?= $item['item_name'] ?></span>
    </div>
    <div class="bg">

        <div class="center">
            <?
            if($item['profileimage_dir']=='[default]'){
                $item['profileimage_dir'] = 'profilepics';
            }
            ?>
            <img src="/files/items/<?=$item['directory']?>/<?= $item['profileimage_dir'] ?>/<?= $item['profileimage'] ?>"><br>
        </div>
        Item description:<br>
        <?= $item['description'] ?>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>