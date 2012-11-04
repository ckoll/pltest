<?
$pages = ceil(count($items) / 12);
if ($pages > 1) {
    for ($x = 1; $x <= $pages; $x++) {
        ?><span class="page_small <? if ($x == 1) echo'active' ?>" data-page="<?= $x ?>"><?= $x ?></span><?
    }
    ?>
    <br class="clear">
    <?
}
$x = 1;
$item_blocks = array_chunk($items, 12);
foreach ($item_blocks as $key => $val) {
    if ($key != 0) {
        $hide = 'hide';
    } else {
        $hide = '';
    }
    ?><div class="items_all_page <?= $hide ?>" data-block="<?= $key + 1 ?>"><?
    foreach ($val as $v) {
        ?>
            <div class="item_one" data-id="<?= $v['id'] ?>" data-type="<?= $v['type'] ?>">
                <?
                $prof_dir = ($v['profileimage_dir'] == '[default]') ? 'profilepics' : $v['profileimage_dir'];
                ?>
                <img src="/files/items/<?= $v['directory'] ?>/<?= $prof_dir ?>/<?= $v['profileimage'] ?>">
                <small><?= $v['item_name'] ?></small>
            </div>
            <?
            if ($x % 4 == 0) {
                echo'<br class="clear">';
            }
            $x++;
        }
        ?>
        <br class="clear"></div>
    <?
}
?>