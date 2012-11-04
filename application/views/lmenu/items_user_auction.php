<?
if (!empty($items_auction)) {
    ?>
    <section class="lmenu_block">
        <h2>Items in my auction</h2>
        <ul class="columns2">
            <?
            foreach ($items_auction as $val) {
                ?>
                <li>
                    <div style="height: 90px;">
                        <img src="/files/items/<?= $val['preview'] ?>">
                    </div>
                    <p class="center">
                        <?= $val['title'] ?><br>
                        <?= $val['price_b'] ?>b
                    </p>
                </li>
                <?
            }
            ?>
        </ul>
        <span class="right">total <?= $items_auction_total ?> items in auction</span><br>
        <a href="#" class="right">Goto my auction</a>
        <br class="clear">
    </section>
<?
}?>