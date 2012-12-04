<div class="signup_bg">
    <div class="signup_page">
        <form action="" method="post">
            <?
            if (!empty($sending)) {
                ?>
                <span>An activation link has been sent to your email. Please click the link to confirm the account.</span>
                <?
            } else {
                if (!empty($err)) {?> <span class="err"><?= $err ?></span> <? } ?>
                <div><label <? if (!empty($_POST['username'])) echo'style="display:none"' ?>>Username</label><input type="text" autocomplete="off" name="username" value="<? if (!empty($_POST['username'])) echo $_POST['username']; ?>"></div>
                <div><label <? if (!empty($_POST['email'])) echo'style="display:none"' ?>>Email</label><input type="text" autocomplete="off" name="email" value="<? if (!empty($_POST['email'])) echo $_POST['email']; ?>"></div>
                <div><label>Password</label><input type="password" autocomplete="off" name="password"></div>
                <div><label>Password Repeat</label><input type="password" autocomplete="off" name="password2"></div>
                <input type="hidden" name="ref_url" value="<?=isset($_SERVER['HTTP-REFERRER'])?$_SERVER['HTTP-REFERRER']:''?>">
                <input type="submit" name="signup" value=" " id="signup">
                <?
            }
            ?>
        </form>
        <div class="info">Already registered but need to confirm your account? <br><a href="/resendemail">[resend activation link]</a></div>
    </div>
</div>