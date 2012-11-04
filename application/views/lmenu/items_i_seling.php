<section class="lmenu_block items_i_seling">
    <?
    if (!empty($items_selling)) {
        ?>
        <ul class="columns2">
            <?
            foreach ($items_selling as $val) {
                ?>
                <li>
                    <div>
                        <a class="image" style="background-image: url('/files/items/<?= $val['directory']?>/<?=($val['profileimage_dir']=='[default]')?'profilepics':$val['profileimage_dir']?>/<?=$val['profileimage'] ?>')"></a>
                    </div>
                    <p class="center">
                        <?= $val['item_name'] ?><br>
                        <?= $val['price_b'] ?> buttons
                    </p>
                </li>
                <?
            }
            ?>
        </ul>
        <?
    }
    ?><br>
    <span>total selling <?= $items_selling_total ?> items</span>
    <a href="/users_shop/<?= $username ?>" class="right">Go to my shop</a>
    <br class="clear">
</section> 
