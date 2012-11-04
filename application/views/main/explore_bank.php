<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">

    <div class="page_title">
        <span>Exchange buttons for jewels</span>
    </div>
    <div class="bg">

        <div class="bank_content">
            <div>
                <div class="bank_info">
                    <p>You have:</p>
                    <p>Buttons: <span><?= $u_buttons ?></span></p>
                    <p>Jewels: <span><?= $u_jewels ?></span></p>
                </div>
                <div>
                    <p>Exchange Rate:</p>
                    <p>1 jewel = 1000 buttons</p>
                </div>
            </div>
            <br class="clear"><br>
            <div>
                <strong class="center">What would you like to do?</strong><br>
                <div>
                    Exchange <input type="text" class="numeric" value="1" style="width:50px;" name="ex_jewels_count"> jewels for 1000 buttons 
                    <input type="button" name="bank_exchange" data-item="jewels" class="right" value="Exchange"> 
                </div>
                <br>
                <div>
                    Exchange <input type="text" class="numeric" value="1" style="width:50px;" name="ex_buttons_count"> buttons for 1 jewel(s) 
                    <input type="button" name="bank_exchange" data-item="buttons" class="right" value="Exchange"> 
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<div id="exchange_modal" class="hide" title="Exchange">
    <div class="modal center">
        <div class="hide buttons_err">
            Sorry, but you don't have enough buttons<br><br>
            <input type="button" class="close_exchange_modal" value="Close">
        </div>
        <div class="hide buttons_less_err">
            Sorry, but you can not exchange less than 1000 buttons <br><br>
            <input type="button" class="close_exchange_modal" value="Close">
        </div>
        <div class="hide jewels_less_err">
            Sorry, but you can not exchange less than 1 jewel <br><br>
            <input type="button" class="close_exchange_modal" value="Close">
        </div>
        <div class="hide jewels_err">
            Sorry, but you don't have enough jewels<br><br>
            <input type="button" class="close_exchange_modal" value="Close">
        </div>
        <div class="hide exchange_done">
            <strong>Exchange was successful</strong><br><br>
            <input type="button" class="close_exchange_modal" value="Close">
        </div>
    </div>
</div>