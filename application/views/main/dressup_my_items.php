<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>


<div id="content">

    <div class="page_title">
        <span>My inventory</span>
    </div>
    <div class="bg">

        <div style="width: 500px" class="left">

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">all (<?= $counts['all'] ?>/<?= $all_items ?>)</a></li>
                    <li><a href="#tabs-2">closet (<?= $counts['all'] - $counts['sell'] ?>)</a></li>
                    <li><a href="#tabs-3">selling (<?= $counts['sell'] ?>)</a></li>
                </ul>
                <div id="tabs-1">
                    <?
                    $pages = ceil(count($items) / 9);
                    if ($pages > 1) {
                        ?><div class="pages" style="width: 480px">
                            <strong>Page </strong>
                            <? for ($x = 0; $x < $pages; $x++) {
                                ?><a class="page <? if ($x == 0) echo 'selected' ?>" data-for="items_all_page" data-page="<?= $x + 1 ?>"><?= $x + 1 ?></a><? }
                            ?>
                        </div>
                        <br class="clear">
                        <?
                    }

                    $x = $block_num = 1;
                    $selling = $closet = array();
                    $n_open = false;
                    if (!empty($items)) {

                        foreach ($items as $val) {
                            if ($x == 1 || $n_open) {
                                $n_open = false;
                                $hide = ($x != 1) ? 'hide' : '';
                                echo '<div class="items_all_page ' . $hide . '" data-block="' . $block_num . '">';
                                $block_num++;
                            }
                            ?>
                            <div class="shop_item">
                                <?
                                $prof_dir = ($val['profileimage_dir'] == '[default]') ? 'profilepics' : $val['profileimage_dir'];
                                $product = '<img src="/files/items/' . $val['directory'] . '/' . $prof_dir . '/' . $val['profileimage'] . '">
                                <a href="/item/'.$val['shortname'].'"><p class="center title"><span class="item_name">' . $val['item_name'] . '</span> (x<span class="item_count">' . $val['counts'] . '</span>)</p></a>';
                                ?><?
                        switch ($val['status']) {
                            case 'sell':
                                $price = (!empty($val['price_b'])) ? $val['price_b'] . 'b' : $val['price_j'] . 'j';
                                $status = '<p class="center" style="font-size: 11px"><a data-id="' . $val['id'] . '" class="edit_selling">in shop (<span class="price">' . $price . '</span>)</a><br><a class="cancel_selling" data-id="' . $val['id'] . '">Cancel</a></p>';
                                $selling[] = $product . $status;
                                break;
                            case 'auction':
                                $left_time_u = unix_left_time_to_date($val['auction_date_end']);
                                $left_time_style = '';
                                if ($left_time_u <= 1800)
                                    $left_time_style = 'style="color:red;"';
                                $status = '<p class="center" style="font-size: 11px"><a href="#">in auction <span ' . $left_time_style . '>(' . auction_left_time($val['auction_date_end']) . ')</span></a><br><a class="cancel_auction" data-id="' . $val['id'] . '">Cancel</a></p>';
                                $closet[] = $product . $status;
                                break;
                            case 'dress':
                                $status = '<p class="center" style="font-size: 11px"><span class="blue">currently wearing</span></p>';
                                $closet[] = $product . $status;
                                break;
                            case 'default':
                                $status = '<p class="center" style="font-size: 11px"><span class="red ">can\'t sell</span> &nbsp; <span class="red">can\'t delete</span></p>';
                                $closet[] = $product . $status;
                                break;
                            default:
                                $status = '<p class="center" style="font-size: 11px"><a class="auction_item" data-id="' . $val['id'] . '">auction</a> <a style="margin-left: 10px" class="sell_item" data-id="' . $val['id'] . '">sell</a><img class="delete_item" data-id="' . $val['id'] . '" src="/images/delete.png"></p>';
                                $closet[] = $product . $status;
                        }
                        echo $product . $status;
                                ?>
                            </div><?
                        if ($x % 9 == 0 || $x == count($items)) {
                            echo'<br class="clear"></div>';
                            $n_open = true;
                        } elseif ($x % 3 == 0) {
                            echo '<br class="clear">';
                        }
                        $x++;
                    }
                } else {
                            ?><em>*No items</em><?
                }
                        ?><br class="clear">
                </div>

                <div id="tabs-2">
                    <?
                    $pages = ceil(count($closet) / 9);
                    pagination($pages);

                    $x = $block_num = 1;
                    $n_open = false;
                    if (!empty($closet)) {
                        $x = 1;
                        foreach ($closet as $val) {
                            if ($n_open || $x == 1) {
                                $n_open = false;
                                $hide = ($x != 1) ? 'hide' : '';
                                echo '<div class="items_dresses_page ' . $hide . '" data-block="' . $block_num . '">';
                                $block_num++;
                            }
                            ?>
                            <div class="shop_item">
                                <?= $val ?>
                            </div>
                            <?
                            if ($x % 9 == 0 || $x == count($closet)) {
                                $n_open = true;
                                echo'<br class="clear"></div>';
                            } elseif ($x % 3 == 0) {
                                echo '<br class="clear">';
                            }
                            $x++;
                        }
                    } else {
                        ?><em>*No items</em><?
                }
                    ?>
                    <br class="clear">
                </div>

                <div id="tabs-3">
                    <?
                    $pages = ceil(count($selling) / 9);
                    pagination($pages);

                    $x = $block_num = 1;
                    $n_open = false;
                    if (!empty($selling)) {
                        $x = 1;
                        foreach ($selling as $val) {
                            if ($n_open || $x == 1) {
                                $n_open = false;
                                $hide = ($x != 1) ? 'hide' : '';
                                echo '<div class="items_selling_page ' . $hide . '" data-block="' . $block_num . '">';
                                $block_num++;
                            }
                            ?>
                            <div class="shop_item">
                                <?= $val ?>
                            </div>
                            <?
                            if ($x % 9 == 0 || $x == count($selling)) {
                                echo'<br class="clear"></div>';
                                $n_open = true;
                            } elseif ($x % 3 == 0) {
                                echo '<br class="clear">';
                            }
                            $x++;
                        }
                    } else {
                        ?><em>*No items</em><?
                }
                    ?>
                    <br class="clear">
                </div>
            </div>
        </div>


        <div class="right_sidebar">
            <? $active = $this->uri->segment(2) ?>
            <a href="/dressup" class="button <? if (empty($active)) echo'checked' ?>">All</a>
            <br><br>
            <? $this->tpl->render_blocks('rmenu'); ?>
            <br>
        </div>

        <br class="clear">
    </div>
    <div class="footer"></div>
