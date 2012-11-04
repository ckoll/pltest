<div class="lmenu_block">
    <strong>By this item from the following user shops!</strong><br><br>
    <p class="center">
        <?
        if (!empty($shop)) {
            foreach ($shop as $val) {
                ?>
        
                <a href="/users_shop/<?= $val['username'] ?>">
                    <div class="avatar100">
                        <div class="border"></div>
                        <img src="<? get_user_avatarlink($val['uid']) ?>">
                    </div>
                    <?= $val['username'] ?></a>
                <br>
                <?
            }
        }
        ?>
    </p>
</div>
<div class="lmenu_block">
    <strong>Get this item in our user auctions</strong><br><br>
    <p class="center">
        <?
        if (!empty($auction)) {
            foreach ($auction as $val) {
                ?>
                <a href="/users_shop/<?= $val['username'] ?>">
                    <div class="avatar100">
                        <div class="border"></div>
                        <img src="<? get_user_avatarlink($val['uid']) ?>">
                    </div>
                    <?= $val['username'] ?></a>
                <br>
                <?
            }
        }
        ?>
    </p>
</div>