<div class="index_bg">
    <div class="index_page">
        <a href="/register" id="register"></a>
        <form action="/signin" method="post">
            <?
            if(!empty($err)){
                ?><span class="err">*<?=$err?></span><?
            }
            ?>
            <div><label>Username</label><input type="text" name="username" value="" autocomplete="off"></div>
            <div><label>Password</label><input type="password" name="password" value="" autocomplete="off"></div>
            <small><a href="/forgotpassword">Lost password?</a></small>
            <input type="submit" value="Signin" name="signin" id="submit">
        </form>
        <a href="/facebook" id="fb_login"></a>
        <a href="/twitter" id="tw_login"></a>
    </div>
</div>