</div>




<div id="delete_item_modal" class="hide" title="Delete Item">
    <div class="modal center">
        <strong>Are you sure you want to delete one of the following items?</strong><br>
        <br>
        <span class="del_info"></span><br>            
        <input type="button" name="del_confirm" class="big_button" value="Yes, delete">
        <input type="button" class="close_del_modal" value="No, cancel" style="margin-left: 30px">
    </div>
</div>
<div id="cancel_auction_modal" class="hide" title="Cancel auction">
    <div class="modal center">
        <span class="cancel_auction_info"></span><br>
        <strong>This will put the item back in your closet</strong><br><br>
        <input type="button" name="rem_auction_confirm" class="big_button" value="Confirm">
        <input type="button" class="close_auction_modal" value="Keep item in shop" style="margin-left: 30px">
    </div>
</div>
<div id="cancel_selling_modal" class="hide" title="Cancel selling">
    <div class="modal center">
        <span class="cancel_sell_info"></span><br>
        <strong>This will put the item back in your closet</strong><br><br>
        <input type="button" name="rem_sell_confirm" class="big_button" value="Confirm">
        <input type="button" class="close_sell_modal" value="Keep item in shop" style="margin-left: 30px">
    </div>
</div>
<div id="add_auction_modal" class="hide" title="Add auction item">
    <div class="modal center">
        <br>
        <span class="auction_info"></span>

        <div class="auction_options">
            <div>
                starting price <input type="text" class="numeric" value="0" name="start_price" style="width: 30px"><br>
                reserve (optional) <a>?</a> <input type="text" class="numeric" value="0" name="reserve" style="width: 30px">
            </div>
            <div>
                choose one:<br>
                <input type="radio" name="price_type" checked value="price_b"> buttons<br>
                <input type="radio" name="price_type" value="price_j"> jewels
            </div>
            <div>
                duration:<br>
                <select name="duration">
                    <option value="6">6 hrs</option>
                    <option value="12">12 hrs</option>
                    <option value="1">1 day</option>
                    <option value="2">2 days</option>
                    <option value="3">3 days</option>
                </select>
            </div>
        </div>
        <br class="clear"><br>
        <input type="button" name="list_auction" class="big_button"  value="list">
        <input type="button" class="hide_add_auction_modal" value="cancel">
    </div>
</div>
<div id="selling_modal" class="hide" title="Sell item">
    <div class="modal center">
        <br>
        <span class="sell_info"></span>
        <table class="sell_info_count">
            <tr>
                <td width="200">Current quantity in closet:</td>
                <td>(<span class="sell_count_now"></span>)</td>
            </tr>
            <tr>
                <td>Quantity to sell: </td>
                <td><select name="sell_count"></select></td>
            </tr>
        </table>
        <br class="clear">

        <div class="sell_options">
            Option #1:<br>
            sell immediately for <span class="item_one_price"></span> buttons each.<br>
            (based on 10% of average user price)<br>
            Total earnings: <span class="item_total_price"></span> buttons<br>
            <input type="button" name="sell1" value="Sell">
        </div>
        <div class="sell_options">
            Option #2:<br>
            sell in the market<br>
            price of each item: <input type="text" name="my_item_price" class="numeric" style="width: 30px"> buttons<br>
            <br>
            <input type="button" name="sell2" data-edit="0" value="Sell">
        </div>

        <br class="clear"><br>
        <input type="button" class="hide_sell_modal big_button" value="cancel">
    </div>
</div>

<div id="edit_selling_modal" class="hide" title="Edit selling item">
    <div class="modal center">
        <br>
        <span class="sell_info"></span>
        <table class="edit_info_count">
            <tr>
                <td width="120">Total quantity:</td>
                <td>(<span class="total_count"></span>)</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Currently in shop: </td>
                <td><span class="sell_count_now"></span></td>
                <td>Current price: (<span class="item_price"></span>)</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"><a class="stop_selling">Stop selling this item and return to inventory</a><br>
                    Or edit selling:<br>
                    New quantity: <select name="sell_count"></select>
                </td>
            </tr>
        </table>

        <br class="clear">
        <div class="sell_options">
            Option #1:<br>
            sell immediately for <span class="item_one_price"></span> buttons each.<br>
            (based on 10% of average user price)<br>
            Total earnings: <span class="item_total_price"></span> buttons<br>
            <input type="button" name="sell1" value="Sell">
        </div>
        <div class="sell_options">
            Option #2:<br>
            sell in the market<br>
            New price of each item: <input type="text" name="my_item_price" class="numeric" style="width: 30px"> buttons<br>
            <br>
            <input type="button" name="sell2" data-edit="1" value="Sell">
        </div>

        <br class="clear"><br>
        <input type="button" class="hide_edit_modal big_button" value="close">
    </div>
</div>
