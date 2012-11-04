<?
$auction_type = $this->uri->segment(1);
$auction_username = $this->uri->segment(2);
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
        <a href="/<?= $auction_type ?>/<? if (!empty($auction_username)) echo $auction_username ?>" class="button categories <? if (empty($_GET['cat'])) echo'checked' ?>">All</a>
        <?
        if (!empty($categories)) {
            foreach ($categories as $val) {
                ?>
                <a href="/<?= $auction_type ?>/<? if (!empty($auction_username)) echo $auction_username . '/' ?>?cat=<?= $val['shortname'] ?>" class="button categories <? if ($_GET['cat'] == $val['shortname']) echo'checked' ?>"><?= $val['name'] ?></a>
                <?
            }
        }
        ?>
        <br class="clear">

        <?
        if (!empty($items)) {
            foreach ($items as $val) {
                //auction items
                ?>
                <div class="shop_item">
                    <img src="/files/items/<?= $val['directory'].'/'.(($val['profileimage_dir']=="[default]")?'profilepics':$val['profileimage_dir']).'/'.$val['profileimage'] ?>">
                    <p class="center title"><?= $val['item_name'] ?></p>
                    <p class="center"><?
                    $pref = ($auction_type=='userauction')?'auction_':'';
        $bids = unserialize($val[$pref.'date_price']);
        $last_bid = array_pop($bids);
        echo ((auction_left_time($val[$pref.'date_end']))?auction_left_time($val[$pref.'date_end'])." ":'') . $last_bid['price'] . " ";
        echo (!empty($val['price_b']) ? 'buttons' : 'jewels');
        echo ($val[$pref.'reserve'] && auction_left_time($val[$pref.'date_end'])) ? ' (R)' : '';
        echo ($val[$pref.'reserve'] && !auction_left_time($val[$pref.'date_end'])) ? ' not met(R)' : '';
        $bids = unserialize($val[$pref.'date_price']);
        echo '<br>' . (count($bids) - 1) . ' bids ';
                ?><a class="bid_now" data-type="<?= $auction_type; ?>" data-id="<?= ($auction_type == 'userauction') ? $val['ui_id'] : $val['id'] ?>">bid now</a></p>

                </div>
                <?
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


<div id="bid_modal" class="hide" title="Bid now">
    <div class="modal">
        <div class="ok">
            <div id="item_data" class="center" style="float:left">
            </div>
            <div style="float:left; margin-left:20px; margin-top: 10px">
                <div style="float:left;">
                    <p>Current bid: <span id="cur_bid"></span>&nbsp;<span class="duration"></span></p>
                    <p>Reserve: <span id="reserve"></span>&nbsp;<span class="duration"></span></p>
                </div>
                <div style="float:left; margin-left:20px">
                    <p><a id="open_bid_history"><span id="count_bids"></span>&nbsp;bids</a></p>
                    <p id="reserve_met"></p>
                </div>
            </div>    
            <br class="clear">
            <p style="margin-left:50px; margin-top:30px;">time left&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="time_left"></span></p>

            <p class="center" style="margin-top:10px" ><input type="text"  class="numeric" style="width:60px;" name="bid">&nbsp;<span class="duration"></span>&nbsp;&nbsp;<input type="button" data-item-id="" class="big_button place_bid" data-type="<?= $auction_type ?>" name="place_bid" value="place bid">&nbsp;&nbsp;<input type="button" class="big_button close_modal" value="cancel"></p>
        </div>
    </div>
</div>
<div id="bid_history_modal" class="hide" title="Bid now">
    <p>Bid history</p>
    <table id="bid_history_table">

    </table>
</div>

