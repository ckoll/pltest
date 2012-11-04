<?
$shop_type = $this->uri->segment(1);
$shop_username = $this->uri->segment(2);
?>
<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Marketplace</span>
    </div>
    <div class="bg">
        <div class="left">
            Item type: 
            <a class="market_filter <? if (empty($_SESSION['market_filter'])) echo 'selected' ?>" data-type="">All</a> | 
            <a class="market_filter <? if ($_SESSION['market_filter'] == 'price_b') echo 'selected' ?>" data-type="price_b">Buttons</a> | 
            <a class="market_filter <? if ($_SESSION['market_filter'] == 'price_j') echo 'selected' ?>" data-type="price_j">Diamonds</a><br>
            Filter by category: <?= (empty($_GET['cat'])) ? 'currently showing all items' : 'currently showing ' . $categories[$_GET['cat']]['name'] . ' items'; ?>
        </div>
        <div class="right">
            Sort By: <select name="market_sort">
                <option>(select)</option>
                <option value="price1" <? if ($_SESSION['market_sort'] == 'price1') echo'selected="selected"' ?>>price (lowest -> highest)</option>
                <option value="price2" <? if ($_SESSION['market_sort'] == 'price2') echo'selected="selected"' ?>>price (highest -> lowest)</option>
            </select>
        </div>
        <br class="clear">
        <hr>
        <a href="/<?= $shop_type ?>/<? if (!empty($shop_username)) echo $shop_username ?>" class="button categories <? if (empty($_GET['cat'])) echo'checked' ?>">All</a>
        <?
        if (!empty($categories)) {
            foreach ($categories as $val) {
                ?>
                <a href="/<?= $shop_type ?>/<? if (!empty($shop_username)) echo $shop_username . '/' ?>?cat=<?= $val['shortname'] ?>" class="button categories <? if ($_GET['cat'] == $val['shortname']) echo'checked' ?>"><?= $val['name'] ?></a>
                <?
            }
        }
        ?>
        <br class="clear">

        <?
        if (!empty($items)) {
            $x=0;
            foreach ($items as $val) {
                $x++;
                if ($shop_type != 'shops') {
                    //Users shop
                    ?>
                    <div class="shop_item">
                        <img src="/files/items/<?= $val['directory'] ?>/<?= ($val['profileimage_dir']=='[default]')? 'profilepics' : $val['profileimage_dir'] ; ?>/<?= $val['profileimage'] ?>">
                        <a class="center title" href="/item/<?= $val['shortname'] ?>"><?= $val['item_name'] ?></a>
                        <p class="center"><?
            if (!empty($val['price_b'])) {
                echo $val['price_b'] . ' buttons';
            } elseif (!empty($val['price_j'])) {
                echo $val['price_j'] . ' jewels';
            } else {
                echo 'free';
            }
            if (!is_my_page($val['username'])) {
                        ?> 
                                <a class="buy_item" data-type="<?= $shop_type ?>" data-id="<?= $val['id'] ?>">buy</a>
                            <? } ?>
                            <br>
                            by: <a href="/<?= $val['username'] ?>"><?= $val['username'] ?></a>
                        </p>
                    </div>
                    <?
                    if($x%4 == 0){
                        ?><br class="clear"><?
                    }
                } else {
                    //Perfectlook shop
                    ?>
                    <div class="shop_item">
                        <img src="/files/items/<?= $val['directory'] ?>/<?= ($val['profileimage_dir']=='[default]')? 'profilepics' : $val['profileimage_dir'] ; ?>/<?= $val['profileimage'] ?>">
                        <a class="center title" href="/item/<?= $val['shortname'] ?>"><?= $val['item_name'] ?></a>
                        <p class="center"><?
            if (!empty($val['price_b'])) {
                echo $val['price_b'] . ' buttons';
            } elseif (!empty($val['jewels'])) {
                echo $val['jewels'] . ' jewels';
            } else {
                echo 'free';
            }
            if ($val['limit'] != 0) {
                        ?> 
                                - <a class="buy_item" data-type="<?= $shop_type ?>" data-id="<?= $val['id'] ?>">buy</a>
                            <? } else { ?>
                                - <span class="red">Sold Out</span>
                            <? } ?>
                        </p>
                    </div> 
                    <?
                    
                    if($x%4 == 0){
                        ?><br class="clear"><?
                    }
                    
                }
            }
        } else {
            echo '<em>*No items</em>';
        }
        ?>
            <br class="clear">
            <? pagination($pages);  ?>
    </div>
    <div class="footer"></div>
</div>

<div id="buy_modal" class="hide" title="Buy item">
    <div class="modal">

        <div class="ok hide center">
            <strong>Are you sure you want to buy:</strong>
            <p id="item_data"></p>
            <br>
            <p>
                <input type="button" name="buy_button" data-type="<?= $shop_type ?>" value="Yes, buy!" class="big_button"><br>
                <input type="button" value="no, cancel" class="close_modal">
            </p>
        </div>

        <div class="error hide center">
            <p class="info"></p>
            <div class="jewels_info hide">
                <br>
                <input type="button" class="big_button close_modal" value="close">
                <br><br>
                <a href="#">How to get more jewels</a>&nbsp;
                <a href="#">Go to bank</a>&nbsp;
                <a href="#">Buy jewels</a><br>
                <br>
                <div style="border: 2px solid #CCC; padding: 10px; margin: auto; width: 150px">
                    Current special:<br>
                    10 jewels<br>
                    for only $1<br>
                    <a href="#">buy now</a>
                </div>
            </div>
            <div class="buttons_info hide"><br>
                <input type="button" class="big_button close_modal" value="close"><br>
                <a href="">How to get more buttons</a>
            </div>
            <div class="syserr_info hide">
                <strong>Item no longer available</strong><br><br>
                <span style="text-align: left">
                    Sorry, but this item is not longer available.<br>
                    It may have been bought by another user or<br> the shop owner has removed it from their shop.<br><br>
                    Try to be faster next time!<br>
                </span><br>
                <input type="button" value="close" class="big_button close_modal">
            </div>
        </div>

    </div>
</div>