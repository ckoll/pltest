<div class="signin_bg">
    <div class="signin_page">
        <form action="" method="post">
            <? if(!empty($err)):?>
            <span class="err"><?=$err?></span>
            <? endif; ?>
            <div><label <? if($this->input->post('username'))echo'style="display:none"'?>>Username</label>
                <input type="text" name="username" autocomplete="off" value="<?=@$this->input->post('username')?>">
            </div>
            <div><label>Password</label><input type="password" name="password" autocomplete="off"></div>
            <input type="submit" name="signin" value="Signin" id="signin">
        </form>
    </div>
</div>