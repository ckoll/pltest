<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Buy jewels</span>
    </div>
    <div class="bg">

        <?
        if (isset($order_done)) {
            if ($order_done == 1) {
                ?><p class="message">Buy jewels done</p> <? } else { ?><p class="err">Buy jewels error</p> <?
    }
}
        ?>

        <div class="buy_jewels_content">
            <form method="post">
                <div>
                    <div class="my_jewels">
                        <p class="inf1">You have:</p>
                        <p class="inf2">Jewels: <span><?= $u_jewels ?></span></p>
                        <br>
                        <select style="width: 100px;" name="jewels_count">
                            <option selected value="0">(select)</option>
                            <option value="10">10 jewels</option>
                            <option value="25">25 jewels</option>
                            <option value="75">75 jewels</option>
                            <option value="150">150 jewels</option>
                            <option value="400">400 jewels</option>
                        </select>
                    </div>
                    <div class="center price_jewels">
                        <p>Price list:</p>
                        <p>10 jewel for 1$</p>
                        <p>25 jewel for 2$</p>
                        <p>75 jewel for 5$</p>
                        <p>150 jewel for 10$</p>
                        <p>400 jewel for 20$</p>
                    </div>
                </div>
                <br class="clear"><br>
                <div class="center" style="width:400px">
                    <input type="submit" name="PayPal" class="jevels_payment" value="Buy with PayPal">
                </div>
            </form>
            <form name="buy_with_co" action='https://www.2checkout.com/checkout/purchase' method='post'>
                <p class="center" style="width:400px;">
                    <input type='hidden' name='sid' value='1795935'>
                    <input type='hidden' name='quantity' value='1'>
                    <input type='hidden' name='product_id' value=''>
                    <input type='hidden' name='email' value='<?= $this->user['email'] ?>'>
                    <input name='2checkout' type='submit' class="jevels_payment" value='Buy with 2checkout' >
                </p>
            </form>
            <br>
            <p style="margin-left: 50px">Alternative payment options:<br>
                (email admin@perfect-look.org for alternative payment options)</p>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>