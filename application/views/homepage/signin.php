<div class="signin_bg">
    <div class="signin_page">
        <form action="" method="post">
            <? if(!empty($err)):?>
            <span class="err"><?=$err?></span>
            <? endif; ?>
            <div><label <? if($this->input->post('username'))echo'style="display:none"'?>>Username</label>
                <input type="text" name="username" value="<?=@$this->input->post('username')?>" autocomplete="off">
            </div>
            <div><label>Password</label><input type="password" name="password" autocomplete="off"></div>
            <div>
                <label style="float: left;left: 0;margin-right: 7px;margin-top: 4px;position: relative; top: 0;">Remember Me</label>
                <input type="checkbox" name="remember_me" value="1">
            </div>
            <input type="submit" name="signin" value="Signin" id="signin">
        </form>
    </div>
</div>