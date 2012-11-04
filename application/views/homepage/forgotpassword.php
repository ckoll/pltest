<div class="forgot_bg">
    <div class="forgot_page">
        <form action="" method="post">
            <?
            if (!empty($sended)) {
                ?>
                <span>Email sent.</span>
                <?
            } else {
                if (!empty($err)) {
                    ?><span class="err">*<?= $err ?></span><?
        }
        if (!$this->input->get('key')) {
                    ?>
                    <div><label <? if ($this->input->post('email')) echo'style="display:none"' ?>>Email or Username</label><input type="text" name="email" value="<?= @$this->input->post('email') ?>"></div>
                    <input type="submit" autocomplete="off" name="send" value="Send" id="submit">
                    <?
                }elseif (empty($changed)) {
                    ?>
                    <div><label>Enter password</label><input type="password" name="password" ></div>
                    <div><label>Confirm password</label><input type="password" name="password2" ></div>
                    <input type="submit" name="change" value="CHANGE" id="submit">
                    <?
                }else {
                    ?>
                    <span>Your new password has been <br>set successfully.</span><br>
                    <a href="/signin">Log In</a>&nbsp;&nbsp;&nbsp;<a href="/">Go back to homepage</a>
                    <?
                }
            }
            ?>
        </form>
        <div class="info"><?= $mess ?></div>
    </div>
</div>